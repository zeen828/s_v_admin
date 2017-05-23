<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Vote_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->r_db = $this->load->database ( 'vidol_old_read', TRUE );
		$this->w_db = $this->load->database ( 'vidol_old_write', TRUE );
	}
	public function __destruct() {
		$this->r_db->close ();
		unset ( $this->r_db );
		$this->w_db->close ();
		unset ( $this->w_db );
		// parent::__destruct();
	}
	
	// 華劇大賞
	public function insert_vote($category_no, $category_title, $video_id_no, $video_title, $ticket, $ticket_add) {
		$this->w_db->set ( 'category_no', $category_no );
		$this->w_db->set ( 'category_title', $category_title );
		$this->w_db->set ( 'video_id_no', $video_id_no );
		$this->w_db->set ( 'video_title', $video_title );
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->insert ( 'vote_tbl' );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	
	// 華劇大賞
	public function update_vote($category_no, $video_id_no, $ticket, $ticket_add) {
		$this->w_db->where ( 'category_no', $category_no );
		$this->w_db->where ( 'video_id_no', $video_id_no );
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->update ( 'vote_tbl' );
		$result = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $result;
	}
	
	// 明星午茶
	public function insert_afternoon($category_no, $category_title, $video_id_no, $video_title, $ticket, $ticket_add) {
		$this->w_db->set ( 'category_no', $category_no );
		$this->w_db->set ( 'category_title', $category_title );
		$this->w_db->set ( 'video_id_no', $video_id_no );
		$this->w_db->set ( 'video_title', $video_title );
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->insert ( 'afternoon_tbl' );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	
	// 明星午茶
	public function update_afternoon($category_no, $video_id_no, $ticket, $ticket_add) {
		$this->w_db->where ( 'category_no', $category_no );
		$this->w_db->where ( 'video_id_no', $video_id_no );
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->update ( 'afternoon_tbl' );
		$result = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $result;
	}
	/**
	 * 清除資料
	 */
	public function update_afternoon($category_no, $video_id_no, $ticket, $ticket_add) {
		$this->w_db->where ( 'category_no', $category_no );
		$this->w_db->where ( 'video_id_no', $video_id_no );
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->update ( 'afternoon_tbl' );
		$result = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $result;
	}
}
