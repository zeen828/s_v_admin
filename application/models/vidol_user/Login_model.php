<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model
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
    
    // 新增登入不重複數(每日)
    public function insert_login_day ($date, $count_utc, $count_tw, $count_repeat_utc, $count_repeat_tw)
    {
    	$time = strtotime($date . ' +0');
    	$year = date('Y', $time);
    	$month = date('m', $time);
    	$day = date('d', $time);
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
	
    // 確認是否已有資料(每日)
    public function check_date_day ($date)
    {
    	$this->r_db->where('l_date', $date);
        $count = $this->r_db->count_all_results('Login_day_tbl');
        return $count;
    }
    
    // 取得不重登入數(每日)
    public function get_count_day ($satrt, $end)
    {
    	$start_date = array();
    	$start_date['date_tw'] = date('Y-m-d 00:00:00 +0', strtotime($satrt));
    	$start_date['time_utc'] = strtotime($start_date['date_tw']);
    	$end_date = array();
    	$end_date['date_tw'] = date('Y-m-d 23:59:59 +0', strtotime($end));
    	$end_date['time_utc'] = strtotime($end_date['date_tw']);
    	$this->r_db->select('l_date as date,l_year as year, l_month as month, l_day as day, l_count_utc as count_utc, l_count_tw as count_tw, l_count_repeat_utc as count_repeat_utc, l_count_repeat_tw as count_repeat_tw');
    	$this->r_db->where(sprintf('`l_time` BETWEEN %d AND %d', $start_date['time_utc'], $end_date['time_utc']));
    	$this->r_db->group_by('l_year, l_month, l_day');
    	$this->r_db->order_by('l_time', 'ASC');
    	$query = $this->r_db->get('Login_day_tbl');
    	// echo $this->r_db->last_query();
    	return $query->result();
    }
    
    // 新增登入不重複數(每月)
    public function insert_login_month ($date, $count_utc, $count_tw, $count_repeat_utc, $count_repeat_tw)
    {
    	$time = strtotime($date . ' +0');
    	$year = date('Y', $time);
    	$month = date('m', $time);
    	$day = date('d', $time);
    	$this->w_db->set('l_date', $date);
    	$this->w_db->set('l_year', $year);
    	$this->w_db->set('l_month', $month);
    	$this->w_db->set('l_day', $day);
    	$this->w_db->set('l_time', $time);
    	$this->w_db->set('l_count_utc', $count_utc);
    	$this->w_db->set('l_count_tw', $count_tw);
    	$this->w_db->set('l_count_repeat_utc', $count_repeat_utc);
    	$this->w_db->set('l_count_repeat_tw', $count_repeat_tw);
    	$this->w_db->insert('Login_month_tbl');
    	$id = $this->w_db->insert_id();
    	return $id;
    }
    
    public function update_login_month_by_date($date, $count_utc, $count_tw, $count_repeat_utc, $count_repeat_tw)
    {
    	$time = strtotime($date . ' +0');
    	$year = date('Y', $time);
    	$month = date('m', $time);
    	$day = date('d', $time);
    	$this->w_db->where ( 'l_date', $date );
    	$this->w_db->set('l_year', $year);
    	$this->w_db->set('l_month', $month);
    	$this->w_db->set('l_day', $day);
    	$this->w_db->set('l_time', $time);
    	$this->w_db->set('l_count_utc', $count_utc);
    	$this->w_db->set('l_count_tw', $count_tw);
    	$this->w_db->set('l_count_repeat_utc', $count_repeat_utc);
    	$this->w_db->set('l_count_repeat_tw', $count_repeat_tw);
    	$this->w_db->update ( 'Login_month_tbl' );
    	$result = $this->w_db->affected_rows ();
    	// echo $this->w_db->last_query();
    	return $result;
    }
    
    // 確認是否已有資料(每月)
    public function check_date_month ($date)
    {
    	$this->r_db->where('l_date', $date);
    	$count = $this->r_db->count_all_results('Login_month_tbl');
    	return $count;
    }
    
    // 取得不重登入數(每月)
    public function get_count_month ($satrt, $end)
    {
    	$start_date = array();
    	$start_date['date_tw'] = date('Y-m-01 00:00:00 +0', strtotime($satrt));
    	$start_date['time_utc'] = strtotime($start_date['date_tw']);
    	$end_date = array();
    	$end_date['date_tw'] = date('Y-m-01 23:59:59 +0', strtotime($end));
    	$end_date['time_utc'] = strtotime($end_date['date_tw']);
    	$this->r_db->select('l_date as date,l_year as year, l_month as month, l_day as day, l_count_utc as count_utc, l_count_tw as count_tw, l_count_repeat_utc as count_repeat_utc, l_count_repeat_tw as count_repeat_tw');
    	$this->r_db->where(sprintf('`l_time` BETWEEN %d AND %d', $start_date['time_utc'], $end_date['time_utc']));
    	$this->r_db->group_by('l_year, l_month, l_day');
    	$this->r_db->order_by('l_time', 'ASC');
    	$query = $this->r_db->get('Login_month_tbl');
    	// echo $this->r_db->last_query();
    	return $query->result();
    }
}
