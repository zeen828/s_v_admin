<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Lotters extends MY_REST_Controller {
	private $data_debug;
	private $data_result;
	public function __construct() {
		parent::__construct ();
		$this->_my_logs_start = false;
		$this->_my_logs_type = 'lotters';
		$this->data_debug = true;
		// 引入
		$this->config->load ( 'restful_status_code' );
		$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function __destruct() {
		parent::__destruct ();
		unset ( $this->data_debug );
		unset ( $this->data_result );
	}
	public function clear_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			//
			$this->w_db = $this->load->database ( 'vidol_old_write', TRUE );
			$this->w_db->truncate ( 'lottery_iphone_list_tbl' );
			$sql = 'UPDATE lottery_iphone_tbl SET status = 1 WHERE id > 1';
			$this->data_result ['status'] = $this->w_db->query ( $sql );
			$this->w_db->close ();
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	/**
	 * 抽獎活動 >> 抽獎系統-中獎名單 >> 抽獎AJAX
	 */
	public function lottery_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->load->model ( 'vidol_old/lotters_config_model' );
			$this->load->model ( 'vidol_old/lotters_winners_list_model' );
			// 變數
			$data_input = array ();
			// input
			$data_input ['pk'] = $this->post ( 'pk' );
			$data_input ['date_range'] = $this->post ( 'date_range' ); // 2017-06-27 - 2017-07-04
			$data_input ['like'] = $this->post ( 'like' );
			$data_input ['count'] = $this->post ( 'count' );
			// 必填檢查
			if (empty ( $data_input ['pk'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			$lottery_config = $this->lotters_config_model->get_row_lotters_config_by_pk ( '*', $data_input ['pk'] );
			$this->data_result ['123'] = $lottery_config;
			switch ($lottery_config->lc_db_type) {
				case 'mysql' :
					$this->data_result ['mysql'] = 'mysql';
					$this->response ( $this->data_result, 404 );
					return;
					break;
				case 'postgresql' :
					$this->data_result ['postgresql'] = 'postgresql';
					$this->response ( $this->data_result, 404 );
					return;
					break;
				case 'mongo' :
					$this->data_result ['mongo'] = 'mongo';
					$this->response ( $this->data_result, 404 );
					return;
					break;
				case 'model' :
					$this->data_result ['model'] = 'model';
					$model_group = $lottery_config->lc_db_group;
					$model_name = $lottery_config->lc_db_table;
					$model_select = $lottery_config->lc_db_select;
					$model_function = $lottery_config->lc_db_where;
					$model_like = $data_input ['like'];
					$model_count = $data_input ['count'];
					// 日期處理沒自己加'會被CI變成`無法正確查詢
					$model_where_string = '';
					if (! empty ( $data_input ['date_range'] )) {
						$data_input ['date_range'] = str_replace ( ' - ', '\' AND \'', $data_input ['date_range'] );
						$model_where_string = sprintf ( 'b_creat_utc BETWEEN \'%s\'', $data_input ['date_range'] );
					}
					$this->load->model ( sprintf ( '%s/%s', $model_group, $model_name ) );
					switch ($lottery_config->lc_value_count) {
						// 資料塞選, 資料取得筆數, 額外條件, like額外條件, 設定變數1, 設定變數2, 設定變數3, 設定變數4, 設定變數5
						default : // 0
							$this->data_result ['lc_value_count'] = '0';
							$query = $this->$model_name->$model_function ( $model_select, $model_count, $model_like, $model_where_string );
							break;
						case '1' :
							$this->data_result ['lc_value_count'] = '1';
							$query = $this->$model_name->$model_function ( $model_select, $model_count, $model_like, $model_where_string, $lottery_config->lc_value1 );
							break;
						case '2' :
							$this->data_result ['lc_value_count'] = '2';
							$query = $this->$model_name->$model_function ( $model_select, $model_count, $model_like, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2 );
							break;
						case '3' :
							$this->data_result ['lc_value_count'] = '3';
							$query = $this->$model_name->$model_function ( $model_select, $model_count, $model_like, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2, $lottery_config->lc_value3 );
							break;
						case '4' :
							$this->data_result ['lc_value_count'] = '4';
							$query = $this->$model_name->$model_function ( $model_select, $model_count, $model_like, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2, $lottery_config->lc_value3, $lottery_config->lc_value4 );
							break;
						case '5' :
							$this->data_result ['lc_value_count'] = '5';
							$query = $this->$model_name->$model_function ( $model_select, $model_count, $model_like, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2, $lottery_config->lc_value3, $lottery_config->lc_value4, $lottery_config->lc_value5 );
							break;
					}
					$this->data_result ['query'] = $query;
					if ($query->num_rows () > 0) {
						foreach ( $query->result () as $row ) {
							$data_input = array (
									'lw_lc_pk' => $lottery_config->lc_pk,
									'lw_lc_title' => $lottery_config->lc_title,
									'lw_mongo_id' => $row->mongo_id,
									'lw_member_id' => $row->member_id,
									'lw_status' => '1' 
							);
							$this->lotters_winners_list_model->insert_lotters_winners_list_for_data ( $data_input );
						}
					}
					break;
				default :
					$this->data_result ['default'] = 'default';
					break;
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 清除抽獎中獎名單
	 */
	public function lottery_clear_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->load->model ( 'vidol_old/lotters_winners_list_model' );
			// 變數
			$data_input = array ();
			// input
			$data_input ['pk'] = $this->post ( 'pk' );
			// 必填檢查
			if (empty ( $data_input ['pk'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 清除中獎名單
			$this->lotters_winners_list_model->clear_winners_list_by_lw_lc_pk ( $data_input ['pk'] );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function whitelist_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->load->model ( 'vidol_event/whitelist_model' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['mongo_id'] = $this->post ( 'config_id' );
			$data_input ['member_id'] = $this->post ( 'member_id' );
			$data_input ['status'] = $this->post ( 'status' );
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
			$this->data_result ['result'] = $this->whitelist_model->insert_data ( $data_input );
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
