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
			echo 'HI';
			$cron = $this->mrplay_model->get_row_Mrplay_cronno('cron_no');
			print_r($cron);
			if(empty($cron) || empty($cron->cron_no)){
				$cron_no = 0;
			}else{
				$cron_no = $cron->cron_no;
			}
			print_r($cron_no);
			$query = $this->boards_model->get_Board_by_type_typeno('b_no, b_message', 'episode', '17899', $cron_no, '10');
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					print_r($row);
					$message = $row->b_message;
					str_replace ('\'', '', $message);
					str_replace ('"', '', $message);
					str_replace (' ', '', $message);
					str_replace (',', '', $message);
					str_replace ('，', '', $message);
					print_r($message);
				}
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
