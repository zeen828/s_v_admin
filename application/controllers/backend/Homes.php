<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homes extends CI_Controller
{

    private $data_view;

    function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 權限
        $this->auth = new stdClass();
        $this->load->library('flexi_auth');
        if (! $this->flexi_auth->is_logged_in()) {
            redirect('homes/login');
        }
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
        $this->load->library('vidol/fun');
        $this->load->helper('formats');
        // 初始化
        $this->data_view = format_helper_backend_view_data('homes_content');
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        $this->home();
        //grocery_CRUD 多選功能
        //$crud->field_type('p_rs','enum',array('active','private','spam','deleted'));
        //$crud->field_type('p_status','set',array('banana','orange','apple','lemon'));
        //$crud->field_type('p_proxy','dropdown',array('1' => 'active', '2' => 'private','3' => 'spam' , '4' => 'deleted'));
        //$crud->field_type('p_type','multiselect',array( "1"  => "banana", "2" => "orange", "3" => "apple"));
    }

    /**
     * 首頁
     */
    public function home ()
    {
        try {
            // 變數
            $data_post = array();
            // 引用
            $this->load->model('admin/dashboard_model');
$this->load->library('mongo_db');
$this->load->model('mongo_model');
            $this->load->driver ( 'cache', array (
            		'adapter' => 'memcached',
            		'backup' => 'dummy'
            ) );
            //直播觀看數取memcached值
            //$cache_name = sprintf ( 'channel_%s_room', 9 );
            //$taiwan = $this->cache->memcached->get ( $cache_name );
            //$cache_name = sprintf ( 'channel_%s_room', 10 );
            //$metro = $this->cache->memcached->get ( $cache_name );
            // 呼叫websocket
            $websocket_com = sprintf("python /home/socket_server/websocket/get_userinroom.py");
            $tmp_channel = exec($websocket_com);
            $tmp_channel = json_decode($tmp_channel);
            //print_r($tmp_channel);
            $taiwan = $tmp_channel->C9;
            $metro = $tmp_channel->C10;
            
            // 磁碟
            // $tmp['disk_totalspace'] =
            // $this->dashboard_model->disk_totalspace(DIRECTORY_SEPARATOR);
            // $tmp['disk_freespace'] =
            // $this->dashboard_model->disk_freespace(DIRECTORY_SEPARATOR);
            // $tmp['disk_usespace'] = $tmp['disk_totalspace'] -
            // $tmp['disk_freespace'];
            //*$disk_usepercent = $this->dashboard_model->disk_usepercent(DIRECTORY_SEPARATOR, FALSE);
            // 記憶體
            // $tmp['memory_usage'] = $this->dashboard_model->memory_usage();
            // $tmp['memory_peak_usage'] =
            // $this->dashboard_model->memory_peak_usage(TRUE);
            //*$memory_usepercent = $this->dashboard_model->memory_usepercent(TRUE, FALSE);
            // facebook註冊數
$user_fb_count = $this->mongo_model->get_user_count_by_facebook();
//$user_fb_count = 0;
            // 註冊會員總數
$user_count = $this->mongo_db->count('_User');
//$user_count = 0;
            // 資料整理
            $this->data_view['right_countent']['view_path'] = 'AdminLTE/homes/home';
            $this->data_view['right_countent']['view_data'] = array(
                    'header' => array(
                    		'taiwan'=>$taiwan,
                    		'metro'=>$metro,
                            //'disk' => $disk_usepercent,
                            //'memory' => $memory_usepercent,
                            'user_fb_count' => number_format($user_fb_count),
                            'users_count' => number_format($user_count)
                    ),
                    'tip' => '補發認證信 更改為 會員查詢.',
            );
            $this->data_view['right_countent']['tags']['tag_2'] = array(
                    'title' => 'Dashboard',
                    'link' => '/backend/homes',
                    'class' => 'fa-dashboard'
            );
            // 套版
            $this->load->view('AdminLTE/include/html5', $this->data_view);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    // 帳戶個人資料
    public function personal ()
    {
        try {
        	// 寫log
        	$this->fun->logs('個人檔案');
        	// 引用
        	$this->load->helper(array('form', 'url'));
        	$this->load->library('form_validation');
            // 變數
            $data_post = array();
            // form validation
            $this->form_validation->set_rules('old_password', 'Old Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');
            // 資料整理
            $this->data_view['right_countent']['view_path'] = 'AdminLTE/homes/personal';
            $this->data_view['right_countent']['tags']['tag_2'] = array(
                    'title' => '個人檔案',
                    'link' => '/backend/homes/personal',
                    'class' => 'fa-user'
            );
            // 套版
            $this->load->view('AdminLTE/include/html5', $this->data_view);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    // 帳戶個人資料編輯
    public function personal_edit ()
    {
    	try {
    		// 寫log
    		$this->fun->logs('編輯個人檔案');
    		// 變數
    		$data_post = array();
    		// input
    		$data_post['password'] = $this->input->post('password');
    		$data_post['new_pass'] = $this->input->post('new_pass');
    		$data_post['confirm_pass'] = $this->input->post('confirm_pass');
    		//print_r($data_post);
    		// 改密碼
    		if (! empty($data_post['password']) && ! empty($data_post['new_pass']) && $data_post['new_pass'] == $data_post['confirm_pass']) {
    			$identity = $this->flexi_auth->get_user_identity();
    			$status = $this->flexi_auth->change_password($identity, $data_post['password'], $data_post['new_pass']);
    			print_r($status);
    		}
    		// 資料整理
    		$this->data_view['right_countent']['view_path'] = 'AdminLTE/homes/personal_edit';
    		$this->data_view['right_countent']['tags']['tag_2'] = array(
    				'title' => '個人檔案',
    				'link' => '/backend/homes/personal',
    				'class' => 'fa-user'
    		);
    		//print_r($this->data_view);
    		// 套版
    		$this->load->view('AdminLTE/include/html5', $this->data_view);
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function personal_post ()
    {
    	try {
    		// 寫log
    		$this->fun->logs('編輯個人檔案{POST}');
    		// 變數
    		$data_post = array();
    		// input
    		$data_post['password'] = $this->input->post('password');
    		$data_post['new_pass'] = $this->input->post('new_pass');
    		$data_post['confirm_pass'] = $this->input->post('confirm_pass');
    		//print_r($data_post);
    		// 改密碼
    		if (! empty($data_post['password']) && ! empty($data_post['new_pass']) && $data_post['new_pass'] == $data_post['confirm_pass']) {
    			$identity = $this->flexi_auth->get_user_identity();
    			$status = $this->flexi_auth->change_password($identity, $data_post['password'], $data_post['new_pass']);
    			print_r($status);
    		}
    		// 資料整理
    		$this->data_view['right_countent']['view_path'] = 'AdminLTE/homes/personal_edit';
    		$this->data_view['right_countent']['tags']['tag_2'] = array(
    				'title' => '個人檔案',
    				'link' => '/backend/homes/personal',
    				'class' => 'fa-user'
    		);
    		//print_r($this->data_view);
    		// 套版
    		$this->load->view('AdminLTE/include/html5', $this->data_view);
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
}
