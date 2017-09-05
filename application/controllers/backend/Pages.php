<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Pages extends CI_Controller {
	private $data_view;
	function __construct() {
		parent::__construct ();
		// 資料庫
		$this->load->database ();
		// 權限
		$this->auth = new stdClass ();
		$this->load->library ( 'flexi_auth' );
		if (! $this->flexi_auth->is_logged_in ()) {
			redirect ( 'homes/login' );
		}
		// 引用
		$this->load->library ( 'session' );
		$this->load->library ( 'lib_log' );
		$this->load->library ( 'vidol/fun' );
		$this->load->helper ( 'formats' );
		$this->lang->load ( 'table_vidol', 'traditional-chinese' );
		// 初始化
		$this->data_view = format_helper_backend_view_data ( 'orders_content' );
		$this->data_view ['system'] ['action'] = 'Pages';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '活動頁面管理',
				'link' => '/backend/pages',
				'class' => 'fa-money' 
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function index() {
		show_404 ();
	}
	public function load_page() {
		// $this->output->enable_profiler(TRUE);
		try {
			if ($this->flexi_auth->is_privileged ( 'Orders View' )) {
				// 寫log
				$this->fun->logs ( '觀看中繼頁' );
				// 變數
				$data_post = array ();
				// 資料整理
				$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/pages/load_page';
				// $this->data_view['right_countent']['view_data'] = $output;
				$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
						'title' => '中繼頁',
						'link' => '/backend/pages/load_page',
						'class' => 'fa-building-o' 
				);
				// 套版
				$this->load->view ( 'AdminLTE/include/html5', $this->data_view );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function load_page_channel() {
		// $this->output->enable_profiler(TRUE);
		try {
			if ($this->flexi_auth->is_privileged ( 'Orders View' )) {
				// 寫log
				$this->fun->logs ( '觀看中繼頁' );
				// 引入
				$this->load->model ( 'vidol_event/page_load_model' );
				// 變數
				$data_input = array ();
				$data_tmp = array ();
				// 接收變數
				$data_input ['channel_pk'] = $this->input->post ( 'channel_pk' );
				$data_input ['channel_type'] = $this->input->post ( 'channel_type' );
				$data_input ['channel_id'] = $this->input->post ( 'channel_id' );
				// CALL API
				$api_url = 'http://api-background.vidol.tv/v1/channels/';
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $api_url );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
						'Content-Type: application/json',
						'Accept: application/json',
						'Authorization: Basic MWIyNjZkNjI0OWJiYjljM2M2ZDdkYjM0YWU1YzU5YzZhMzYyZmQxODgxOGJkMzM2NmNiYjY5YTUzOGYwZmU2NDpjODNlMDkxMmQ5MWI1NjAzM2RlNmFmODdjZDIxZGZkODk1NTBkNzA4M2Q3ODM0ZDIyMWVmNmNkZGM5ODg4ZjM2',
						'locale: zh-tw' 
				) );
				$output = curl_exec ( $ch );
				curl_close ( $ch );
				unset ( $ch );
				unset ( $api_url );
				$output = json_decode ( $output );
				// 判斷取得資料數當作取得資料正確判斷
				if (count ( $output ) == 2) {
					foreach ( $output as $channel ) {
						$data_tmp [$channel->id] = $channel;
						unset ( $channel );
					}
					// 改資料
					for($i = 0; $i < count ( $data_input ['channel_pk'] ); $i ++) {
						$video_id = $data_input ['channel_id'] [$i];
						if (! empty ( $video_id ) && isset ( $data_tmp [$video_id] )) {
							$data_update = array (
									'video_id' => $video_id,
									'title' => $data_tmp [$video_id]->title,
									'des' => $data_tmp [$video_id]->title,
									'image' => $data_tmp [$video_id]->url,
									'url' => sprintf ( 'http://vidol.tv/channel/%d', $video_id ) 
							);
							$this->page_load_model->update_data ( $data_input ['channel_pk'] [$i], $data_update );
							unset ( $data_update );
						}
						unset ( $video_id );
					}
				}
				unset ( $output );
				unset ( $data_tmp );
				unset ( $data_input );
				redirect ( '/backend/pages/load_page' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function load_page_video() {
		// $this->output->enable_profiler(TRUE);
		try {
			if ($this->flexi_auth->is_privileged ( 'Orders View' )) {
				// 寫log
				$this->fun->logs ( '觀看中繼頁' );
				// 變數
				$data_post = array ();
				
				print_r ( $_POST );
				// 資料整理
				$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/pages/load_page';
				// $this->data_view['right_countent']['view_data'] = $output;
				$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
						'title' => '中繼頁',
						'link' => '/backend/pages/load_page',
						'class' => 'fa-building-o' 
				);
				// 套版
				$this->load->view ( 'AdminLTE/include/html5', $this->data_view );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function load_page_event() {
		// $this->output->enable_profiler(TRUE);
		try {
			if ($this->flexi_auth->is_privileged ( 'Orders View' )) {
				// 寫log
				$this->fun->logs ( '觀看中繼頁' );
				// 引入
				$this->load->model ( 'vidol_event/page_load_model' );
				// 變數
				$data_input = array ();
				// 接收變數
				$data_input ['event_pk'] = $this->input->post ( 'event_pk' );
				$data_input ['event_title'] = $this->input->post ( 'event_title' );
				$data_input ['event_des'] = $this->input->post ( 'event_des' );
				$data_input ['event_img'] = $this->input->post ( 'event_img' );
				$data_input ['event_url'] = $this->input->post ( 'event_url' );
				// 改資料
				for($i = 0; $i < count ( $data_input ['event_pk'] ); $i ++) {
					$data_update = array (
							'title' => $data_input ['event_title'] [$i],
							'des' => $data_input ['event_des'] [$i],
							'image' => $data_input ['event_img'] [$i],
							'url' => $data_input ['event_url'] [$i] 
					);
					$this->page_load_model->update_data ( $data_input ['event_pk'] [$i], $data_update );
					unset ( $data_update );
				}
				unset ( $data_input );
				redirect ( '/backend/pages/load_page' );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
