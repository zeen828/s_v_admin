<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

class Sold_items_model extends CI_Model
{
	private $table_name = 'Sold_items_tbl';
	private $fields_pk = 'si_pk';
	
	public function __construct ()
	{
		parent::__construct();
		// $this->load->config('set/databases_fiels', TRUE);
		$this->r_db = $this->load->database('vidol_billing_read', TRUE);
		$this->w_db = $this->load->database('vidol_billing_write', TRUE);
	}
	
	public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
		//parent::__destruct();
	}
}
