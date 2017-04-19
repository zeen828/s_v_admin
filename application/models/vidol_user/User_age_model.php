<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

	class User_age_model extends CI_Model
	{
		private $table_name = 'User_age_tbl';
		private $fields_pk = 'ua_pk';

		public function __construct ()
		{
			parent::__construct();
			// $this->load->config('set/databases_fiels', TRUE);
			$this->r_db = $this->load->database('vidol_user_read', TRUE);
			$this->w_db = $this->load->database('vidol_user_write', TRUE);
		}

		public function __destruct() {
			$this->r_db->close();
			unset($this->r_db);
			$this->w_db->close();
			unset($this->w_db);
			//parent::__destruct();
		}

		public function insert_User_age_for_data($data){
			$this->w_db->insert($this->table_name, $data);
			$id = $this->w_db->insert_id();
			//echo $this->w_db->last_query();
			return $id;
		}

		public function update_User_age_for_data($pk, $data){
			$this->w_db->where($this->fields_pk, $pk);
			$this->w_db->update($this->table_name, $data);
			$result = $this->w_db->affected_rows();
			//echo $this->w_db->last_query();
			return $result;
		}

		public function get_row_User_age_by_pk ($select, $pk)
		{
			if(!empty($select)){
				$this->r_db->select($select);
			}
			$this->r_db->where($this->fields_pk, $pk);
			$query = $this->r_db->get($this->table_name);
			//echo $this->r_db->last_query();
			if ($query->num_rows() > 0){
				return $query->row();
			}
			return false;
		}
		
		public function get_rows_User_age ($select)
		{
			if(!empty($select)){
				$this->r_db->select($select);
			}
			$query = $this->r_db->get($this->table_name);
			//echo $this->r_db->last_query();
			return $query;
		}
		
		/**
		 * 年紀查詢
		 * @param unknown $select
		 * @param unknown $age
		 * @return unknown|boolean
		 */
		public function get_row_User_age_by_age ($select, $age)
		{
			if(!empty($select)){
				$this->r_db->select($select);
			}
			$this->r_db->where('ua_age', $age);
			$query = $this->r_db->get($this->table_name);
			// echo $this->r_db->last_query();
			if ($query->num_rows() > 0){
				return $query->row();
			}
			return false;
		}
		
		/**
		 * 不重複新增
		 * @param unknown $select
		 * @param unknown $data
		 * @return unknown
		 */
		public function not_repeating_User_age ($select, $data)
		{
			$arr = array();
			$age = $this->get_row_User_age_by_age($this->fields_pk, $data['ua_age']);
			if(empty($age)){
				//新增
				$insert = $this->insert_User_age_for_data($data);
				return $insert;
			}else{
				//更新
				$update = $this->update_User_age_for_data($age->ua_pk, $data);
				return $update;
			}
		}
	}
