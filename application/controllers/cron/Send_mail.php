<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Send_mail extends CI_Controller {
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
	 * 執行寄信的資料
	 * http://xxx.xxx.xxx/cron/send_mail/send
	 * # 分 時 日 月 週 指令
	 * (1) * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron send_mail send
	 */
	public function send() {
		try {
			// 引用
			$this->load->library ( 'email' );
			$this->load->model ( 'vidol_cron/send_mail_model' );
			$this->load->helper ( 'email' ); // 檢查email
			$this->load->helper ( 'formats' );
			// 變數
			$data_tmpe = array ();
			// 取得帳號id
			// $uid = $this->flexi_auth->get_user_id();
			// $this->send_mail_model->insert_send_mail ('test', $uid, 'zeren828@gmail.com', 'zeren828@gmail.com', 'zeren828@gmail.com', 'zeren828@gmail.com,zeren828@gmail.com', 'zeren828@gmail.com,zeren828@gmail.com', '主題', '內容');
			$query = $this->send_mail_model->get_rows_by_status ( '*', '0', '50' );
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					print_r ( $row );
					// 檢查電子信箱格式
					if (valid_email ( $row->sm_to )) {
						// mail格式類型
						$this->email->set_mailtype ( $row->sm_type );
						// mail寄件人
						$this->email->from ( $row->sm_from );
						// mail收件人
						$this->email->to ( $row->sm_to );
						// mail回覆位置
						$this->email->reply_to ( $row->sm_reply_to );
						// mail副本
						$this->email->cc ( json_decode ( $row->sm_cc ) );
						// mail密件副本(不會顯示名子)
						$this->email->bcc ( json_decode ( $row->sm_bcc ) );
						// mail標題
						$this->email->subject ( $row->sm_title );
						// mail內文
						$this->email->message ( $row->sm_content );
						// 發送
						if ($this->email->send ()) {
							// echo 'Email sent.';
							$this->send_mail_model->update_for_data_by_pk ( $row->sm_pk, array (
									'sm_status' => '1' 
							) );
						} else {
							// 寄信失敗
							$mail_err = show_error ( $this->email->print_debugger () );
							$this->send_mail_model->update_for_data_by_pk ( $row->sm_pk, array (
									'sm_note' => $mail_err,
									'sm_status' => '-1' 
							) );
						}
					} else {
						// 失敗信箱規格錯誤
						$this->send_mail_model->update_for_data_by_pk ( $row->sm_pk, array (
								'sm_note' => 'email format error!',
								'sm_status' => '-2' 
						) );
					}
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
