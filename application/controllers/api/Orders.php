<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Orders extends REST_Controller
{

	private $data_result;

	public function __construct ()
	{
		parent::__construct();
		// 資料庫
		$this->load->database();
		// 權限
		$this->auth = new stdClass();
		$this->load->library('flexi_auth');
		if (! $this->flexi_auth->is_logged_in()) {
			$this->response(NULL, 404);
		}
		// 引用
		$this->load->library('session');
		$this->load->library('lib_log');
		$this->load->library('vidol/fun');
		$this->load->helper('formats');
		// 初始化
		$this->data_result = format_helper_return_data();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function index_get ()
	{
		$this->response(NULL, 404);
	}

	/**
	 * 查詢訂單資料 透過API
	 * 權限 Orders View
	 * transtionID 訂單編號
	 */
	public function search_get ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Orders View')) {
				// 變數
				$data_input = array();
				// 引用
				$this->load->model('vidol_billing/orders_model');
				// input
				$data_input['date_range'] = $this->get('date_range');
				$daterange = explode (' - ', $data_input['date_range']);
				$data_input['start'] = (empty($daterange['0']))? date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -8 day")) : date('Y-m-d', strtotime($daterange['0']));
				$data_input['end'] = (empty($daterange['1']))? date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -1 day")) : date('Y-m-d', strtotime($daterange['1']));
				$data_input['status'] = $this->get('status');
				$data_input['oredr_sn'] = $this->get('oredr_sn');
				$data_input['user'] = $this->get('user');
				//$this->orders_model->get_orders_by_search($data_input['start'], $data_input['end'], $data_input['status'], $data_input['oredr_sn'], $data_input['user']);
				$query = $this->orders_model->get_orders_by_search($data_input['start'], $data_input['end'], $data_input['status'], $data_input['oredr_sn'], $data_input['user']);
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						$this->data_result['data'][] = array(
								'pk'=>$row->o_pk,
								'order_sn'=>$row->o_order_sn,
								'mongo_id'=>$row->o_mongo_id,
								'member_id'=>$row->o_member_id,
								'package_no'=>$row->o_package_no,
								'package_title'=>$row->o_package_title,
								'package_unit'=>$row->o_package_unit,
								'package_unit_value'=>$row->o_package_unit_value,
								'coupon_no'=>$row->o_coupon_no,
								'coupon_title'=>$row->o_coupon_title,
								'cost'=>$row->o_cost,
								'price'=>$row->o_price,
								'invoice_type'=>$row->o_invoice_type,
								'invoice'=>$row->o_invoice,
								'status'=>$row->o_status,
								'time_creat'=>$row->o_time_creat,
								'time_update'=>$row->o_time_update,
								'time_active'=>$row->o_time_active,
								'time_deadline'=>$row->o_time_deadline,
								'ip'=>$row->o_ip,
								'note'=>$row->o_note,
						);
					}
				}
				$this->data_result['input'] = $data_input;
				$this->response($this->data_result, 200);
			} else {
				$this->response(NULL, 401);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
	
	/**
	 * 查詢paykit訂單資料 透過API
	 * 權限 Orders View
	 * transtionID 訂單編號
	 */
	public function paykit_search_get ()
	{
		try {
			if ($this->flexi_auth->is_privileged('Orders View')) {
				// 變數
				$data_input = array();
				// 引用
				$this->load->library('vidol/paykit_api');
				// input
				$data_input['transtionID'] = $this->get('transtionID');
				if (! empty($data_input['transtionID'])) {
					// 寫log
					$this->fun->logs(sprintf('查詢訂單{%s}', $data_input['transtionID']));
					// paykit api 呼叫
					$this->paykit_api->get_token();
					$data_api = $this->paykit_api->get_orders($data_input['transtionID']);
					// print_r($data_api);
					if (isset($data_api->error)) {
						$this->response($this->data_result, 204);
					} else {
						$this->data_result['status'] = true;
						$this->data_result['input'] = $data_input;
						$this->data_result['data'] = $data_api;
						$this->response($this->data_result, 200);
					}
				} else {
					$this->response(NULL, 400);
				}
			} else {
				$this->response(NULL, 401);
			}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
