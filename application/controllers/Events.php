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
			// 限制公司IP || 活動編號
			if ($this->IP != '61.216.83.7' || empty ( $config_id )) {
				show_404 ();
				exit ();
			}
			// 引用
			$this->load->model ( 'vidol_event/event_vote_config_model' );
			$this->load->model ( 'vidol_event/event_vote_select_model' );
			// 設定檔
			$data_config = $this->event_vote_config_model->get_row_by_pk ( '*', $config_id );
			//print_r ( $data_config );
			$this->data_view['config_id'] = $data_config->id;
			$this->data_view['title'] = $data_config->title;
			$this->data_view['count'] = 60;
			$this->data_view['start_at'] = $data_config->start_at;
			$this->data_view['end_at'] = $data_config->end_at;
			// 參加者
			$query = $this->event_vote_select_model->get_user_by_condifid_date ( 'member_id', $config_id , null, null);
			//print_r($query);
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					$this->data_view['user_json'][] = $row->member_id;
					unset($row);
				}
			}
			$this->data_view['user_json'] = json_encode($this->data_view['user_json']);
			// 套版
			$this->load->view ( 'Events/lottery/lottery', $this->data_view );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
