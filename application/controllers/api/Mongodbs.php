<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require APPPATH . '/libraries/REST_Controller.php';
class Mongodbs extends REST_Controller {
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
	
	/**
	 * 查詢會員資料 透過mongo
	 * 權限 Users View
	 * email 會員E-mail或帳號
	 */
	public function search_user_get() {
		try {
			if ($this->flexi_auth->is_privileged ( 'Users View' )) {
				$this->benchmark->mark ( 'code_start' );
				// 引入
				$this->load->model ( 'mongo_model' );
				// 變數
				$data_input = array ();
				// input
				$data_input ['email'] = $this->get ( 'email' );
				if (! empty ( $data_input ['email'] )) {
					$data_input ['email_sprintf'] = sprintf ( '/%s/', $data_input ['email'] );
					// 寫log
					$this->fun->logs ( sprintf ( '查詢會員{%s}', $data_input ['email'] ) );
					$data_mongo = $this->mongo_model->get_user_by_id_memberid_or_email ( $data_input ['email_sprintf'] );
					if (! empty ( $data_mongo ) && count ( $data_mongo ) > 0) {
						$this->data_result ['status'] = true;
						$this->data_result ['input'] = $data_input;
						foreach ( $data_mongo as $key => $document ) {
							$this->data_result ['tmp'] = $document;
							if (isset ( $document ['_auth_data_facebook'] )) {
								$user = array (
										'user_id' => $document ['_id'],
										'fb_id' => $document ['_auth_data_facebook'] ['id'],
										'member_id' => $document ['member_id'],
										'username' => '',
										'nick_name' => $document ['nick_name'],
										'email' => '',
										'_email_verify_token' => '',
										'_perishable_token' => (isset ( $document ['_perishable_token'] )) ? $document ['_perishable_token'] : '',
										'verified' => true,
										'creat_time' => date ( 'm/d/Y H:i:s', $document ['_created_at']->sec ),
										'update_time' => date ( 'm/d/Y H:i:s', $document ['_updated_at']->sec ) 
								);
							} else {
								$user = array (
										'user_id' => $document ['_id'],
										'fb_id' => '',
										'member_id' => $document ['member_id'],
										'username' => $document ['username'],
										'nick_name' => $document ['nick_name'],
										'email' => $document ['email'],
										// '_email_verify_token' => $document['_email_verify_token'],
										'_email_verify_token' => (isset ( $document ['_email_verify_token'] )) ? $document ['_email_verify_token'] : '',
										'_perishable_token' => (isset ( $document ['_perishable_token'] )) ? $document ['_perishable_token'] : '',
										'verified' => $document ['emailVerified'],
										'creat_time' => date ( 'm/d/Y H:i:s', $document ['_created_at']->sec ),
										'update_time' => date ( 'm/d/Y H:i:s', $document ['_updated_at']->sec ) 
								);
							}
							// profile
							$user ['profile'] ['full_name'] = (isset ( $document ['full_name'] )) ? $document ['full_name'] : '';
							$user ['profile'] ['contact_email'] = (isset ( $document ['contact_email'] )) ? $document ['contact_email'] : '';
							$user ['profile'] ['contact_number'] = (isset ( $document ['contact_number'] )) ? $document ['contact_number'] : '';
							$user ['profile'] ['birth_date'] = (isset ( $document ['birth_date'] )) ? $document ['birth_date'] : '';
							$user ['profile'] ['gender'] = (isset ( $document ['gender'] )) ? $document ['gender'] : '';
							$user ['profile'] ['occupation'] = (isset ( $document ['occupation'] )) ? $document ['occupation'] : '';
							$user ['profile'] ['address'] = (isset ( $document ['address'] )) ? $document ['address'] : '';
							$this->data_result ['data'] [] = $user;
						}
						$this->response ( $this->data_result, 200 );
					} else {
						$this->response ( $this->data_result, 204 );
					}
				} else {
					$this->response ( NULL, 400 );
				}
			} else {
				$this->response ( NULL, 401 );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 會員登入紀錄查詢
	 */
	public function user_login_log_get() {
		try {
			if ($this->flexi_auth->is_privileged ( 'Users View' )) {
				$this->benchmark->mark ( 'code_start' );
				// 引入
				$this->load->model ( 'mongo_model' );
				// 變數
				$data_input = array ();
				// input
				$data_input ['id'] = $this->get ( 'id' );
				if (! empty ( $data_input ['id'] )) {
					$data_input ['id_sprintf'] = sprintf ( '/%s/^', $data_input ['id'] );
					// 寫log
					$this->fun->logs ( sprintf ( '查詢會員{%s}登入紀錄', $data_input ['id'] ) );
					$data_mongo = $this->mongo_model->get_user_session_by_p_user ( $data_input ['id_sprintf'] );
					if (! empty ( $data_mongo ) && count ( $data_mongo ) > 0) {
						$this->data_result ['status'] = true;
						$this->data_result ['input'] = $data_input;
						foreach ( $data_mongo as $key => $document ) {
							$this->data_result ['data'] [] = array (
									'session_id' => $document ['_id'],
									'user_id' => str_replace ( '_User$', '', $document ['_p_user'] ),
									'action' => $document ['createdWith'] ['action'],
									'action_type' => $document ['createdWith'] ['authProvider'],
									'restricted' => $document ['restricted'],
									'creat_time' => date ( 'm/d/Y H:i:s', $document ['_created_at']->sec ),
									'update_time' => date ( 'm/d/Y H:i:s', $document ['_updated_at']->sec ),
									'expires_time' => date ( 'm/d/Y H:i:s', $document ['expiresAt']->sec ) 
							);
						}
						$this->response ( $this->data_result, 200 );
					} else {
						$this->response ( $this->data_result, 204 );
					}
				} else {
					$this->response ( NULL, 400 );
				}
			} else {
				$this->response ( NULL, 401 );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 補發忘記密碼
	 */
	public function send_mail_password_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 權限檢查
			if (! $this->flexi_auth->is_privileged ( 'Users View' )) {
				// 權限錯誤
				$this->data_result ['message'] = $this->lang->line ( 'permissions_error' );
				$this->data_result ['code'] = $this->config->item ( 'permissions_error' );
				$this->response ( $this->data_result, 401 );
				return;
			}
			// 引入
			$this->config->load ( 'vidol' );
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'vidol', 'traditional-chinese' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->library ( 'email' );
			$this->load->model ( 'mongo_model' );
			$this->load->model ( 'vidol_cron/send_mail_model' );
			$this->load->helper ( 'email' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['member_id'] = $this->get ( 'member_id' );
			$data_input ['debug'] = $this->get ( 'debug' );
			// 必填檢查
			if (empty ( $data_input ['member_id'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			$data_mongo = $this->mongo_model->get_user_by_memberid ( $data_input ['member_id'] );
			if (empty ( $data_mongo ) || count ( $data_mongo ) <= 0) {
				// 沒有資料
				$this->data_result ['message'] = $this->lang->line ( 'database_not_data	' );
				$this->data_result ['code'] = $this->config->item ( 'database_not_data	' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			if (empty ( $data_mongo ['email'] ) || empty ( $data_mongo ['_perishable_token'] )) {
				// 資料格式錯誤
				$this->data_result ['message'] = $this->lang->line ( 'database_format_error' );
				$this->data_result ['code'] = $this->config->item ( 'database_format_error' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// 標題
			$email_title = $this->lang->line ( 'email_password_title' );
			// 信件內容
			$email_content = $this->lang->line ( 'email_password_content' );
			// 取變數
			$email_user_password_uri = $this->config->item ( 'email_user_password_uri' );
			$email_user_password_doman = $this->config->item ( 'email_user_password_doman' );
			$email_user_password_id = $this->config->item ( 'email_user_password_id' );
			$email_url = sprintf ( $email_user_password_uri, $email_user_password_doman, urlencode ( $data_mongo ['_perishable_token'] ), $email_user_password_id, urlencode ( $data_mongo ['email'] ) );
			// chrome
			// 主要網址不能urlencode會變搜尋,傳遞資料要urlencode否則會被當特殊字完處理
			$content = sprintf ( $email_content, $email_url );
			$this->data_result ['data'] = $content;
			// 紀錄
			$uid = $this->flexi_auth->get_user_id (); // 取得帳號id
			                                          // 紀錄
			$uid = $this->flexi_auth->get_user_id (); // 取得帳號id
			$data_insert = array (
					'sm_source' => 'backend_password',
					'sm_user_accounts_no' => $uid,
					'sm_from' => $this->config->item ( 'email_from' ),
					'sm_to' => $data_mongo ['email'],
					'sm_reply_to' => '',
					'sm_cc' => '',
					'sm_bcc' => json_encode ( $this->config->item ( 'email_bcc' ) ),
					'sm_title' => $email_title,
					'sm_content' => $content 
			);
			$insert_id = $this->send_mail_model->insert_for_data ( $data_insert );
			// 成功
			$this->data_result ['result'] = $insert_id;
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_mongo'] = $data_mongo;
				$this->data_result ['debug'] ['email_title'] = $email_title;
				$this->data_result ['debug'] ['email_content'] = $email_content;
				$this->data_result ['debug'] ['email_user_password_uri'] = $email_user_password_uri;
				$this->data_result ['debug'] ['email_user_password_doman'] = $email_user_password_doman;
				$this->data_result ['debug'] ['email_user_password_id'] = $email_user_password_id;
				$this->data_result ['debug'] ['email_url'] = $email_url;
				$this->data_result ['debug'] ['content'] = $content;
				$this->data_result ['debug'] ['data_insert'] = $data_insert;
			}
			unset ( $data_insert );
			unset ( $content );
			unset ( $email_url );
			unset ( $email_user_password_id );
			unset ( $email_user_password_doman );
			unset ( $email_user_password_uri );
			unset ( $email_content );
			unset ( $email_title );
			unset ( $data_mongo );
			unset ( $data_input );
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
	 * 修改密碼
	 */
	public function change_password_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 權限檢查
			if (! $this->flexi_auth->is_privileged ( 'Users View' )) {
				// 權限錯誤
				$this->data_result ['message'] = $this->lang->line ( 'permissions_error' );
				$this->data_result ['code'] = $this->config->item ( 'permissions_error' );
				$this->response ( $this->data_result, 401 );
				return;
			}
			// 引入
			$this->config->load ( 'vidol' );
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->library ( 'mongo_db' );
			$this->load->helper ( 'string' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['member_id'] = $this->get ( 'member_id' );
			$data_input ['debug'] = $this->get ( 'debug' );
			// 必填檢查
			if (empty ( $data_input ['member_id'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 產生資料
			$data_input ['token'] = random_string ( 'alnum', 25 );
			$this->mongo_db->where ( array (
					'member_id' => $data_input ['member_id'] 
			) )->set ( '_perishable_token', $data_input ['token'] )->update ( '_User' );
			// 會員資料
			$data_mongo = $this->mongo_db->where ( array (
					'member_id' => $data_input ['member_id'] 
			) )->get ( '_User' );
			$data_mongo = array_shift ( $data_mongo );
			if (empty ( $data_mongo ['_perishable_token'] ) || empty ( $data_mongo ['email'] )) {
				// 資料格式錯誤
				$this->data_result ['message'] = $this->lang->line ( 'database_format_error' );
				$this->data_result ['code'] = $this->config->item ( 'database_format_error' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// 網址
			$email_user_password_uri = $this->config->item ( 'email_user_password_uri' );
			$email_user_password_doman = $this->config->item ( 'email_user_password_doman' );
			$email_user_password_id = $this->config->item ( 'email_user_password_id' );
			$email_url = sprintf ( $email_user_password_uri, $email_user_password_doman, urlencode ( $data_mongo ['_perishable_token'] ), $email_user_password_id, urlencode ( $data_mongo ['email'] ) );
			// 成功
			$this->data_result ['result'] = $email_url;
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_mongo'] = $data_mongo;
				$this->data_result ['debug'] ['email_user_password_uri'] = $email_user_password_uri;
				$this->data_result ['debug'] ['email_user_password_doman'] = $email_user_password_doman;
				$this->data_result ['debug'] ['email_user_password_id'] = $email_user_password_id;
				$this->data_result ['debug'] ['email_url'] = $email_url;
			}
			unset ( $email_url );
			unset ( $email_user_password_id );
			unset ( $email_user_password_doman );
			unset ( $email_user_password_uri );
			unset ( $data_mongo );
			unset ( $data_input );
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
	 * 發送認證信
	 * 權限 Users View
	 * member_id 會員ID
	 */
	public function send_mail_verify_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 權限檢查
			if (! $this->flexi_auth->is_privileged ( 'Users View' )) {
				// 權限錯誤
				$this->data_result ['message'] = $this->lang->line ( 'permissions_error' );
				$this->data_result ['code'] = $this->config->item ( 'permissions_error' );
				$this->response ( $this->data_result, 401 );
				return;
			}
			// 引入
			$this->config->load ( 'vidol' );
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'vidol', 'traditional-chinese' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->library ( 'mongo_db' );
			$this->load->model ( 'vidol_cron/send_mail_model' );
			$this->load->helper ( 'string' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['member_id'] = $this->get ( 'member_id' );
			$data_input ['debug'] = $this->get ( 'debug' );
			// 必填檢查
			if (empty ( $data_input ['member_id'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 取得會員資料
			$data_mongo = $this->mongo_db->where ( array (
					'member_id' => $data_input ['member_id'] 
			) )->get ( '_User' );
			$data_mongo = array_shift ( $data_mongo );
			if (empty ( $data_mongo ) || empty ( $data_mongo ['_email_verify_token'] ) || empty ( $data_mongo ['email'] )) {
				// 資料格式錯誤
				$this->data_result ['message'] = $this->lang->line ( 'database_format_error' );
				$this->data_result ['code'] = $this->config->item ( 'database_format_error' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// 標題
			$email_title = $this->lang->line ( 'email_verify_title' );
			// 信件內容
			$email_content = $this->lang->line ( 'email_verify_content' );
			// 取變數
			$email_user_verify_vidol_uri = $this->config->item ( 'email_user_verify_vidol_uri' );
			$email_user_verify_uri = $this->config->item ( 'email_user_verify_uri' );
			$email_user_verify_doman = $this->config->item ( 'email_user_verify_doman' );
			$email_user_verify_id = $this->config->item ( 'email_user_verify_id' );
			$email_uri = sprintf ( $email_user_verify_uri, $email_user_verify_doman, $email_user_verify_id, $data_mongo ['_email_verify_token'], $data_mongo ['email'], $data_input ['member_id'] );
			$email_url = sprintf ( $email_user_verify_vidol_uri, urlencode ( $email_uri ) );
			// chrome
			// 主要網址不能urlencode會變搜尋,傳遞資料要urlencode否則會被當特殊字完處理
			$content = sprintf ( $email_content, $email_url, $email_data );
			// 紀錄
			$uid = $this->flexi_auth->get_user_id (); // 取得帳號id
			$data_insert = array (
					'sm_source' => 'backend_verify',
					'sm_user_accounts_no' => $uid,
					'sm_from' => $this->config->item ( 'email_from' ),
					'sm_to' => $data_mongo ['email'],
					'sm_reply_to' => '',
					'sm_cc' => '',
					'sm_bcc' => json_encode ( $this->config->item ( 'email_bcc' ) ),
					'sm_title' => $email_title,
					'sm_content' => $content 
			);
			$insert_id = $this->send_mail_model->insert_for_data ( $data_insert );
			// 成功
			$this->data_result ['result'] = $insert_id;
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_mongo'] = $data_mongo;
				$this->data_result ['debug'] ['email_title'] = $email_title;
				$this->data_result ['debug'] ['email_content'] = $email_content;
				$this->data_result ['debug'] ['email_user_verify_vidol_uri'] = $email_user_verify_vidol_uri;
				$this->data_result ['debug'] ['email_user_verify_uri'] = $email_user_verify_uri;
				$this->data_result ['debug'] ['email_user_verify_doman'] = $email_user_verify_doman;
				$this->data_result ['debug'] ['email_user_verify_id'] = $email_user_verify_id;
				$this->data_result ['debug'] ['email_uri'] = $email_uri;
				$this->data_result ['debug'] ['email_url'] = $email_url;
				$this->data_result ['debug'] ['content'] = $content;
				$this->data_result ['debug'] ['data_insert'] = $data_insert;
			}
			unset ( $data_insert );
			unset ( $content );
			unset ( $email_url );
			unset ( $email_uri );
			unset ( $email_user_verify_id );
			unset ( $email_user_verify_doman );
			unset ( $email_user_verify_uri );
			unset ( $email_user_verify_vidol_uri );
			unset ( $email_content );
			unset ( $email_title );
			unset ( $data_mongo );
			unset ( $data_input );
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
	 * 啟動信箱認證
	 */
	public function mail_verify_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 權限檢查
			if (! $this->flexi_auth->is_privileged ( 'Users View' )) {
				// 權限錯誤
				$this->data_result ['message'] = $this->lang->line ( 'permissions_error' );
				$this->data_result ['code'] = $this->config->item ( 'permissions_error' );
				$this->response ( $this->data_result, 401 );
				return;
			}
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->library ( 'mongo_db' );
			$this->load->helper ( 'string' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// input
			$data_input ['member_id'] = $this->get ( 'member_id' );
			$data_input ['debug'] = $this->get ( 'debug' );
			// 必填檢查
			if (empty ( $data_input ['member_id'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			$mongo_update = $this->mongo_db->where ( array (
					'member_id' => $data_input ['member_id'] 
			) )->set ( 'emailVerified', true )->update ( '_User' );
			// 成功
			$this->data_result ['result'] = $mongo_update;
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['mongo_update'] = $mongo_update;
			}
			unset ( $mongo_update );
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function add_user_get() {
		try {
			if ($this->flexi_auth->is_privileged ( 'Users View' )) {
				$this->benchmark->mark ( 'code_start' );
				// 引入
				$this->load->library ( 'mongo_db' );
				$this->load->helper ( 'string' );
				// 變數
				$data_input = array ();
				// input
				$data_input ['member_id'] = $this->get ( 'member_id' );
				if (! empty ( $data_input ['member_id'] )) {
					$this->mongo_db->where ( array (
							'member_id' => $data_input ['member_id'] 
					) )->set ( 'emailVerified', true )->update ( '_User' );
					//
					$this->data_result ['status'] = true;
					$this->response ( $this->data_result, 200 );
				} else {
					$this->response ( NULL, 400 );
				}
			} else {
				$this->response ( NULL, 401 );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
