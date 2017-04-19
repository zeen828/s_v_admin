<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Boards_model extends CI_Model
{
	
    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('vidol_websocket_read', TRUE);
        $this->w_db = $this->load->database('vidol_websocket_write', TRUE);
    }
    
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function update_live_board ($video_type, $video_no, $update_video_type, $update_video_no)
    {
    	$this->w_db->where('b_type', $video_type);
    	$this->w_db->where('b_type_no', $video_no);
    	$this->w_db->set('b_type', $update_video_type);
    	$this->w_db->set('b_type_no', $update_video_no);
    	$this->w_db->update('Board_tbl');
    	$result = $this->w_db->affected_rows();
    	//echo $this->r_db->last_query();
    	return $result;
    }
    
    public function update_live_barrage ($video_type, $video_no, $update_video_type, $update_video_no)
    {
    	$this->w_db->where('b_type', $video_type);
    	$this->w_db->where('b_type_no', $video_no);
    	$this->w_db->set('b_type', $update_video_type);
    	$this->w_db->set('b_type_no', $update_video_no);
    	$this->w_db->update('Barrage_tbl');
    	$result = $this->w_db->affected_rows();
    	//echo $this->r_db->last_query();
    	return $result;
    }
}
