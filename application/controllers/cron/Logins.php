<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Logins extends CI_Controller
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
	
	/**
	 * 每天不重複登入數計算
	 * @param number $st
	 * http://xxx.xxx.xxx/cron/logins/day
	 * # 分 時 日 月 週 指令
	 * 11 10 * * * php /var/www/codeigniter/3.0.6/admin/index.php cron logins day
	 */
	public function day ($st = 1)
	{
		try {
			// 引用
			$this->load->library('mongo_db');
			$this->load->model('vidol_user/login_model');
			// 變數
			$data_tmpe = array();
			// 迴圈
			for ($i = $st; $i > 0; $i --) {
				$data_tmpe['start_str'] = sprintf('-%d day', $i);
				$data_tmpe['start_time'] = strtotime($data_tmpe['start_str']);
				$data_tmpe['start_date'] = date('Y-m-d', $data_tmpe['start_time']);
				$data_tmpe['end_str'] = sprintf('-%d day', ($i - 1));
				$data_tmpe['end_time'] = strtotime($data_tmpe['end_str']);
				$data_tmpe['end_date'] = date('Y-m-d', $data_tmpe['end_time']);
				// UTC不重複登入數
				$data_tmpe['start_utc'] = new MongoDate(strtotime($data_tmpe['start_date'] . ' +0'));
				$data_tmpe['end_utc'] = new MongoDate(strtotime($data_tmpe['end_date'] . ' +0'));
				//$data_tmpe['count_data_utc'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->distinct('_Session', '_p_user');
				$data_tmpe['count_data_utc'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->distinct('_Session', '_p_user');
				$data_tmpe['count_utc'] = count($data_tmpe['count_data_utc']);
				unset($data_tmpe['count_data_utc']);
				// UTC重複登入數
				//$data_tmpe['count_repeat_utc'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->count('_Session');
				$data_tmpe['count_repeat_utc'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->count('_Session');
				// TW不重複登入數
				$data_tmpe['start_tw'] = new MongoDate(strtotime($data_tmpe['start_date'] . ' +8'));
				$data_tmpe['end_tw'] = new MongoDate(strtotime($data_tmpe['end_date'] . ' +8'));
				//$data_tmpe['count_data_tw'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->distinct('_Session', '_p_user');
				$data_tmpe['count_data_tw'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->distinct('_Session', '_p_user');
				$data_tmpe['count_tw'] = count($data_tmpe['count_data_tw']);
				unset($data_tmpe['count_data_tw']);
				// TW重複登入數
				//$data_tmpe['count_repeat_tw'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->count('_Session');
				$data_tmpe['count_repeat_tw'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->count('_Session');
				// 檢查資料重複
				$data_tmpe['count'] = $this->login_model->check_date_day($data_tmpe['start_date']);
				if($data_tmpe['count'] == 0){
					$data_tmpe['action'] = $this->login_model->insert_login_day ($data_tmpe['start_date'], $data_tmpe['count_utc'], $data_tmpe['count_tw'], $data_tmpe['count_repeat_utc'], $data_tmpe['count_repeat_tw']);
				}
				//print_r($data_tmpe);
				// 銷毀
				unset($data_tmpe);
				$data_tmpe = array();
			}
			// 成功
			$this->data_result['status'] = true;
			// 輸出
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($this->data_result));
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 每月不重複登入數計算
	 * @param number $st
	 * http://xxx.xxx.xxx/cron/logins/month
	 * # 分 時 日 月 週 指令
	 * 11 10 1 * * php /var/www/codeigniter/3.0.6/admin/index.php cron logins month
	 */
	public function month ($type = 0, $st = 1)
	{
		try {
			// 引用
			$this->load->library('mongo_db');
			$this->load->model('vidol_user/login_model');
			// 變數
			$data_tmpe = array();
			switch ( $type ) {
				case 0:
				case '0':
					//當月也計算
					$en = 0;
					break;
   				default:
   					//只計算到上個月
   					$en = 1;
					break;
			}
			// 迴圈
			for ($i = $st; $i >= $en; $i --) {
				$data_tmpe['start_str'] = sprintf('%s -%d month', date('Y-m-01'), $i);
				$data_tmpe['start_time'] = strtotime($data_tmpe['start_str']);
				$data_tmpe['start_date'] = date('Y-m-1', $data_tmpe['start_time']);
				$data_tmpe['end_str'] = sprintf('%s -%d month', date('Y-m-01'), ($i - 1));
				$data_tmpe['end_time'] = strtotime($data_tmpe['end_str']);
				$data_tmpe['end_date'] = date('Y-m-1', $data_tmpe['end_time']);
				// UTC不重複登入數
				$data_tmpe['start_utc'] = new MongoDate(strtotime($data_tmpe['start_date'] . ' +0'));
				$data_tmpe['end_utc'] = new MongoDate(strtotime($data_tmpe['end_date'] . ' +0'));
				//$data_tmpe['count_data_utc'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->distinct('_Session', '_p_user');
				$data_tmpe['count_data_utc'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->distinct('_Session', '_p_user');
				$data_tmpe['count_utc'] = count($data_tmpe['count_data_utc']);
				unset($data_tmpe['count_data_utc']);
				// UTC重複登入數
				//$data_tmpe['count_repeat_utc'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->count('_Session');
				$data_tmpe['count_repeat_utc'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_utc'], $data_tmpe['end_utc'])->count('_Session');
				// TW不重複登入數
				$data_tmpe['start_tw'] = new MongoDate(strtotime($data_tmpe['start_date'] . ' +8'));
				$data_tmpe['end_tw'] = new MongoDate(strtotime($data_tmpe['end_date'] . ' +8'));
				//$data_tmpe['count_data_tw'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->distinct('_Session', '_p_user');
				$data_tmpe['count_data_tw'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->distinct('_Session', '_p_user');
				$data_tmpe['count_tw'] = count($data_tmpe['count_data_tw']);
				unset($data_tmpe['count_data_tw']);
				// TW重複登入數
				//$data_tmpe['count_repeat_tw'] = $this->mongo_db->where('createdWith.action', 'login')->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->count('_Session');
				$data_tmpe['count_repeat_tw'] = $this->mongo_db->where_between('_created_at', $data_tmpe['start_tw'], $data_tmpe['end_tw'])->count('_Session');
				// 檢查資料重複
				$data_tmpe['count'] = $this->login_model->check_date_month($data_tmpe['start_date']);
				if($data_tmpe['count'] == 0){
					$data_tmpe['action'] = $this->login_model->insert_login_month ($data_tmpe['start_date'], $data_tmpe['count_utc'], $data_tmpe['count_tw'], $data_tmpe['count_repeat_utc'], $data_tmpe['count_repeat_tw']);
				}else{
					$data_tmpe['action'] = $this->login_model->update_login_month_by_date ($data_tmpe['start_date'], $data_tmpe['count_utc'], $data_tmpe['count_tw'], $data_tmpe['count_repeat_utc'], $data_tmpe['count_repeat_tw']);
				}
				//print_r($data_tmpe);
				$this->data_result['data_tmpe'] = $data_tmpe;
				// 銷毀
				unset($data_tmpe);
				$data_tmpe = array();
			}
			// 成功
			$this->data_result['status'] = true;
			// 輸出
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($this->data_result));
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
