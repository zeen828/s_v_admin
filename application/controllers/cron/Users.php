<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Users extends CI_Controller
{

    private $data_result;

    function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 引用
        $this->load->library('session');
        $this->load->library('lib_log');
        $this->load->helper('formats');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        show_404();
    }
    
    public function demo ()
    {
    	print_r(array('DEMO'));
    }
    public function mongo_test ()
    {
		$this->load->library ( 'mongo_db' );
		$my_data = array(
			'start_m' => 2,
			'end_m' => date('m'),
			'where_date'=>array(),
		);
		//總比數
		$count = $this->mongo_db->count ( '_User' );
		echo "總筆數:", $count, "<br/>";
		//小於2月
		$tmp = new MongoDate( strtotime( '2017-02-01 00:00:01' ) );
		$count = $this->mongo_db->where_gt( '_created_at', $tmp )->count ( '_User' );
		echo "大於2017-2月總筆數gt:", $count, "<br/>";
		$tmp = new MongoDate( strtotime( '2017-02-01 00:00:01' ) );
		$count = $this->mongo_db->where_lt( '_created_at', $tmp )->count ( '_User' );
		echo "小於2017-2月總筆數lt:", $count, "<br/>";
		//每個月
		for ($i = $my_data['start_m']; $i <= $my_data['end_m']; $i ++) {
			$star = sprintf('2017-%s-01 00:00:01', $i);
			$star_time = new MongoDate( strtotime( $star ) );
			$end = sprintf('2017-%s-01 00:00:01', $i + 1);
			$end_time = new MongoDate( strtotime( $end ) );
			$my_data['where_date'][$i] = array(
				'star'=>$star,
				'star_time'=>$star_time,
				'end'=>$end,
				'end_time'=>$end_time,
			);
			$count = $this->mongo_db->where_gte ( '_created_at', $star_time )->where_lt( '_created_at', $end_time )->count ( '_User' );
			echo "2017-", $i, "月總筆數:", $count, "<br/>";
		}
		//大於9月
		$tmp = new MongoDate( strtotime( '2017-09-01 00:00:01' ) );
		$count = $this->mongo_db->where_gt( '_created_at', $tmp )->count ( '_User' );
		echo "大於2017-9月月總筆數gt:", $count, "<br/>";
		$count = $this->mongo_db->where_lt( '_created_at', $tmp )->count ( '_User' );
		echo "小於2017-9月月總筆數lt:", $count, "<br/>";
		//
		$star_time = new MongoDate( strtotime( '2017-01-01 00:00:01' ) );
		$end_time = new MongoDate( strtotime( '2018-01-01 00:00:01' ) );
		$count = $this->mongo_db->where_gte ( '_created_at', $star_time )->where_lt( '_created_at', $end_time )->count ( '_User' );
		echo "2017年總筆數:", $count, "<br/>";
		//
		$count = $this->mongo_db->where ( '_created_at', 'null' )->count ( '_User' );
		echo "沒有時間欄位的:", $count, "<br/>";
		$count = $this->mongo_db->where ( '_created_at', null )->count ( '_User' );
		echo "有時間欄位的:", $count, "<br/>";
		print_r($my_data);
		exit();
		$count = $this->mongo_db->count ( '_User' );
		echo "總比數:", $count, "<br/>";
		//2017年8月14日星期一 23:28:34 GMT+08:00
		$my_data = array(
			'member_id' => '',
			'start' => '2017-08-13 00:00:00',
			'start_time'=>'0',
			'start_mongo_time'=>'0',
			'end' => '2017-08-14 00:00:00',
			'end_time'=>'0',
			'end_mongo_time'=>'0',
		);
		//
		
		$my_data['start_mongo_time'] = new MongoDate( strtotime( $my_data['start'] ) );
		$my_data['end_mongo_time'] = new MongoDate( strtotime( $my_data['end'] ) );
		$count = $this->mongo_db->where_gt ( '_created_at', $my_data['start_mongo_time'] )->where_lte( '_created_at', $my_data['end_mongo_time'])->count ( '_User' );
		print_r($count);
		print_r($my_data);
    }

    /**
     * 每小時註冊數
     * 
     * @param string $date  指定時間            
     * http://xxx.xxx.xxx/cron/users/registered_hour/20160607130000
     * http://xxx.xxx.xxx/cron/users/registered_hour
     * # 分 時 日 月 週 指令
     * 10 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron users registered_hour
     * 日期跑掉的話重寫用SQL
     * UPDATE `Registered_tbl` SET `r_date_utc` = concat(`r_year_utc`, '-', `r_month_utc`, '-', `r_day_utc`, ' ', `r_hour_utc`, ':00:00')
     */
    public function registered_hour ($date = '')
    {
        try {
            //
            $data = array();
            // 引用
            $this->load->model('mongo_model');
            // 開始時間
            $start_time = strtotime($date . "-1 hour");
            $start_date = date("Y-m-d H:00:00", $start_time);
            // 記錄時間
            $tmp_data = date("Y-m-d H:00:00", $start_time);
            $tmp_time = strtotime($tmp_data);
            // 結束時間
            $end_time = strtotime($date . "-0 hour");
            $end_date = date("Y-m-d H:00:00", $end_time);
            // 查詢
            $data_mongo_user_count = $this->mongo_model->get_user_count_by_createdat($start_date, $end_date);
            $data_mongo_user_fb_count = $this->mongo_model->get_user_fb_count_by_createdat($start_date, $end_date);
            // 寫入資料庫用
            // Y年,n月,j日,G時
            $data['r_date_utc'] = date("Y-m-d H:i:s", strtotime($tmp_data . "-8 hour"));
            $data['r_year_utc'] = date("Y", strtotime($tmp_data . "-8 hour"));
            $data['r_month_utc'] = date("n", strtotime($tmp_data . "-8 hour"));
            $data['r_day_utc'] = date("j", strtotime($tmp_data . "-8 hour"));
            $data['r_hour_utc'] = date("G", strtotime($tmp_data . "-8 hour"));
            $data['r_date_tw'] = date("Y-m-d H:i:s", $tmp_time);
            $data['r_year_tw'] = date("Y", $tmp_time);
            $data['r_month_tw'] = date("n", $tmp_time);
            $data['r_day_tw'] = date("j", $tmp_time);
            $data['r_hour_tw'] = date("G", $tmp_time);
            $data['r_time'] = $tmp_time;
            $data['r_re_count'] = $data_mongo_user_count - $data_mongo_user_fb_count;
            $data['r_fb_count'] = $data_mongo_user_fb_count;
            $data['r_count'] = $data_mongo_user_count;
            // 組合
            $sql = "INSERT INTO `Registered_tbl` (`r_date_utc`, `r_year_utc`, `r_month_utc`, `r_day_utc`, `r_hour_utc`, `r_date_tw`, `r_year_tw`, `r_month_tw`, `r_day_tw`, `r_hour_tw`, `r_time`, `r_re_count`, `r_fb_count`, `r_count`) VALUES ('%s', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d') ON DUPLICATE KEY UPDATE `r_time` = '%s', `r_re_count` = '%s', `r_fb_count` = '%s', `r_count` = '%s';";
            $sql = sprintf($sql, $data['r_date_utc'], $data['r_year_utc'], $data['r_month_utc'], $data['r_day_utc'], $data['r_hour_utc'], $data['r_date_tw'], $data['r_year_tw'], $data['r_month_tw'], $data['r_day_tw'], $data['r_hour_tw'], $data['r_time'], $data['r_re_count'], $data['r_fb_count'], $data['r_count'], $data['r_time'], $data['r_re_count'], $data['r_fb_count'], $data['r_count']);
            if ($this->db->simple_query($sql)) {
                trigger_error(sprintf("統計註冊數[%s].", $tmp_data), 1024);
                $this->data_result['status'] = true;
                $this->data_result['data'] = $data;
            }
            // 輸出
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->data_result));
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    //10 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron users age
    public function age ($date = '')
    {
    	try {
    		//
    		$where_time = array(
    				'start_time'=>0,
    				'start_date'=>'0000-00-00',
    				'end_time'=>0,
    				'end_date'=>'0000-00-00',
    		);
    		$data = array();
    		// 引用
    		$this->load->model('mongo_model');
    		$this->load->model('vidol_user/user_age_model');
    		for($y = 1; $y <= 150; $y++){
    			//最小查詢時間
    			$where_time['start_time'] = strtotime($date . '-' . $y . ' year');
    			$where_time['start_date'] = date('Y-m-d', $where_time['start_time']);
				//最大查詢時間
    			$where_time['end_time'] = strtotime($where_time['start_date'] . '+1 year');
    			$where_time['end_date'] = date('Y-m-d', $where_time['end_time']);
    			//取得年紀分佈數
    			$count = $this->mongo_model->get_user_count_by_facebook_age($where_time['start_date'], $where_time['end_date']);
    			$where_time['count'] = $count;
				//新增資料
    			$data['ua_age'] = $y;
    			$data['ua_min_year'] = $where_time['start_date'];
    			$data['ua_max_year'] = $where_time['end_date'];
    			$data['ua_count'] = $count;
    			$where_time['DB'] = $this->user_age_model->not_repeating_User_age('*', $data);
    			//輸出
    			$where_time['data'] = $data;
    			$this->data_result[] = $where_time;
    		}
    		$this->data_result['status'] = true;
    		$this->output->set_content_type('application/json');
    		$this->output->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }

    /**
     * 每小時註冊數
     * 補資料用
     */
    public function test ($st=null, $en=null)
    {
        try {
            //
            $data = array();
            // 引用
            $this->load->model('mongo_model');
            if(empty($st)){
            	$st = 150;
            }
            if(empty($en)){
            	$en = 0;
            }
            $this->data_result['debug']['date']['st'] = $st;
            $this->data_result['debug']['date']['en'] = $en;
            for ($i = $st; $i > $en; $i --) {
                // 開始時間
                $start_time = strtotime("-" . $i . " hour");
                // $start_time = strtotime($date . "-2 hour");
                $start_date = date("Y-m-d H:00:00", $start_time);
                // 記錄時間
                $tmp_data = date("Y-m-d H:00:00", $start_time);
                $tmp_time = strtotime($tmp_data);
                // 結束時間
                $end_time = strtotime("-" . ($i - 1) . " hour");
                // $end_time = strtotime($date . "-1 hour");
                $end_date = date("Y-m-d H:00:00", $end_time);
                // 查詢
                $data_mongo_user_count = $this->mongo_model->get_user_count_by_createdat($start_date, $end_date);
                $data_mongo_user_fb_count = $this->mongo_model->get_user_fb_count_by_createdat($start_date, $end_date);
                // 寫入資料庫用
                // Y年,n月,j日,G時
                $data['r_date_utc'] = date("Y-m-d H:i:s", strtotime($tmp_data . "-8 hour"));
                $data['r_year_utc'] = date("Y", strtotime($tmp_data . "-8 hour"));
                $data['r_month_utc'] = date("n", strtotime($tmp_data . "-8 hour"));
                $data['r_day_utc'] = date("j", strtotime($tmp_data . "-8 hour"));
                $data['r_hour_utc'] = date("G", strtotime($tmp_data . "-8 hour"));
                $data['r_date_tw'] = date("Y-m-d H:i:s", $tmp_time);
                $data['r_year_tw'] = date("Y", $tmp_time);
                $data['r_month_tw'] = date("n", $tmp_time);
                $data['r_day_tw'] = date("j", $tmp_time);
                $data['r_hour_tw'] = date("G", $tmp_time);
                $data['r_time'] = $tmp_time;
                $data['r_re_count'] = $data_mongo_user_count - $data_mongo_user_fb_count;
                $data['r_fb_count'] = $data_mongo_user_fb_count;
                $data['r_count'] = $data_mongo_user_count;
                // 組合
                $sql = "INSERT INTO `Registered_tbl` (`r_date_utc`, `r_year_utc`, `r_month_utc`, `r_day_utc`, `r_hour_utc`, `r_date_tw`, `r_year_tw`, `r_month_tw`, `r_day_tw`, `r_hour_tw`, `r_time`, `r_re_count`, `r_fb_count`, `r_count`) VALUES ('%s', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d') ON DUPLICATE KEY UPDATE `r_time` = '%s', `r_re_count` = '%s', `r_fb_count` = '%s', `r_count` = '%s';";
                $sql = sprintf($sql, $data['r_date_utc'], $data['r_year_utc'], $data['r_month_utc'], $data['r_day_utc'], $data['r_hour_utc'], $data['r_date_tw'], $data['r_year_tw'], $data['r_month_tw'], $data['r_day_tw'], $data['r_hour_tw'], $data['r_time'], $data['r_re_count'], $data['r_fb_count'], $data['r_count'], $data['r_time'], $data['r_re_count'], $data['r_fb_count'], $data['r_count']);
                if ($this->db->simple_query($sql)) {
                    trigger_error(sprintf("統計註冊數[%s].", $tmp_data), 1024);
                    $this->data_result['status'] = true;
                    $this->data_result['data'] = $data;
                }
            }
            // 輸出
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->data_result));
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    //php /var/www/codeigniter/3.0.6/admin/index.php cron users copy_mysql
    //30 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron users copy_mysql
    public function copy_mysql ()
    {
        //db.getCollection('_User').find().limit(10).sort({_created_at:1})
        $limit = 10000;
        $user_count = $this->db->count_all('vidol_old.User_tbl');
        $this->load->library('mongo_db');
        $this->mongo_db->limit($limit);
        $this->mongo_db->offset($user_count);
        $this->mongo_db->order_by(array('_created_at'=>'asc'));
        $user = $this->mongo_db->get('_User');
        if (count($user) > 0) {
            foreach ($user as $val) {
                //print_r($val);
                if (isset($val['_id'])) {
                    $this->db->set('u_mongo_id', $val['_id']);
                }
                if (isset($val['member_id'])) {
                    $this->db->set('u_member_id', $val['member_id']);
                }
                if (isset($val['nick_name'])) {
                    $this->db->set('u_nick_name', $val['nick_name']);
                }
                if (isset($val['username'])) {
                    $this->db->set('u_username', $val['username']);
                }
                if (isset($val['_hashed_password'])) {
                    $this->db->set('u_password', $val['_hashed_password']);
                }
                if (isset($val['_perishable_token'])) {
                    $this->db->set('u_token', $val['_perishable_token']);
                }
                if (isset($val['_session_token'])) {
                    $this->db->set('u_session_token', $val['_session_token']);
                }
                if (isset($val['email'])) {
                    $this->db->set('u_email', $val['email']);
                }
                if (isset($val['contact_email'])) {
                    $this->db->set('u_email_contact', $val['contact_email']);
                }
                if (isset($val['_email_verify_token'])) {
                    $this->db->set('u_email_verify_token', $val['_email_verify_token']);
                }
                if (isset($val['emailVerified'])) {
                    $this->db->set('u_email_verified', $val['emailVerified']);
                }
                if (isset($val['_auth_data_facebook'])) {
                    $this->db->set('u_facebook_data', json_encode($val['_auth_data_facebook']));
                    if (isset($val['_auth_data_facebook']['id'])) {
                        $this->db->set('u_facebook_id', $val['_auth_data_facebook']['id']);
                    }
                    if (isset($val['_auth_data_facebook']['access_token'])) {
                        $this->db->set('u_facebook_token', $val['_auth_data_facebook']['access_token']);
                    }
                    //if (isset($val['_auth_data_facebook']['expiration_date']) && isset($val['_auth_data_facebook']['expiration_date']->sec)) {
                        //$exampleDate = new MongoDate();
                        //$date = date(DATE_ISO8601, $val['_auth_data_facebook']['expiration_date']->sec);
                        //$this->db->set('u_facebook_expiration', $date);
                    //}
                }
                if (isset($val['_created_at'])) {
                    if (isset($val['_created_at']->sec)) {
                        $tmp_date = date(DATE_ISO8601, $val['_created_at']->sec);
                        $this->db->set('u_creat_utc', $tmp_date);
                        $this->db->set('u_creat_unix', $val['_created_at']->sec);
                    }
                }
                if (isset($val['_updated_at'])) {
                    if (isset($val['_updated_at']->sec)) {
                        $tmp_date = date(DATE_ISO8601, $val['_updated_at']->sec);
                        $this->db->set('u_update_utc', $tmp_date);
                        $this->db->set('u_update_unix', $val['_updated_at']->sec);
                    }
                }
                $this->db->insert('vidol_old.User_tbl');
            }
        }
    }
    
    public function new_copy_mysql ()
    {
    	//db.getCollection('_User').find().limit(10).sort({_created_at:1})
    	//$limit = 10000;
    	$limit = 50000;
    	$user_count = $this->db->count_all('vidol_user.User_profile_tbl');
    	$data_arr = array(
    			'limit'=>$limit,
    			'user_count'=>$user_count,
    	);
    	$this->load->library('mongo_db');
    	$this->mongo_db->limit($limit);
     	$this->mongo_db->offset($user_count);
    	//$this->mongo_db->order_by(array('_created_at'=>'asc'));
    	$user = $this->mongo_db->get('_User');
    	//print_r($user);
    	//exit();
    	if (count($user) > 0) {
    		foreach ($user as $val) {
    			if (isset($val['_id'])) {
    				$this->db->set('u_mongo_id', $val['_id']);
    			}
    			if (isset($val['member_id'])) {
    				$this->db->set('u_member_id', $val['member_id']);
    			}
    			if (isset($val['email'])) {
    				$this->db->set('u_email', $val['email']);
    			}
    			if (isset($val['contact_email'])) {
    				//if(!isset($val['email'])){
    					//$this->db->set('u_email', $val['contact_email']);
    				//}
    				$this->db->set('u_contact_email', $val['contact_email']);
    			}
    			if (isset($val['_email_verify_token'])) {
    				$this->db->set('u_email_token', $val['_email_verify_token']);
    			}
    			if (isset($val['_hashed_password'])) {
    				$this->db->set('u_password', $val['_hashed_password']);
    			}
    			if (isset($val['_auth_data_facebook'])) {
    				$this->db->set('u_fb_oauth', json_encode($val['_auth_data_facebook']));
    				if (isset($val['_auth_data_facebook']['id'])) {
    					$this->db->set('u_fb_id', $val['_auth_data_facebook']['id']);
    				}
    				if (isset($val['_auth_data_facebook']['access_token'])) {
    					$this->db->set('u_fb_token', $val['_auth_data_facebook']['access_token']);
    				}
    			}
    			if (isset($val['username'])) {
    				$this->db->set('u_user_name', $val['username']);
    			}
    			if (isset($val['nick_name'])) {
    				$this->db->set('u_nick_name', $val['nick_name']);
    			}
    			if (isset($val['birth_date'])) {
    				$this->db->set('u_birthday', $val['birth_date']);
    			}
    			if (isset($val['gender'])) {
    				$this->db->set('u_gender', $val['gender']);
    			}
    			if (isset($val['contact_number'])) {
    				$this->db->set('u_phone', $val['contact_number']);
    				//$this->db->set('u_tel', $val['contact_number']);
    				//$this->db->set('u_fax', $val['contact_number']);
    			}
    			if (isset($val['address'])) {
    				$this->db->set('u_address', $val['address']);
    			}
    			//if (isset($val[''])) {
    				//$this->db->set('u_profile_url', $val['']);
    			//}
    			if (isset($val['occupation'])) {
    				$this->db->set('u_occuption', $val['occupation']);
    			}
    			if (isset($val['emailVerified'])) {
    				$this->db->set('u_status', $val['emailVerified']);
    			}
    			if (isset($val['_created_at'])) {
    				if (isset($val['_created_at']->sec)) {
    					$tmp_date = date(DATE_ISO8601, $val['_created_at']->sec);
    					$this->db->set('u_date_creat_utc', $tmp_date);
    					$this->db->set('u_date_creat_unix', $val['_created_at']->sec);
    				}
    			}
    			if (isset($val['_updated_at'])) {
    				if (isset($val['_updated_at']->sec)) {
    					$tmp_date = date(DATE_ISO8601, $val['_updated_at']->sec);
    					$this->db->set('u_date_update_utc', $tmp_date);
    					$this->db->set('u_date_update_unix', $val['_updated_at']->sec);
    				}
    			}
    			if (isset($val['_session_token'])) {
    				$this->db->set('u_session', $val['_session_token']);
    			}
    			$status = $this->db->insert('vidol_user.User_profile_tbl');
    			if(empty($status)){
    				print_r($val);
    			}
    			//echo $this->db->last_query();
    			unset($val);
    		}
    	}
    	unset($user);
    	unset($user_count);
    	unset($limit);
    }
    
    public function add_user_one ()
    {
    	try {
    		// 變數
    		$this->load->helper('string');
    		$max = rand( 1, 2 );
			for ( $i = 1; $i <= $max; $i++ )
			{
	    		$user = random_string('alnum', 8);
	    		$user_mail = sprintf('%s@cron.ms01.com', $user);
	    		$data = array();
	    		$data ['username'] = $user;
	    		$data ['password'] = $user;
	    		$data ['email'] = $user_mail;
	    		$data ['contact_email'] = $user_mail;
	    		$data ['nick_name'] = $user;
				$ch = curl_init ( 'http://api-background.vidol.tv/v1/register' );
				curl_setopt_array ( $ch, array (
						CURLOPT_POST => TRUE,
						CURLOPT_RETURNTRANSFER => TRUE,
						CURLOPT_TIMEOUT_MS => 1000,
						CURLOPT_SSL_VERIFYPEER => FALSE,
						CURLOPT_HTTPHEADER => array (
								'Content-Type: application/json',
								'Authorization: Basic MWIyNjZkNjI0OWJiYjljM2M2ZDdkYjM0YWU1YzU5YzZhMzYyZmQxODgxOGJkMzM2NmNiYjY5YTUzOGYwZmU2NDpjODNlMDkxMmQ5MWI1NjAzM2RlNmFmODdjZDIxZGZkODk1NTBkNzA4M2Q3ODM0ZDIyMWVmNmNkZGM5ODg4ZjM2' 
						),
						CURLOPT_POSTFIELDS => json_encode ( $data ) 
				) );
				$response = curl_exec ( $ch );
				curl_close ( $ch );
				$data_json_token = json_decode ( $response );
				//$this->data_result['input'][] = $data;
				$this->data_result['info'][] = $data_json_token;
				$this->data_result['status'] = true;
			}
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function add_user_two ()
    {
    	try {
    		// 變數
    		$this->load->helper('string');
    		$max = rand( 3, 5 );
    		for ( $i = 1; $i <= $max; $i++ )
    		{
    			$user = random_string('alnum', 8);
    			$user_mail = sprintf('%s@cron.ms01.com', $user);
    			$data = array();
    			$data ['username'] = $user;
    			$data ['password'] = $user;
    			$data ['email'] = $user_mail;
    			$data ['contact_email'] = $user_mail;
    			$data ['nick_name'] = $user;
    			$ch = curl_init ( 'http://api-background.vidol.tv/v1/register' );
    			curl_setopt_array ( $ch, array (
    					CURLOPT_POST => TRUE,
    					CURLOPT_RETURNTRANSFER => TRUE,
    					CURLOPT_TIMEOUT_MS => 1000,
    					CURLOPT_SSL_VERIFYPEER => FALSE,
    					CURLOPT_HTTPHEADER => array (
    							'Content-Type: application/json',
    							'Authorization: Basic MWIyNjZkNjI0OWJiYjljM2M2ZDdkYjM0YWU1YzU5YzZhMzYyZmQxODgxOGJkMzM2NmNiYjY5YTUzOGYwZmU2NDpjODNlMDkxMmQ5MWI1NjAzM2RlNmFmODdjZDIxZGZkODk1NTBkNzA4M2Q3ODM0ZDIyMWVmNmNkZGM5ODg4ZjM2'
    					),
    					CURLOPT_POSTFIELDS => json_encode ( $data )
    			) );
    			$response = curl_exec ( $ch );
    			curl_close ( $ch );
    			$data_json_token = json_decode ( $response );
    			//$this->data_result['input'][] = $data;
    			$this->data_result['info'][] = $data_json_token;
    			$this->data_result['status'] = true;
    		}
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    public function add_fb_user ()
    {
    	try {
    		// 變數
    		$this->load->helper('string');
    		$max = rand( 2, 5 );
    		//$max = rand( 2, 5 );
    		for ( $i = 1; $i <= $max; $i++ )
    		{
    			$user = random_string('alnum', 8);
    			$user_id = random_string('numeric', 10);//100000877746162
    			$token = random_string('alnum', 24);
    			$user_mail = sprintf('%s@cron.ms01.com', $user);
    			$data = array();
				$data = array ();
				$data ['grant_type'] = 'password';
				$data ['facebook_token'] = 'EAAO2QXzAcIoBAF9fNkQDgi9LIaEMUfMP5yB0k0y0ayWKQF37rnPhUhm2GZBf1J5RNbxJqUsezhBHCRS2PQIz83LUKZApMk7RT9MMdqeZCm39tl1MWjY3e0VYShmOZA3gvJCbGl34VQotq3wXLYHtQAK2LeyQXWHoIvfK7Ns7XwZDZD';
				$data ['expiration_date'] = 4073;
				$data ['user_id'] = $user_id;
				$data ['nick_name'] = $user;
				$data ['contact_email'] = $user_mail;
    			$ch = curl_init ( 'http://api-background.vidol.tv/v1/oauth/token' );
    			curl_setopt_array ( $ch, array (
    					CURLOPT_POST => TRUE,
    					CURLOPT_RETURNTRANSFER => TRUE,
    					CURLOPT_TIMEOUT_MS => 1000,
    					CURLOPT_SSL_VERIFYPEER => FALSE,
    					CURLOPT_HTTPHEADER => array (
    							'Content-Type: application/json',
    							'Authorization: Basic MWIyNjZkNjI0OWJiYjljM2M2ZDdkYjM0YWU1YzU5YzZhMzYyZmQxODgxOGJkMzM2NmNiYjY5YTUzOGYwZmU2NDpjODNlMDkxMmQ5MWI1NjAzM2RlNmFmODdjZDIxZGZkODk1NTBkNzA4M2Q3ODM0ZDIyMWVmNmNkZGM5ODg4ZjM2'
    					),
    					CURLOPT_POSTFIELDS => json_encode ( $data )
    			) );
    			$response = curl_exec ( $ch );
    			curl_close ( $ch );
    			$data_json_token = json_decode ( $response );
    			$this->data_result['input'][] = $data;
    			$this->data_result['info'][] = $data_json_token;
    			$this->data_result['status'] = true;
    		}
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    //FBID重複刪除用
    public function user_fbid_del ()
    {
    	try {
    		//
    		$this->load->library('mongo_db');
    		//
    		$this->r_db = $this->load->database('vidol_user_read', TRUE);
    		$this->w_db = $this->load->database('vidol_user_write', TRUE);
    		//
   			$this->r_db->select('u_fb_id,count(u_fb_id) as fbc');
   			$this->r_db->group_by('u_fb_id');
   			$this->r_db->order_by('fbc', 'DESC');
   			$this->r_db->limit(100);
    		$query = $this->r_db->get('User_profile_tbl');
    		echo $this->r_db->last_query();
    		if ($query->num_rows () > 0) {
    			foreach ( $query->result () as $row ) {
    				print_r($row);
    				if($row->fbc > 1){
    					$users = $this->mongo_db->where('_auth_data_facebook.id', $row->u_fb_id)->select(array('_id', 'member_id', '_auth_data_facebook.id'))->get('_User');
    					print_r($users);
    					if(count($users) >= 2){
    						foreach ( $users as $key=>$user ) {
    							print_r($key);
    							print_r($user);
    							if($key != 0){
    								//刪除
    								$this->mongo_db->where('_id', $user['_id'])->delete('_User');
    								$this->w_db->delete('User_profile_tbl', array('u_mongo_id' => $user['_id']));
    							}
    							unset($key);
    							unset($user);
    						}
    					}
    					unset($users);
    				}
    				unset($row);
    			}
    		}
    		//SELECT `u_fb_id`,count(`u_fb_id`) as `fbc` FROM `User_profile_tbl` GROUP BY `u_fb_id` ORDER BY `fbc` DESC LIMIT 50
    		//SELECT `u_fb_id`,count(`u_fb_id`) as `fbc` FROM `User_profile_tbl` GROUP BY `u_fb_id` ORDER BY `fbc` DESC LIMIT 100
    		//
    		$this->r_db->close();
    		unset($this->r_db);
    		$this->w_db->close();
    		unset($this->w_db);
    		//
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }

    /**
     * 每10分鐘開通認證
     *
     * http://xxx.xxx.xxx/cron/users/registered_hour
     * # 分 時 日 月 週 指令
     * *10 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron users phone_email_verified
     */
    public function phone_email_verified ()
    {
    	try {
    		//load
    		$this->load->library('mongo_db');
    		$this->mongo_db->like('username' ,'@mobile.vidol.tv', 'i', TRUE, TRUE)->set(array('emailVerified'=>true, 'mobile'=>true))->update_all('_User');
    		//$users = $this->mongo_db->like('username' ,'@mobile.vidol.tv', 'i', TRUE, TRUE)->get('_User');
    		//if(count($users) > 0){
    			//foreach($users as $user){
    				//print_r($user);
    				//$this->mongo_db->where(array('_id'=>$user['_id']))->set(array('emailVerified'=>true, 'mobile'=>'aaaaa', 'mobile_phone'=>$mobile_phone))->update('_User');
    			//}
    		//}
    		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
}
