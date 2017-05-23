<?php
defined('BASEPATH') or exit('No direct script access allowed');

class votes extends CI_Controller
{

	private $data_view;
	private $vote_arr;

	function __construct ()
	{
		parent::__construct();
		// 資料庫
		//$this->load->database();
		// 權限
		$this->auth = new stdClass();
		$this->load->library('flexi_auth');
		if (! $this->flexi_auth->is_logged_in()) {
			redirect('homes/login');
		}
		// 引用
		$this->load->library('session');
		$this->load->library('lib_log');
		$this->load->library('vidol/fun');
		$this->load->helper('formats');
		$this->lang->load('table_vidol', 'traditional-chinese');
		//$this->config->load('vidol');
		// 初始化
		$this->data_view = format_helper_backend_view_data('sdac_content');
		$this->data_view['system']['action'] = 'Votes';
		$this->data_view['right_countent']['tags']['tag_2'] = array(
				'title' => '投票系統',
				'link' => '/backend/votes',
				'class' => 'fa-line-chart'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	/**
	 * 玩很大進校園
	 */
	public function mrplayer ()
	{
		try {
			//if ($this->flexi_auth->is_privileged('Tools View')) {
			// 寫log
			//$this->fun->logs('觀看票數管理');
			// 引入
			$this->config->load ( 'votes' );
			// 變數
			$this->vote_arr = $this->config->item ( 'votes_mrplay_1' );
			//
			$this->load->model ( 'postgre/vidol_production_model' );
			
			
			if(ENVIRONMENT == 'production'){
				$this->data_view['right_countent']['api_domain'] = 'plugin-boards.vidol.tv';
				$this->data_view['right_countent']['cron_domain'] = 'admin-background.vidol.tv';
			}else{
				$this->data_view['right_countent']['api_domain'] = 'cplugin-boards.vidol.tv';
				$this->data_view['right_countent']['cron_domain'] = 'cadmin-background.vidol.tv';
			}
			$this->data_view['right_countent']['api_uri'] = 'api/votes/mrplay.json';
			$this->data_view['right_countent']['cron_uri'] = 'cron/votes/mrplay';
			//
			$query = $this->vidol_production_model->get_mrplay_votes();
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					//sum
					if(isset($this->data_view['right_countent']['view_data'][$row->category_no]['sum'])){
						$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] += $row->tickets;
					}else{
						$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] = $row->tickets;
					}
					//title
					$this->data_view['right_countent']['view_data'][$row->category_no]['title'] = $this->vote_arr[$row->category_no]['title'];
					$this->data_view['right_countent']['view_data'][$row->category_no]['post'] = $this->vote_arr[$row->category_no]['post'];
					//title
					$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['title'] = $this->vote_arr[$row->category_no]['countent'][$row->video_id_no];
					//
					$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['tickets'] = $row->tickets;
					//
					$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['add_ticket'] = $row->tickets - $row->couns;
					//print_r($row);
				}
			}
			// 變數
			$data_post = array();
			// 資料整理
			$this->data_view['right_countent']['view_path'] = 'AdminLTE/votes/vote';
			$this->data_view['right_countent']['tags']['tag_3'] = array(
					'title' => '票數管理',
					'link' => '/backend/votes/mrplay_post',
					'class' => 'fa-qrcode'
			);
			// 套版
			$this->load->view('AdminLTE/include/html5', $this->data_view);
			//}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	//華劇大賞-灌票
	public function awards($category_no){
		try {
			$this->load->model ( 'postgre/vidol_production_model' );
			// 變數
			$data_input = array ();
			if(count($this->vote_arr[$category_no]['countent'])>0){
				foreach ($this->vote_arr[$category_no]['countent'] as $video_id_no=>$val){
					if(isset($_POST['video_id_' . $video_id_no])){
						$data_input['sum'] = $_POST['sum'];
						$data_input['video_id_' . $video_id_no] = $_POST['video_id_' . $video_id_no];
						if(($data_input['video_id_' . $video_id_no] / $data_input['sum'] * 100) <= 5 || true){
							$query = $this->vidol_production_model->update_votes_by_category_video($category_no, $video_id_no, $data_input['video_id_' . $video_id_no]);
						}
					}
				}
			}
			//print_r($data_input);
			redirect('/backend/sdac/vote');
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	//華劇大賞-明星午茶
	public function afternoon ()
	{
		try {
			//if ($this->flexi_auth->is_privileged('Tools View')) {
			// 寫log
			//$this->fun->logs('觀看票數管理');
			 
			$this->load->model ( 'postgre/vidol_production_model' );
			$query = $this->vidol_production_model->get_afternoon();
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					//sum
					if(isset($this->data_view['right_countent']['view_data'][$row->category_no]['sum'])){
						$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] += $row->tickets;
					}else{
						$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] = $row->tickets;
					}
					//title
					$this->data_view['right_countent']['view_data'][$row->category_no]['title'] = $this->afternoon_arr[$row->category_no]['title'];
					$this->data_view['right_countent']['view_data'][$row->category_no]['post'] = $this->afternoon_arr[$row->category_no]['post'];
					//title
					$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['title'] = $this->afternoon_arr[$row->category_no]['countent'][$row->video_id_no];
					//
					$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['tickets'] = $row->tickets;
					//
					$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['add_ticket'] = $row->tickets - $row->couns;
					//print_r($row);
				}
			}
			// 變數
			$data_post = array();
			// 資料整理
			$this->data_view['right_countent']['view_path'] = 'AdminLTE/sdac/afternoon';
			$this->data_view['right_countent']['tags']['tag_3'] = array(
					'title' => '票數管理',
					'link' => '/backend/sdac/afternoon',
					'class' => 'fa-qrcode'
			);
			// 套版
			$this->load->view('AdminLTE/include/html5', $this->data_view);
			//}
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	//華劇大賞-明星午茶-灌票
	public function afternoon_awards($category_no){
		try {
			$this->load->model ( 'postgre/vidol_production_model' );
			// 變數
			$data_input = array ();
			if(count($this->afternoon_arr[$category_no]['countent'])>0){
				foreach ($this->afternoon_arr[$category_no]['countent'] as $video_id_no=>$val){
					if(isset($_POST['video_id_' . $video_id_no])){
						$data_input['sum'] = $_POST['sum'];
						$data_input['video_id_' . $video_id_no] = $_POST['video_id_' . $video_id_no];
						if(($data_input['video_id_' . $video_id_no] / $data_input['sum'] * 100) <= 5 || true){
							$query = $this->vidol_production_model->update_afternoon_by_category_video($category_no, $video_id_no, $data_input['video_id_' . $video_id_no]);
						}
					}
				}
			}
			//print_r($data_input);
			redirect('/backend/sdac/afternoon');
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}
}
