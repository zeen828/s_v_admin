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
			$query = $this->boards_model->get_Board_by_type_typeno('*', 'episode', '17899', '0', '100');
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					print_r($row);
				}
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
