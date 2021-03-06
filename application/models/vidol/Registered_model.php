<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Registered_model extends CI_Model {
	private $table_name = 'Registered_tbl';
	private $fields_pk = '';
	private $fields_status = '';
	public function __construct() {
		parent::__construct ();
		$this->r_db = $this->load->database ( 'read', TRUE );
		$this->w_db = $this->load->database ( 'write', TRUE );
	}
	public function __destruct() {
		$this->r_db->close ();
		unset ( $this->r_db );
		$this->w_db->close ();
		unset ( $this->w_db );
		// parent::__destruct();
	}
	public function get_row_registered_count_sum_by_date_utc($yesterday, $now) {
		$this->r_db->select_sum('r_count');
		$this->r_db->where ( 'r_date_utc >=', $yesterday );
		$this->r_db->where ( 'r_date_utc <', $now );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
 		if ($query->num_rows () > 0) {
 			return $query->row ();
 		}
		return false;
	}
	
	public function get_registered_sum_by_day($date) {
		$time = strtotime($date);
		$r_year_tw = date("Y", $time);
		$r_month_tw = date("m", $time);
		$r_day_tw = date("d", $time);
		$this->r_db->select_sum('r_count');
		$this->r_db->where ( 'r_year_tw', $r_year_tw );
		$this->r_db->where ( 'r_month_tw', $r_month_tw );
		$this->r_db->where ( 'r_day_tw', $r_day_tw );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
		if ($query->num_rows () > 0) {
			return $query->row ();
		}
		return false;
	}
}
