<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Boards_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->r_db = $this->load->database ( 'vidol_websocket_read', TRUE );
		$this->w_db = $this->load->database ( 'vidol_websocket_write', TRUE );
	}
	public function __destruct() {
		$this->r_db->close ();
		unset ( $this->r_db );
		$this->w_db->close ();
		unset ( $this->w_db );
		// parent::__destruct();
	}
	public function update_live_board($video_type, $video_no, $update_video_type, $update_video_no) {
		$this->w_db->where ( 'b_type', $video_type );
		$this->w_db->where ( 'b_type_no', $video_no );
		$this->w_db->set ( 'b_type', $update_video_type );
		$this->w_db->set ( 'b_type_no', $update_video_no );
		$this->w_db->update ( 'Board_tbl' );
		$result = $this->w_db->affected_rows ();
		// echo $this->r_db->last_query();
		return $result;
	}
	public function update_live_barrage($video_type, $video_no, $update_video_type, $update_video_no) {
		$this->w_db->where ( 'b_type', $video_type );
		$this->w_db->where ( 'b_type_no', $video_no );
		$this->w_db->set ( 'b_type', $update_video_type );
		$this->w_db->set ( 'b_type_no', $update_video_no );
		$this->w_db->update ( 'Barrage_tbl' );
		$result = $this->w_db->affected_rows ();
		// echo $this->r_db->last_query();
		return $result;
	}
	/**
	 * 依節目查詢資料
	 * 
	 * @param unknown $select        	
	 * @param unknown $type        	
	 * @param unknown $typeno        	
	 * @param number $limit_start        	
	 * @param number $limit        	
	 * @return unknown
	 */
	public function get_Board_by_type_typeno($select, $type, $typeno, $limit_start = 0, $limit = 100) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		if (! empty ( $limit_start )) {
			$this->r_db->where ( 'b_no >', $limit_start );
		}
		$this->r_db->where ( 'b_type', $type );
		$this->r_db->where ( 'b_type_no', $typeno );
		$this->r_db->where ( 'b_status', '1' );
		$this->r_db->order_by ( 'b_no', 'ASC' );
		$this->r_db->limit ( $limit );
		$query = $this->r_db->get ( 'Board_tbl' );
		// echo $this->r_db->last_query ();
		return $query;
	}
	/**
	 * 取得亂數留言資料
	 * @param unknown $select			查詢解果
	 * @param number $count				查詢筆數
	 * @param unknown $where_string		查詢額外條件最後
	 * @param unknown $type				留言類型
	 * @param unknown $typeno			流言號碼
	 * @return unknown
	 */
	public function get_rand_Board_by_type_typeno($select, $count = 1, $where_string, $type, $typeno) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		$this->r_db->where ( 'b_type', $type );
		$this->r_db->where ( 'b_type_no', $typeno );
		$this->r_db->where ( 'b_status', '1' );
		if (! empty ( $where_string )) {
			$this->r_db->where ( $where_string );
		}
		$this->r_db->order_by($count, 'RANDOM');
		$query = $this->r_db->get ( 'Board_tbl' );
		echo $this->r_db->last_query ();
		return $query;
	}
}
