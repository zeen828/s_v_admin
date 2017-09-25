<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Lotteries extends MY_REST_Controller {
	private $data_debug;
	private $data_result;
	public function __construct() {
		parent::__construct ();
		$this->_my_logs_start = true;
		$this->_my_logs_type = 'auth';
		$this->data_debug = true;
		// 引入
		$this->config->load ( 'restful_status_code' );
		$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
		// 資料庫
		// $this->load->database ( 'vidol_billing_write' );
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function __destruct() {
		parent::__destruct ();
		unset ( $this->data_debug );
		unset ( $this->data_result );
	}
	public function lottery_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->load->model ( 'vidol_event/event_vote_config_model' );
			$this->load->model ( 'vidol_event/event_vote_lottery_model' );
			$this->load->model ( 'vidol_event/event_vote_select_model' );
			$this->load->model ( 'vidol_event/whitelist_model' );
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 變數
			$data_input = array ();
			$data_config = array ();
			$date_user = array ();
			$data_count = array (
					'max' => '0',
					'lottery' => '0',
					'now' => '0' 
			);
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['config_id'] = $this->post ( 'config_id' );
			// Debug info
			$data_input ['debug'] = $this->post ( 'debug' );
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['input'] = &$data_input;
				$this->data_result ['debug'] ['vote_config'] = $data_config;
				$this->data_result ['debug'] ['count'] = &$data_count;
				$this->data_result ['debug'] ['user'] = &$date_user;
			}
			// 必填檢查
			if (empty ( $data_input ['config_id'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				// 時間標記
				$this->benchmark->mark ( 'error_required' );
				// 標記時間計算
				$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'error_required' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 1.取得活動設定-獎項名額
			// cache name key
			$data_cache ['name'] = sprintf ( '%s_event_vote_config_%d', ENVIRONMENT, $data_input ['config_id'] );
			// $this->cache->memcached->delete ( $data_cache['name_1'] );
			$data_cache [$data_cache ['name']] = $this->cache->memcached->get ( $data_cache ['name'] );
			if ($data_cache [$data_cache ['name']] == false) {
				$data_cache [$data_cache ['name']] = $this->event_vote_config_model->get_row_by_pk ( '*', $data_input ['config_id'] );
				$this->cache->memcached->save ( $data_cache ['name'], $data_cache [$data_cache ['name']], 90000 );
			}
			$data_config = $data_cache [$data_cache ['name']];
			$data_count ['max'] = $data_config->lottery_int;
			// 2.取得目前得獎名額
			$data_count ['lottery'] = $this->event_vote_lottery_model->get_count_by_configid ( $data_input ['config_id'] );
			// 3.檢查名額
			$data_count ['now'] = $data_count ['max'] - $data_count ['lottery'];
			if ($data_count ['now'] <= 0) {
				// 抽獎名額已滿
				$this->data_result ['message'] = $this->lang->line ( 'database_full_error' );
				$this->data_result ['code'] = $this->config->item ( 'database_full_error' );
				// 時間標記
				$this->benchmark->mark ( 'error_full' );
				// 標記時間計算
				$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'error_full' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 保險避免無線迴圈
			$insurance = 10;
			do {
				// 4.白單
				$date_user = $this->whitelist_model->get_row_by_random ( 1 );
				if ($date_user != false && isset ( $date_user->id )) {
					// 4.消白單
					$this->whitelist_model->update_data ( $date_user->id, array (
							'status' => '0' 
					) );
				} else {
					// 5.抽獎
					$date_user = $this->event_vote_select_model->get_row_by_random ( $data_input ['config_id'], 1 );
				}
				// 6.確認無重複
				$confirm_repeat = $this->event_vote_lottery_model->confirm_repeat ( $data_input ['config_id'], $date_user->user_id, $date_user->member_id );
				// 保險避免無線迴圈
				if ($insurance == 0) {
					$confirm_repeat = false;
				} else {
					$insurance --;
				}
			} while ( $confirm_repeat );
			// 7.紀錄
			$this->event_vote_lottery_model->insert_data ( array (
					'config_id' => $data_config->id,
					'title' => $data_config->title,
					'award_number' => $data_count ['now'],
					'mongo_id' => $date_user->mongo_id,
					'member_id' => $date_user->member_id,
					'user_ip' => $this->input->ip_address () 
			) );
			// 8.得獎名單
			$this->data_result ['result'] = $date_user->member_id;
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
