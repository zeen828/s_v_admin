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

    public function get_Board_by_type_typeno($select, $type, $typeno, $limit_start = 0, $limit = 100) {
    	if (! empty ( $select )) {
    		$this->r_db->select ( $select );
    	}
    	$this->r_db->where ( 'b_type', $type );
    	$this->r_db->where ( 'b_type_no', $typeno );
    	$this->r_db->where ( 'b_status', '1' );
    	$this->r_db->order_by( 'b_no', 'ASC');
    	$this->r_db->limit ( $limit, $limit_start );
    	$query = $this->r_db->get ( 'Board_tbl' );
    	echo $this->r_db->last_query();
    	return $query;
    }
}
