<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Promo_packages_model extends CI_Model
{
	
    public function __construct ()
    {
        parent::__construct();
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
    
    // 
    public function truncate_insert_array_promo_packages ($data_array)
    {
    	//清空
    	$this->w_db->truncate('Promo_packages_tbl');
    	//新增
    	$this->w_db->set('l_date', $date);
    	$this->w_db->set('l_year', $year);
    	$this->w_db->set('l_month', $month);
    	$this->w_db->set('l_day', $day);
    	$this->w_db->set('l_time', $time);
    	$this->w_db->set('l_count_utc', $count_utc);
    	$this->w_db->set('l_count_tw', $count_tw);
    	$this->w_db->set('l_count_repeat_utc', $count_repeat_utc);
    	$this->w_db->set('l_count_repeat_tw', $count_repeat_tw);
    	$this->w_db->insert('Login_day_tbl');
    	$id = $this->w_db->insert_id();
    	return $id;
    }
}
