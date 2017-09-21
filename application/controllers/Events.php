<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Report_event extends CI_Controller {
	private $data_view;
	private $vote_arr;
	function __construct() {
		parent::__construct ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	
	public function index() {
		// show_404();
		$this->vote ( 1 );
	}
	
	/**
	 * 投票名單抽獎
	 */
	public function lottery($config_id) {
		try {
			
			// 套版
			$this->load->view ( 'Events/lotters/lotters', $this->data_view );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
