<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require APPPATH . '/libraries/REST_Controller.php';
class Uploads extends REST_Controller {
	private $data_result;
	public function __construct() {
		parent::__construct ();
		// 資料庫
		$this->load->database ();
		// 權限
		$this->auth = new stdClass ();
		$this->load->library ( 'flexi_auth' );
		if (! $this->flexi_auth->is_logged_in ()) {
			$this->response ( NULL, 404 );
		}
		// 引用
		$this->load->library ( 'session' );
		$this->load->library ( 'lib_log' );
		$this->load->library ( 'vidol/fun' );
		$this->load->helper ( 'formats' );
		// 初始化
		$this->data_result = format_helper_return_data ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function index_get() {
		$this->response ( NULL, 404 );
	}
	
	// 搭配views/AdminLTE/dealers/user_binding.php
	public function tvbox_mac_csv_post() {
		try {
			$config ['upload_path'] = '/var/www/codeigniter/3.0.6/admin/assets/uploads/device/device_mac/';
			$config ['allowed_types'] = 'csv';
			$this->load->library ( 'upload', $config );
			$this->upload->initialize ( $config );
			$this->upload->set_allowed_types ( '*' );
			$upload_data = array ();
			if (! $this->upload->do_upload ( 'file' )) {
				// 上傳失敗
				$this->data_result ['status'] = false;
				$this->data_result ['info'] = $this->upload->display_errors ();
			} else {
				// 上傳成功
				$upload_data = $this->upload->data ();
				if ($upload_data ['file_ext'] == '.csv') {
					$this->data_result ['status'] = true;
					$this->load->model ( 'vidol_dealer/user_binding_model' );
					$file_path = $upload_data ['full_path'];
					$fp = fopen ( $file_path, 'r' ) or die ( "can't open file" );
					while ( $csv_line = fgetcsv ( $fp, 1024 ) ) {
						$dealer = $csv_line [0];
						$device_id = $csv_line [1];
						$device_mac = strtoupper($csv_line [2]);
						$device_key = md5 ( sprintf ( 'vido1-%s', str_replace ( ':', '-', $device_mac ) ) );
						$insert_data = array (
								'ub_dealer' => $dealer,
								'ub_device_id' => $device_id,
								'ub_device_mac' => $device_mac,
								'ub_device_key' => $device_key 
						);
						$this->user_binding_model->insert_User_binding_for_data ( $insert_data );
						unset ( $insert_data );
						unset ( $device_key );
						unset ( $device_mac );
						unset ( $device_id );
						unset ( $dealer );
					}
					fclose ( $fp ) or die ( "can't close file" );
				}
			}
			$this->response($this->data_result, 200);
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function user_pay_tmp_post() {
		try {
			$config ['upload_path'] = '/var/www/codeigniter/3.0.6/admin/assets/uploads/users/';
			$config ['allowed_types'] = 'xlsx,csv';
			$this->load->library ( 'upload', $config );
			$this->upload->initialize ( $config );
			$this->upload->set_allowed_types ( '*' );
			$upload_data = array ();
			if (! $this->upload->do_upload ( 'file' )) {
				// 上傳失敗
				$this->data_result ['status'] = false;
				$this->data_result ['info'] = $this->upload->display_errors ();
			} else {
				// 上傳成功
				$upload_data = $this->upload->data ();
				$this->load->model ( 'vidol_user/user_pay_model' );
				$this->data_result['upload'] = $upload_data;
				$file_path = $upload_data ['full_path'];
				$fp = fopen ( $file_path, 'r' ) or die ( "can't open file" );
				while ( $csv_line = fgetcsv ( $fp, 1024 ) ) {
					if(!empty($csv_line [3]) && $csv_line [3] != 'email' && $csv_line [3] != '#REF!'){
						$insert_data = array (
								'up_name' => $csv_line [0],
								'up_email' => $csv_line [3],
								'up_money' => $csv_line [1],
								'up_date' => $csv_line [2],
						);
						$this->user_pay_model->not_repeating_User_pay('up_pk', $insert_data);
						unset($insert_data);
					}
				}
				fclose ( $fp ) or die ( "can't close file" );
			}
			$this->response($this->data_result, 200);
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
