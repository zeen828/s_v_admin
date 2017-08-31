<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Send_sms extends CI_Controller {
	private $data_result;
	function __construct() {
		parent::__construct ();
		// 資料庫
		// $this->load->database ();
		// 權限
		// $this->auth = new stdClass ();
		// $this->load->library ( 'flexi_auth' );
		// 初始化
		// $this->data_result = format_helper_return_data ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function index() {
		show_404 ();
	}
	
	/**
	 * 執行發簡訊的資料
	 * http://xxx.xxx.xxx/cron/send_sms/send
	 * # 分 時 日 月 週 指令
	 * (1) * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron send_sms send
	 */
	public function send() {
		try {
			// 引用
			$this->config->load ( 'smexpress_sms' );
			$this->load->model ( 'vidol_cron/send_sms_model' );
			$this->load->helper ( 'formats' );
			// 變數
			$data_tmpe = array ();
			// config
			$sms_array = array (
					'username' => $this->config->item ( 'sms_usermname' ),
					'password' => $this->config->item ( 'sms_password' ),
					'dstaddr' => '',
					'DestName' => 'vidol',
					'encoding' => 'UTF8',
					'smbody' => '' 
			);
			$send_api_url = $this->config->item ( 'sms_send_api_url' );
			// 取要寄簡訊的資料
			$query = $this->send_sms_model->get_rows_by_status ( '*', '0', '50' );
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					// print_r ( $row );
					$sms_array ['dstaddr'] = $row->ss_phone;
					$sms_array ['smbody'] = $row->ss_msm;
					$api_url_query = http_build_query ( $sms_array );
					// 發送
					$api_url = sprintf ( '%s?%s', $send_api_url, $api_url_query );
					$ch = curl_init ();
					curl_setopt ( $ch, CURLOPT_URL, $api_url );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
					$output = curl_exec ( $ch );
					curl_close ( $ch );
					//
					$this->send_sms_model->update_for_data_by_pk ( $row->ss_pk, array (
							'ss_url' => $api_url,
							'ss_data' => $api_url_query,
							'ss_response' => $output,
							'ss_status' => '1' 
					) );
				}
			}
			// 成功
			$this->data_result ['status'] = true;
			// 輸出
			$this->output->set_content_type ( 'application/json' );
			$this->output->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
