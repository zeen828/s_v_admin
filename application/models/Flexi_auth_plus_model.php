<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Flexi_auth_plus_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
    }

    public function get_groups_privileges ($group_id)
    {
        $data_group_privileges = array();
        $this->db->select('upriv_groups_upriv_fk');
        $this->db->where('upriv_groups_ugrp_fk', $group_id);
        $query = $this->db->get('user_privilege_groups');
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data_group_privileges[] = $row->upriv_groups_upriv_fk;
            }
        }
        return $data_group_privileges;
    }

    public function check_groups_privileges ($group_id, $privileges_id)
    {
        $this->db->where('upriv_groups_ugrp_fk', $group_id);
        $this->db->where('upriv_groups_upriv_fk', $privileges_id);
        $count = $this->db->count_all_results('user_privilege_groups');
        // echo $this->db->last_query();
        if ($count > 0) {
            return true;
        }
        return false;
    }
}
