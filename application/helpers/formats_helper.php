<?php
/**
 * 產出預設後台資料結構
 */
if (! function_exists('format_helper_backend_view_data')) {

    function format_helper_backend_view_data ($class = 'main')
    {
        // 變數
        $CI = & get_instance();
        // 使用者(預設值)
        $user_data = array(
                'user_id' => '0',
                'email' => 'service@vidol.tv',
                'username' => 'vidol',
                'group' => 'vidol',
                'user_img' => 'http://admin-background.vidol.tv/assets/img/puu.jpg'
        );
        // 使用者
        $user_id = $CI->flexi_auth->get_user_id();
        if (! empty($user_id)) {
            $select_user = array(
                    'uacc_id as user_id',
                    'uacc_email as email',
                    'uacc_username as username',
                    'uacc_personal_figure as user_img'
            );
            $query = $CI->flexi_auth->get_user_by_id($user_id, $select_user);
            if ($query->num_rows() > 0) {
                $user_data = $query->row_array();
                if (empty($user_data['user_img'])) {
                    $user_data['user_img'] = 'http://admin-background.vidol.tv/assets/img/puu.jpg';
                }
            }
        }
        // js特效
        $time = time();
        $javascript_data = array();
        //聖誕節,下雪
        if($time >= strtotime(date('Y-12-01')) && $time <= strtotime(date('Y-12-25'))){
        	$javascript_data[] = '/assets/jquery_plugind/snow-3/snow-v3.jquery.js';
        }
        //跨年,煙火
        if($time >= strtotime(date('Y-12-26')) && $time <= strtotime(date('Y-01-01', strtotime('+1 year')))){
        	$javascript_data[] = '/assets/jquery_plugind/fireworks/fireworks_3.js';
        }
        // 群組
        $group = $CI->flexi_auth->get_users_group()->row();
        if (! empty($group)) {
            $user_data['group'] = $group->ugrp_name;
        }
        // 資料結構
        $result = array(
                'meta' => array(
                        'title' => 'vidol backend',
                		'javascript' => $javascript_data,
                ),
                'header' => array(
                        'user' => $user_data,
                ),
                'left_menu' => array(
                        'user' => $user_data,
                        'my' => array(
                                'title' => '',
                                'link' => '/backend/users/my'
                        )
                ),
                'right_countent' => array(
                        'user' => $user_data,
                        'title' => '',
                        'class' => $class,
                        'tags' => array(
                                'tag_1' => array(
                                        'title' => '首頁',
                                        'link' => '/backend',
                                        'class' => 'fa-home'
                                ),
                                'tag_2' => array(
                                        'title' => '',
                                        'link' => '/backend',
                                        'class' => 'fa-home'
                                ),
                                'tag_3' => array(
                                        'title' => '',
                                        'link' => '/backend',
                                        'class' => 'fa-home'
                                ),
                                'tag_4' => array(
                                        'title' => '',
                                        'link' => '/backend',
                                        'class' => 'fa-home'
                                )
                        ),
                        'view_path' => null,
                        'view_data' => null
                ),
                'footer' => array(
                        'title' => 'SET',
                        'link' => 'http://www.settv.com.tw',
                        'version' => '2.3.3'
                ),
                'system' => array(
                		'action' => 'tmpe',
                        'lang' => 'zh-Hant-TW',
                        'themes' => 'AdminLTE',
                        'time' => 0
                )
        );
        return $result;
    }
}

if (! function_exists('format_helper_return_data')) {

    function format_helper_return_data ()
    {
        // 變數
    	$result = array(
    			'api' => '',
    			'status' => false,
    			'info' => array(),
    			'input' => array(),
    			'data' => array(),
    			'time' => 0
    	);
        return $result;
    }
}
