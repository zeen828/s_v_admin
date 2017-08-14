<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Vote_config extends CI_Controller {
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
		$this->data_view = format_helper_backend_view_data ( 'vote_config' );
		$this->data_view ['system'] ['action'] = 'Votes';
		$this->data_view ['right_countent'] ['tags'] ['tag_2'] = array (
				'title' => '投票系統',
				'link' => '/backend/vote_config',
				'class' => 'fa-line-chart'
		);
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}

	public function config() {
		try {

		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
