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
			// 記錄時間
			$tmp_data = date("Y-m-d H:00:00", $start_time);
			$tmp_time = strtotime($tmp_data);
			// 結束時間
			$end_time = strtotime($date . "-0 hour");
			$end_date = date("Y-m-d H:00:00", $end_time);
			// 查詢
			$data_mongo_user_count = $this->mongo_model->get_user_count_by_createdat($start_date, $end_date);
			$data_mongo_user_fb_count = $this->mongo_model->get_user_fb_count_by_createdat($start_date, $end_date);
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
			$data['r_time'] = $tmp_time;
			$data['r_re_count'] = $data_mongo_user_count - $data_mongo_user_fb_count;
			$data['r_fb_count'] = $data_mongo_user_fb_count;
			$data['r_count'] = $data_mongo_user_count;
			
			print_r($data);
			// 組合
			//$sql = "INSERT INTO `Registered_tbl` (`r_date_utc`, `r_year_utc`, `r_month_utc`, `r_day_utc`, `r_hour_utc`, `r_date_tw`, `r_year_tw`, `r_month_tw`, `r_day_tw`, `r_hour_tw`, `r_time`, `r_re_count`, `r_fb_count`, `r_count`) VALUES ('%s', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d') ON DUPLICATE KEY UPDATE `r_time` = '%s', `r_re_count` = '%s', `r_fb_count` = '%s', `r_count` = '%s';";
			//$sql = sprintf($sql, $data['r_date_utc'], $data['r_year_utc'], $data['r_month_utc'], $data['r_day_utc'], $data['r_hour_utc'], $data['r_date_tw'], $data['r_year_tw'], $data['r_month_tw'], $data['r_day_tw'], $data['r_hour_tw'], $data['r_time'], $data['r_re_count'], $data['r_fb_count'], $data['r_count'], $data['r_time'], $data['r_re_count'], $data['r_fb_count'], $data['r_count']);
			//if ($this->db->simple_query($sql)) {
				//trigger_error(sprintf("統計註冊數[%s].", $tmp_data), 1024);
				//$this->data_result['status'] = true;
				//$this->data_result['data'] = $data;
			//}
			// 輸出
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($this->data_result));
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
