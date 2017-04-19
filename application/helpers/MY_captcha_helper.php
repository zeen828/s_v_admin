<?php
defined('BASEPATH') or exit('No direct script access allowed');

// 建立
if (! function_exists('create_my_captcha')) {

    function create_my_captcha ($img_width, $img_height)
    {
        $CI = & get_instance();
        $CI->config->load('vidol');
        $CI->load->database('write', TRUE);
        $CI->load->helper('captcha');
        // captcha
        $vals = array(
                'img_path' => $CI->config->item('captcha_path'),
                'img_url' => $CI->config->item('captcha_url'),
                'pool' => '0123456789',
                'font_size' => 24,
                'word_length' => 4,
                'img_width' => 250,
                'img_height' => 43
        );
        $captcha_arr = create_captcha($vals);
        // insert db
        $data = array(
                'captcha_time' => $captcha_arr['time'],
                'ip_address' => $CI->input->ip_address(),
                'word' => $captcha_arr['word']
        );
        $CI->db->set($data);
        $CI->db->insert('captcha');
        $id = $CI->db->insert_id();
//         echo $CI->db->last_query();
//         var_dump($CI->db);
//         var_dump($id);
        if (empty($id)) {
            return false;
        }
        return $captcha_arr;
    }
}

// 檢查
if (! function_exists('checking_my_captcha')) {

    function checking_my_captcha ($word)
    {
        $CI = & get_instance();
        $CI->load->database();
        if (! empty($word)) {
            $expiration = time() - 7200;
            $client_ip = $CI->input->ip_address();
            $CI->db->where('word', $word);
            $query = $CI->db->get('captcha');
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    if ($row->captcha_time >= $expiration && $row->ip_address == $client_ip) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

// 刪除
if (! function_exists('delete_my_captcha')) {

    function delete_my_captcha ($data = '', $img_path = '', $img_url = '', $font_path = '')
    {
        $captcha_arr = '';
        return $captcha_arr;
    }
}
