<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Events extends CI_Controller {
	private $data_view;
	private $IP;
	function __construct() {
		parent::__construct ();
		$this->IP = $this->input->ip_address ();
		// 效能檢查
		$this->output->enable_profiler(TRUE);
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
			if ($this->IP != '61.216.83.7') {
				show_404 ();
				exit ();
			}
			print_r($this->IP);
				
			
			
			// 套版
			$this->load->view ( 'Events/lottery/lottery', $this->data_view );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
