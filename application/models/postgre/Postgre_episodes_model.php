<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Postgre_episodes_model extends CI_Model
{
	
    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('postgre_read', TRUE);
        $this->w_db = $this->load->database('postgre_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    /**
     * 取得串流資料
     * @param unknown $no	以爬取號碼以後的資料
     * @param number $limit	顯示筆數
     * @return unknown
     */
    public function get_episodes ($no, $limit = 50)
    {
    	$this->r_db->select('id, programme_id, episode_number, free, created_at');
    	$this->r_db->where('id >', $no);
    	$this->r_db->limit($limit);
    	$this->r_db->order_by('id', 'ASC');
    	$query = $this->r_db->get('episodes');
    	echo $this->r_db->last_query();
    	return $query;
    }
}
