<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Brightcove_model extends CI_Model
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
    
    public function get_report ($date_start, $date_end)
    {
        $this->r_db->select('sum(`b_view`) as view, max(`b_city`) as city, max(`b_country`) as country, max(`b_region`) as region, max(`b_device_os`) as device_os, max(`b_device_type`) as device_type');
        $this->r_db->where(sprintf('b_date_tw BETWEEN \'%s\' AND \'%s\'', $date_start, $date_end));
        $query = $this->r_db->get('Brightcove_tbl');
        // echo $this->r_db->last_query();
        return $query->row();
    }

    public function get_report_type ($type, $date_start, $date_end)
    {
        $this->r_db->select('`b_title` as title, sum(`b_view`) as view');
        $this->r_db->where('b_type', $type);
        $this->r_db->where(sprintf('b_date_tw BETWEEN \'%s\' AND \'%s\'', $date_start, $date_end));
        $this->r_db->group_by('b_title');
        $query = $this->r_db->get('Brightcove_report_tbl');
        echo $this->r_db->last_query();
        return $query->result();
    }

    public function insert_report_data ($data_api)
    {
        $data_brightcove = array(
                'b_date_tw' => $data_api['date'],
                'b_view' => $data_api['account'],
                'b_city' => $data_api['city'],
                'b_country' => $data_api['country'],
                'b_region' => $data_api['region'],
                'b_device_os' => $data_api['device_os'],
                'b_device_type' => $data_api['device_type']
        );
        $status = $this->w_db->replace('Brightcove_tbl', $data_brightcove);
        // echo $this->w_db->last_query();
        return $status;
    }

    public function insert_report_type_data ($date, $type, $data_api)
    {
        if (! empty($type) && isset($data_api['item_count']) && count($data_api['item_count']) > 0) {
            foreach ($data_api['items'] as $key => $val) {
                $data_brightcove = array(
                        'b_date_tw' => $date,
                        'b_type' => $type,
                        'b_title' => $val[$type],
                        'b_view' => $val['video_view']
                );
                $status = $this->w_db->replace('Brightcove_report_tbl', $data_brightcove);
                // echo $this->w_db->last_query();
            }
            return true;
        } else {
            return false;
        }
    }
}
