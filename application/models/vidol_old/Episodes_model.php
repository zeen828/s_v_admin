<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Episodes_model extends CI_Model
{
	
    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('vidol_old_read', TRUE);
        $this->w_db = $this->load->database('vidol_old_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function insert_episodes ($type, $type_no, $programme_no, $programme_ep, $free, $time_creat)
    {
		$this->w_db->set('e_programme_no', $programme_no);
		$this->w_db->set('e_programme_ep', $programme_ep);
		$this->w_db->set('e_type', $type);
		$this->w_db->set('e_type_no', $type_no);
		$this->w_db->set('e_free', $free);
		$this->w_db->set('e_time_creat', $time_creat);
        $this->w_db->insert('Episodes_tbl');
        $id = $this->w_db->insert_id();
        //echo $this->w_db->last_query();
        return $id;
    }
    
    public function get_episodes_max_type_no ($type='episode')
    {
    	$this->r_db->select('e_type_no');
    	$this->r_db->where('e_type', $type);
    	$this->r_db->limit(1);
    	$this->r_db->order_by('e_type_no', 'DESC');
    	$query = $this->r_db->get('Episodes_tbl');
    	//echo $this->r_db->last_query();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		return $row->e_type_no;
    	}else{
    		return 0;
    	}
    }
}
