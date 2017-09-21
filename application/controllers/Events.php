<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Events extends CI_Controller {
	private $data_view;
	private $IP;
	function __construct() {
		parent::__construct ();
		$this->IP = $this->input->ip_address ();
		// 效能檢查
		// $this->output->enable_profiler ( TRUE );
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
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 限制公司IP
			if ($this->IP != '61.216.83.7') {
				show_404 ();
				exit ();
			}
			$this->load->model ( 'vidol_event/event_vote_config_model' );
			$this->load->model ( 'vidol_event/event_vote_select_model' );
			$data_config = $this->event_vote_config_model->get_row_by_pk ( '*', $config_id );
			print_r ( $data_config );
			// 套版
			$this->load->view ( 'Events/lottery/lottery', $this->data_view );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
