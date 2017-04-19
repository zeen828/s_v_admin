<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Registered_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('read', TRUE);
        $this->w_db = $this->load->database('write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    /**
     * 每日註冊數格林威治
     * 
     * @param unknown $satrt
     *            開始查詢時間
     * @param unknown $end
     *            結束查詢時間
     */
    public function get_day_count_by_utc ($satrt, $end)
    {
        $start_date = array();
        $start_date['date_tw'] = date("Y-m-d 00:00:00", strtotime($satrt));
        $start_date['time_tw'] = strtotime($start_date['date_tw']);
        $start_date['time_utc'] = $start_date['time_tw'] + (8 * 60 * 60);
        $end_date = array();
        $end_date['date_tw'] = date("Y-m-d 23:59:59", strtotime($end));
        $end_date['time_tw'] = strtotime($end_date['date_tw']);
        $end_date['time_utc'] = $end_date['time_tw'] + (8 * 60 * 60);
        $this->r_db->select("r_date_utc as date_utc, r_date_tw as date_tw, r_time as time, sum(`r_re_count`) as count_re, sum(`r_fb_count`) as count_fb, sum(`r_count`) as count");
        $this->r_db->where(sprintf("`r_time` BETWEEN %d AND %d", $start_date['time_utc'], $end_date['time_utc']));
        $this->r_db->group_by("r_year_utc, r_month_utc, r_day_utc");
        $this->r_db->order_by('r_time', 'ASC');
        $query = $this->r_db->get("Registered_tbl");
        // echo $this->r_db->last_query();
        return $query->result();
    }

    /**
     * 每日註冊數格林威治
     * 
     * @param unknown $satrt
     *            開始查詢時間
     * @param unknown $end
     *            結束查詢時間
     */
    public function get_hour_count_by_utc ($satrt, $end)
    {
        $start_date = array();
        $start_date['date_tw'] = date("Y-m-d H:00:00", strtotime($satrt));
        $start_date['time_tw'] = strtotime($start_date['date_tw']);
        $start_date['time_utc'] = $start_date['time_tw'] + (8 * 60 * 60);
        $end_date = array();
        $end_date['date_tw'] = date("Y-m-d H:59:59", strtotime($end));
        $end_date['time_tw'] = strtotime($end_date['date_tw']);
        $end_date['time_utc'] = $end_date['time_tw'] + (8 * 60 * 60);
        $this->r_db->select("r_date_utc as date_utc, r_date_tw as date_tw, r_time as time, sum(`r_re_count`) as count_re, sum(`r_fb_count`) as count_fb, sum(`r_count`) as count");
        $this->r_db->where(sprintf("`r_time` BETWEEN %d AND %d", $start_date['time_utc'], $end_date['time_utc']));
        $this->r_db->group_by("r_year_utc, r_month_utc, r_day_utc, r_hour_utc");
        $this->r_db->order_by('r_time', 'ASC');
        $query = $this->r_db->get("Registered_tbl");
        // echo $this->r_db->last_query();
        return $query->result();
    }

    /**
     * 每日註冊數台灣時間
     * 
     * @param unknown $satrt
     *            開始查詢時間
     * @param unknown $end
     *            結束查詢時間
     */
    public function get_day_count_by_tw ($satrt, $end)
    {
        $start_date = array();
        $start_date['date_tw'] = date("Y-m-d 00:00:00", strtotime($satrt));
        $start_date['time_tw'] = strtotime($start_date['date_tw']);
        $end_date = array();
        $end_date['date_tw'] = date("Y-m-d 23:59:59", strtotime($end));
        $end_date['time_tw'] = strtotime($end_date['date_tw']);
        $this->r_db->select("r_date_utc as date_utc, r_date_tw as date_tw, r_time as time, sum(`r_re_count`) as count_re, sum(`r_fb_count`) as count_fb, sum(`r_count`) as count");
        $this->r_db->where(sprintf("`r_time` BETWEEN %d AND %d", $start_date['time_tw'], $end_date['time_tw']));
        $this->r_db->group_by("r_year_tw, r_month_tw, r_day_tw");
        $this->r_db->order_by('r_time', 'ASC');
        $query = $this->r_db->get("Registered_tbl");
        // echo $this->r_db->last_query();
        return $query->result();
    }

    /**
     * 每小時註冊數台灣時間
     * 
     * @param unknown $satrt
     *            開始查詢時間
     * @param unknown $end
     *            結束查詢時間
     */
    public function get_hour_count_by_tw ($satrt, $end)
    {
        $start_date = array();
        $start_date['date_tw'] = date("Y-m-d H:00:00", strtotime($satrt));
        $start_date['time_tw'] = strtotime($start_date['date_tw']);
        $end_date = array();
        $end_date['date_tw'] = date("Y-m-d H:59:59", strtotime($end));
        $end_date['time_tw'] = strtotime($end_date['date_tw']);
        $this->r_db->select("r_date_utc as date_utc, r_date_tw as date_tw, r_time as time, sum(`r_re_count`) as count_re, sum(`r_fb_count`) as count_fb, sum(`r_count`) as count");
        $this->r_db->where(sprintf("`r_time` BETWEEN %d AND %d", $start_date['time_tw'], $end_date['time_tw']));
        $this->r_db->group_by("r_year_tw, r_month_tw, r_day_tw, r_hour_tw");
        $this->r_db->order_by('r_time', 'ASC');
        $query = $this->r_db->get("Registered_tbl");
        // echo $this->r_db->last_query();
        return $query->result();
    }
}
