<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set ( "display_errors", "On" ); // On, Off
/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Votes extends CI_Controller
{
	private $data_debug;
	private $data_result;

	function __construct ()
	{
		parent::__construct();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		show_404();
	}

	public function mrplay_boards ()
	{
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->load->model ( 'vidol_websocket/boards_model' );
			$this->load->model ( 'vidol_websocket/mrplay_model' );
			$cron = $this->mrplay_model->get_row_Mrplay_cronno('cron_no');
			if(empty($cron) || empty($cron->cron_no)){
				$cron_no = 0;
			}else{
				$cron_no = $cron->cron_no;
			}
			$query = $this->boards_model->get_Board_by_type_typeno('b_no, b_message', 'episode', '17899', $cron_no, '10');
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					print_r($row);
					$message = $row->b_message;
					$message = str_replace ('\'', '', $message);
					$message = str_replace ('"', '', $message);
					$message = str_replace (' ', '', $message);
					$message = str_replace (',', '', $message);
					$message = str_replace ('，', '', $message);
					$message = str_replace ('我的學校是', '', $message);
					print_r($message);
					$str_sec = explode ('我', $message);
					print_r($str_sec);
					if(!empty($str_sec['0'])){
						$school = $str_sec['0'];
						print_r($school);
// 						$mrplay = $this->mrplay_model->get_row_Mrplay_by_school('*', $school);
// 						if(empty($mrplay)){
// 							//新增
// 							$data = array(
// 									'school '=>'$school',
// 									'count '=>'1',
// 									'cron_no '=>$row->b_no,
// 							);
// 							$this->mrplay_model->insert_Mrplay_for_data($data);
// 						}else{
// 							//更新
// 							$data = array(
// 									'count '=>$mrplay->count + 1,
// 									'cron_no '=>$row->b_no,
// 							);
// 							$this->mrplay_model->update_Mrplay_for_data($mrplay->pk, $data);
// 						}
					}
				}
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
