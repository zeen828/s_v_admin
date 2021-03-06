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
	 * 取得玩很大進校園投票查詢筆數
	 * @param unknown $category_no
	 * @param unknown $code
	 * @return unknown
	 */
	public function get_count_vote_mrplay($category_no, $code_no) {
		$this->w_db->where ( 'category_no', $category_no );
		$this->w_db->where ( 'code_no', $code_no );
		$this->w_db->from ( 'vote_mrplay_tbl' );
		$count = $this->w_db->count_all_results ();
		// echo $this->w_db->last_query ();
		return $count;
	}
	/**
	 * 新增玩很大進校園投票資料
	 * @param unknown $category_no
	 * @param unknown $code
	 * @param unknown $title
	 * @param unknown $ticket
	 * @param unknown $ticket_add
	 * @return unknown
	 */
	public function insert_vote_mrplay($category_no, $code_no, $code, $title, $ticket, $ticket_add) {
		$this->w_db->set ( 'category_no', $category_no );
		$this->w_db->set ( 'code_no', $code_no );
		$this->w_db->set ( 'code', $code );
		$this->w_db->set ( 'title', $title );
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->insert ( 'vote_mrplay_tbl' );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	/**
	 * 更新玩很大進校園投票投票資料
	 * @param unknown $category_no
	 * @param unknown $code
	 * @param unknown $ticket
	 * @param unknown $ticket_add
	 * @return unknown
	 */
	public function update_vote_mrplay($category_no, $code_no, $ticket, $ticket_add) {
		$this->w_db->set ( 'ticket', $ticket );
		$this->w_db->set ( 'ticket_add', $ticket_add );
		$this->w_db->where ( 'category_no', $category_no );
		$this->w_db->where ( 'code_no', $code_no );
		$this->w_db->update ( 'vote_mrplay_tbl' );
		$id = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $id;
	}
	
	/**
	 * 紀錄玩很大進校園報表
	 * @param unknown $category_no
	 * @param unknown $code_no
	 * @param unknown $code
	 * @param unknown $title
	 * @param unknown $ticket
	 * @param unknown $ticket_add
	 * @return unknown
	 */
	public function insert_vote_mrplay_list($data) {
		$this->w_db->insert ( 'vote_mrplay_list_tbl', $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	
	/**
	 * 玩粉感恩大放送報表
	 * @param unknown $category_no
	 * @param unknown $code_no
	 * @param unknown $code
	 * @param unknown $title
	 * @param unknown $ticket
	 * @param unknown $ticket_add
	 * @return unknown
	 */
	public function insert_vote_mrplay_gifts_list($data) {
		$this->w_db->insert ( 'vote_mrplay_gifts_list_tbl', $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	
	/**
	 * 愛上哥們贈東京票報表
	 * @param unknown $category_no
	 * @param unknown $code_no
	 * @param unknown $code
	 * @param unknown $title
	 * @param unknown $ticket
	 * @param unknown $ticket_add
	 * @return unknown
	 */
	public function insert_vote_bromance_meetings_list($data) {
		$this->w_db->insert ( 'vote_bromance_meetings_list_tbl', $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
//不重複新增共用
	/**
	 * 查詢投票表格
	 * @param unknown $tables
	 * @param unknown $where_fields
	 * @param unknown $where_val
	 * @return unknown
	 */
	public function get_count_vote_tables_list($tables, $where_fields, $where_val) {
		$this->w_db->where ( $where_fields, $where_val );
		$this->w_db->from ( $tables );
		$count = $this->w_db->count_all_results ();
		// echo $this->w_db->last_query ();
		return $count;
	}

	/**
	 * 新增資料到投票表格
	 * @param unknown $tables	資料表
	 * @param unknown $data		新增資料
	 * @return unknown
	 */
	public function insert_vote_tables_list($tables, $data) {
		$this->w_db->insert ( $tables, $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}

	/**
	 * 更新資料到投票表格
	 * @param unknown $tables		資料表
	 * @param unknown $where_fields	查詢欄位
	 * @param unknown $where_val	查詢值
	 * @param unknown $data			更新資料
	 * @return unknown
	 */
	public function update_vote_tables_list($tables, $where_fields, $where_val, $data) {
		$this->w_db->where ( $where_fields, $where_val );
		$this->w_db->update ( $tables, $data );
		$id = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $id;
	}
	
}
