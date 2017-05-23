<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class vidol_production_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('postgre_production_read', TRUE);
        $this->w_db = $this->load->database('postgre_production_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    //華劇大賞
    public function get_votes ()
    {
    	$this->r_db->select('category_no,video_id_no,COUNT(video_id_no) as couns,SUM(ticket) as tickets');
        $this->r_db->group_by(array('category_no', 'video_id_no'));
        $this->r_db->order_by('category_no', 'ASC');
        $this->r_db->order_by('video_id_no', 'ASC');
        $query = $this->r_db->get('votes');
        //echo $this->r_db->last_query();
        return $query;
    }
    
    //明星午茶
    public function get_afternoon ()
    {
    	$this->r_db->select('category_no,video_id_no,COUNT(video_id_no) as couns,SUM(ticket) as tickets');
    	$this->r_db->group_by(array('category_no', 'video_id_no'));
    	$this->r_db->order_by('category_no', 'ASC');
    	$this->r_db->order_by('video_id_no', 'ASC');
    	$query = $this->r_db->get('fvotes');
    	//echo $this->r_db->last_query();
    	return $query;
    }
    
    //華劇大賞
    public function update_votes_by_category_video ($category_no, $video_id_no, $ticket)
    {
    	$ticket = $ticket + 1;
    	$this->w_db->where('voter_id', '47291');
		$this->w_db->where('category_no', $category_no);
		$this->w_db->where('video_id_no', $video_id_no);
		$this->w_db->set('ticket', $ticket);
		$this->w_db->update('votes');
		$result = $this->w_db->affected_rows();
		echo $this->w_db->last_query();
		return $result;
    }
    
    //明星午茶
    public function update_afternoon_by_category_video ($category_no, $video_id_no, $ticket)
    {
    	$ticket = $ticket + 1;
    	$this->w_db->where('fvoter_id', '6');
    	$this->w_db->where('category_no', $category_no);
    	$this->w_db->where('video_id_no', $video_id_no);
    	$this->w_db->set('ticket', $ticket);
    	$this->w_db->update('fvotes');
    	$result = $this->w_db->affected_rows();
    	//echo $this->w_db->last_query();
    	return $result;
    }
	
    //華劇大賞
    public function cron_subtotal ()
    {
    	$this->r_db->select('category_no,video_id_no,COUNT(video_id_no) as ticket_count,SUM(ticket) as ticket_sum');
		$this->r_db->group_by(array('category_no', 'video_id_no'));
		$this->r_db->order_by('category_no', 'ASC');
		$this->r_db->order_by('video_id_no', 'ASC');
        $query = $this->r_db->get('votes');
        //echo $this->r_db->last_query();
        return $query;
    }
    
    //明星午茶
    public function cron_afternoon_subtotal ()
    {
    	$this->r_db->select('category_no,video_id_no,COUNT(video_id_no) as ticket_count,SUM(ticket) as ticket_sum');
    	$this->r_db->group_by(array('category_no', 'video_id_no'));
    	$this->r_db->order_by('category_no', 'ASC');
    	$this->r_db->order_by('video_id_no', 'ASC');
    	$query = $this->r_db->get('	fvotes');
    	//echo $this->r_db->last_query();
    	return $query;
    }
    
    //華劇大賞
    public function cron_get_voters($limit)
    {
    	$this->r_db->select('id,member_id');
    	$this->r_db->where('join_at', null);
    	$this->r_db->order_by('id', 'ASC');
    	$this->r_db->limit($limit);
    	$query = $this->r_db->get('voters');
    	//echo $this->r_db->last_query();
    	return $query;
    }
    
    //明星午茶
    public function cron_get_fvoters($limit)
    {
    	$this->r_db->select('id,member_id');
    	$this->r_db->where('join_at', null);
    	$this->r_db->order_by('id', 'ASC');
    	$this->r_db->limit($limit);
    	$query = $this->r_db->get('fvoters');
    	//echo $this->r_db->last_query();
    	return $query;
    }
    
    //華劇大賞
    public function cron_update_voters($id, $join_at)
    {
    	$this->w_db->where('id', $id);
		//$this->w_db->set('join_at', $join_at);
    	$this->w_db->set('join_at' , 'to_timestamp(' . $join_at . ')', FALSE);
		$this->w_db->update('voters');
		$result = $this->w_db->affected_rows();
		//echo $this->w_db->last_query();
		return $result;
    }
    
    //明星午茶
    public function cron_update_fvoters($id, $join_at)
    {
    	$this->w_db->where('id', $id);
    	//$this->w_db->set('join_at', $join_at);
    	$this->w_db->set('join_at' , 'to_timestamp(' . $join_at . ')', FALSE);
    	$this->w_db->update('fvoters');
    	$result = $this->w_db->affected_rows();
    	//echo $this->w_db->last_query();
    	return $result;
    }
    
    //玩很大
    public function cron_mrplay_subtotal ()
    {
    	$this->r_db->select('COUNT(id) as ticket_count,SUM(ticket) as ticket_sum');
    	$this->r_db->group_by('school_code');
    	$this->r_db->order_by('school_code', 'ASC');
    	$query = $this->r_db->get('mrplayer_votes');
    	echo $this->r_db->last_query();
    	return $query;
    }
    
}
