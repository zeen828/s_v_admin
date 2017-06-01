<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class votes extends CI_Controller {
	private $data_view;
	private $vote_arr;
	function __construct() {
		parent::__construct ();
		// 資料庫
		// $this->load->database();
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
		// $this->config->load('vidol');
		// 初始化
		$this->data_view = format_helper_backend_view_data ( 'sdac_content' );
		$this->data_view ['system'] ['action'] = 'Votes';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '玩很大進校園',
				'link' => '/backend/votes',
				'class' => 'fa-line-chart' 
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	
	/**
	 * 玩很大進校園
	 */
	public function mrplay() {
		try {
			// if ($this->flexi_auth->is_privileged('Tools View')) {
			// 寫log
			// $this->fun->logs('觀看票數管理');
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			// 變數
			$this->vote_arr = $this->config->item ( 'votes_mrplay_1' );
			//
			if (ENVIRONMENT == 'production') {
				$this->data_view ['right_countent'] ['api_domain'] = 'plugin-boards.vidol.tv';
				$this->data_view ['right_countent'] ['cron_domain'] = 'admin-background.vidol.tv';
			} else {
				$this->data_view ['right_countent'] ['api_domain'] = 'cplugin-boards.vidol.tv';
				$this->data_view ['right_countent'] ['cron_domain'] = 'cadmin-background.vidol.tv';
			}
			$this->data_view ['right_countent'] ['api_uri'] = 'api/votes/mrplay.json';
			$this->data_view ['right_countent'] ['cron_uri'] = 'cron/votes/mrplay';
			//
			$query = $this->vidol_production_model->get_mrplay_votes ();
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					// sum
					if (isset ( $this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['sum'] )) {
						$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['sum'] += $row->tickets;
					} else {
						$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['sum'] = $row->tickets;
					}
					// title
					$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['title'] = $this->vote_arr [$row->category_no] ['title'];
					$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['post'] = $this->vote_arr [$row->category_no] ['post'];
					// title
					$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['countent'] [$row->video_id_no] ['title'] = $this->vote_arr [$row->category_no] ['countent'] [$row->video_id_no];
					//
					$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['countent'] [$row->video_id_no] ['tickets'] = $row->tickets;
					//
					$this->data_view ['right_countent'] ['view_data'] [$row->category_no] ['countent'] [$row->video_id_no] ['add_ticket'] = $row->tickets - $row->couns;
					// print_r($row);
				}
			}
			// 變數
			$data_post = array ();
			// 資料整理
			$this->data_view ['right_countent'] ['view_path'] = 'AdminLTE/votes/vote';
			$this->data_view ['right_countent'] ['tags'] ['tag_3'] = array (
					'title' => '投票系統',
					'link' => '/backend/votes/mrplay',
					'class' => 'fa-qrcode' 
			);
			// 套版
			$this->load->view ( 'AdminLTE/include/html5', $this->data_view );
			// }
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// 華劇大賞-明星午茶-灌票
	public function mrplay_post($category_no) {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			// 變數
			$this->vote_arr = $this->config->item ( 'votes_mrplay_1' );
			$data_input = array ();
			// 接收變數
			$data_input ['sum'] = $this->input->post ( 'sum' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			if (count ( $this->vote_arr [$category_no] ['countent'] ) > 0) {
				foreach ( $this->vote_arr [$category_no] ['countent'] as $video_id_no => $val ) {
					if (isset ( $_POST ['video_id_' . $video_id_no] )) {
						// $data_input ['video_id_' . $video_id_no] = $_POST ['video_id_' . $video_id_no];
						$data_input ['video_id_' . $video_id_no] = $this->input->post ( 'video_id_' . $video_id_no );
						if (($data_input ['video_id_' . $video_id_no] / $data_input ['sum'] * 100) <= 5 || true) {
							$query = $this->vidol_production_model->update_mrplay_by_school_code_no ( $video_id_no, $data_input ['video_id_' . $video_id_no] );
						}
					}
				}
			}
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			} else {
				redirect ( '/backend/votes/mrplay' );
				exit ();
			}
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function mrplay_list() {
		try {
			if ($this->flexi_auth->is_privileged('Votes View')) {
				// 寫log
				$this->fun->logs('觀看投票報表');
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_old_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('vote_mrplay_list_tbl');
				// 標題
				$crud->set_subject($this->lang->line('tabels_user_accounts'));
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_vote_registered','v_registered','v_total_registered','v_proportion','v_created_at','v_updated_at');
				// 資料庫欄位文字替換
				$crud->display_as('v_pk', $this->lang->line('fields_pk'));
				$crud->display_as('v_date', '時間(TW)');
				$crud->display_as('v_new_vote', '新投票會員');
				$crud->display_as('v_vote', '投票數');
				$crud->display_as('v_single_vote', '不重複投票數');
				$crud->display_as('v_total_vote', '累計投票數');
				$crud->display_as('v_vote_registered', '投票註冊數');
				$crud->display_as('v_registered', 'vidol註冊數');
				$crud->display_as('v_total_registered', '累計投票註冊數');
				$crud->display_as('v_proportion', '註冊占比');
				$crud->display_as('v_created_at', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('v_updated_at', $this->lang->line('fields_time_update_utc'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '玩很大進校園報表',
						'link' => '/backend/votes/mrplay_list',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function mrplay_gifts_list() {
		try {
			if ($this->flexi_auth->is_privileged('Votes View')) {
				// 寫log
				$this->fun->logs('觀看投票報表');
				// 變數
				$data_post = array();
				// 強制切換資料庫
				unset($this->db);
				$this->db = $this->load->database('vidol_old_write', true);
				// grocery_CRUD 自產表單
				$this->load->library('grocery_CRUD'); // CI整合表單http://www.grocerycrud.com/
				$crud = new grocery_CRUD();
				// 語系
				$crud->set_language('taiwan');
				// 版型
				$crud->set_theme('flexigrid');
				// 表格
				$crud->set_table('vote_mrplay_gifts_list_tbl');
				// 標題
				$crud->set_subject($this->lang->line('tabels_user_accounts'));
				// 移除新增
				$crud->unset_add();
				// 移除編輯
				$crud->unset_edit();
				// 移除刪除
				$crud->unset_delete();
				// 清單顯示欄位
				$crud->columns('v_pk','v_date','v_new_vote','v_vote','v_single_vote','v_total_vote','v_vote_registered','v_registered','v_total_registered','v_proportion','v_created_at','v_updated_at');
				// 資料庫欄位文字替換
				$crud->display_as('v_pk', $this->lang->line('fields_pk'));
				$crud->display_as('v_date', '時間(TW)');
				$crud->display_as('v_new_vote', '新投票會員');
				$crud->display_as('v_vote', '投票數');
				$crud->display_as('v_single_vote', '不重複投票數');
				$crud->display_as('v_total_vote', '累計投票數');
				$crud->display_as('v_vote_registered', '投票註冊數');
				$crud->display_as('v_registered', 'vidol註冊數');
				$crud->display_as('v_total_registered', '累計投票註冊數');
				$crud->display_as('v_proportion', '註冊占比');
				$crud->display_as('v_created_at', $this->lang->line('fields_time_creat_utc'));
				$crud->display_as('v_updated_at', $this->lang->line('fields_time_update_utc'));
				// 產生表單
				$output = $crud->render();
				// 資料整理
				$this->data_view['right_countent']['view_path'] = 'AdminLTE/include/content_grocery_crud';
				$this->data_view['right_countent']['view_data'] = $output;
				$this->data_view['right_countent']['tags']['tag_3'] = array(
						'title' => '玩很大玩粉感恩大放送',
						'link' => '/backend/votes/mrplay_gifts_list',
						'class' => 'fa-bar-chart'
				);
				// 套版
				$this->load->view('AdminLTE/include/html5', $this->data_view);
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
