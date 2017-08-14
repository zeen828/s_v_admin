<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Report_event extends CI_Controller
{

	private $data_result;

	function __construct ()
	{
		parent::__construct();
		// 資料庫
		$this->load->database();
		// 引用
		$this->load->library('session');
		$this->load->library('lib_log');
		$this->load->helper('formats');
		// 初始化
		$this->data_result = format_helper_return_data();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		show_404();
	}

	public function demo ()
	{
		print_r(array('DEMO'));
	}

	public function vote ($config_id, $date = '')
	{
		try {
			//
			$data = array();
			// 引用
			$this->load->model('mongo_model');
			// 開始時間
			$start_time = strtotime($date . "-1 hour");
			$start_date = date("Y-m-d H:00:00", $start_time);
			print_r($start_date);
			// 記錄時間
			$tmp_data = date("Y-m-d H:00:00", $start_time);
			$tmp_time = strtotime($tmp_data);
			print_r($tmp_time);
			// 結束時間
			$end_time = strtotime($date . "-0 hour");
			$end_date = date("Y-m-d H:00:00", $end_time);
			print_r($end_date);
			// 寫入資料庫用
			// Y年,n月,j日,G時
			$data['r_date_utc'] = date("Y-m-d H:i:s", strtotime($tmp_data . "-8 hour"));
			$data['r_year_utc'] = date("Y", strtotime($tmp_data . "-8 hour"));
			$data['r_month_utc'] = date("n", strtotime($tmp_data . "-8 hour"));
			$data['r_day_utc'] = date("j", strtotime($tmp_data . "-8 hour"));
			$data['r_hour_utc'] = date("G", strtotime($tmp_data . "-8 hour"));
			$data['r_date_tw'] = date("Y-m-d H:i:s", $tmp_time);
			$data['r_year_tw'] = date("Y", $tmp_time);
			$data['r_month_tw'] = date("n", $tmp_time);
			$data['r_day_tw'] = date("j", $tmp_time);
			$data['r_hour_tw'] = date("G", $tmp_time);
			print_r($data);
			// 輸出
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($this->data_result));
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
