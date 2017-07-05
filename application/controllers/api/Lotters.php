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
	public function clear_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			//
			$this->w_db = $this->load->database ( 'vidol_old_write', TRUE );
			$this->w_db->truncate('lottery_iphone_list_tbl');
			$sql = 'UPDATE lottery_iphone_tbl SET status = 1 WHERE id > 1';
			$this->data_result ['status'] = $this->w_db->query($sql);
			$this->w_db->close();
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
			$this->config->load ( 'restful_status_code' );
			$this->load->model ( 'vidol_old/lotters_config_model' );
			$this->load->model ( 'vidol_old/lotters_winners_list_model' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			// 變數
			$data_input = array();
			// input
			$data_input['pk'] = $this->post('pk');
			$data_input['date_range'] = $this->post('date_range');
			$data_input['count'] = $this->post('count');
			// 必填檢查
			if (empty ( $data_input ['pk'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			$lottery_config = $this->lotters_config_model->get_row_lotters_config_by_pk('*', $data_input ['pk']);
			$this->data_result['123'] = $lottery_config;
			switch ($lottery_config->lc_db_type){
				case 'mysql':
					break;
				case 'postgresql':
					break;
				case 'mongo':
					break;
				case 'model':
					$model_group = $lottery_config->lc_db_group;
					$model_name = $lottery_config->lc_db_table;
					$model_function = $lottery_config->lc_db_where;
					$model_select = '*';
					$model_count = $data_input['count'];
					$data_input['date_range'] = str_replace(' - ', ' AND ', $data_input['date_range']);
					$model_where_string = sprintf('b_creat_utc BETWEEN ', $data_input['date_range']);
					$this->load->model ( sprintf('%s/%s', $model_group, $model_name) );
					switch ($lottery_config->lc_value_count){
						default://0
							$query = $this->$model_name->$model_function($model_select, $model_count, $model_where_string);
							break;
						case '1':
							$query = $this->$model_name->$model_function($model_select, $model_count, $model_where_string, $lottery_config->lc_value1);
							break;
						case '2':
							$query = $this->$model_name->$model_function($model_select, $model_count, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2);
							break;
						case '3':
							$query = $this->$model_name->$model_function($model_select, $model_count, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2, $lottery_config->lc_value3);
							break;
						case '4':
							$query = $this->$model_name->$model_function($model_select, $model_count, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2, $lottery_config->lc_value3, $lottery_config->lc_value4);
							break;
						case '5':
							$query = $this->$model_name->$model_function($model_select, $model_count, $model_where_string, $lottery_config->lc_value1, $lottery_config->lc_value2, $lottery_config->lc_value3, $lottery_config->lc_value4, $lottery_config->lc_value5);
							break;
					}
					$this->data_result['query'] = $query;
					break;
				default:
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
}
