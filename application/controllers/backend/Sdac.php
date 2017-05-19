<?php
defined('BASEPATH') or exit('No direct script access allowed');

ini_set ( "display_errors", "On" ); // On, Off

class Sdac extends CI_Controller
{

    private $data_view;
    private $vote_arr = array(
    		'1'=>array(
    				'title'=>'最佳催淚獎',
    				'post'=>'/backend/sdac/awards/1',
    				'countent'=>array(
    						'1'=>'飛魚高校生',
    						'2'=>'狼王子',
    						'3'=>'我的極品男友',
    						'4'=>'大人情歌',
    						'5'=>'1989一念間',
    				),
    		),
    		'2'=>array(
    				'title'=>'最佳接吻獎',
    				'post'=>'/backend/sdac/awards/2',
    				'countent'=>array(
    						'1'=>'獨家保鑣',
    						'2'=>'飛魚高校生',
    						'3'=>'狼王子',
    						'4'=>'我的極品男友',
    						'5'=>'後菜鳥的燦爛時代',
    						'6'=>'大人情歌',
    						'7'=>'1989一念間',
    				),
    		),
    		'3'=>array(
    				'title'=>'最佳潛力新星獎',
    				'post'=>'/backend/sdac/awards/3',
    				'countent'=>array(
    						'1'=>'管麟 - 飛魚高校生',
    						'2'=>'張軒睿 - 狼王子',
    						'3'=>'連俞涵 - 我的極品男友',
    						'4'=>'張立昂 - 1989一念間',
    				),
    		),
    		'4'=>array(
    				'title'=>'最佳實力派演員獎',
    				'post'=>'/backend/sdac/awards/4',
    				'countent'=>array(
    						'1'=>'林煒 - 狼王子',
    						'2'=>'檢場 - 我的極品男友',
    						'3'=>'苗可麗 - 大人情歌',
    						'4'=>'梁家榕 - 1989一念間',
    						'5'=>'尹昭德 - 1989一念間',
    				),
    		),
    		'5'=>array(
    				'title'=>'最佳螢幕情侶獎',
    				'post'=>'/backend/sdac/awards/5',
    				'countent'=>array(
    						'1'=>'謝佳見、黃薇渟 - 獨家保鑣',
    						'2'=>'王傳一、魏蔓 - 飛魚高校生',
    						'3'=>'安心亞、張軒睿 - 狼王子',
    						'4'=>'林佑威、連俞涵 - 我的極品男友',
    						'5'=>'黃騰浩、林逸欣 - 我的極品男友',
    						'6'=>'簡宏霖、林可彤 - 我的極品男友',
    						'7'=>'炎亞綸、曾之喬 - 後菜鳥的燦爛時代',
    						'8'=>'Darren、李維維 - 大人情歌',
    						'9'=>'張立昂、邵雨薇 - 1989一念間',
    						'10'=>'蔡黃汝、孫沁岳 - 1989一念間',
    				),
    		),
    		'6'=>array(
    				'title'=>'最佳男演員獎',
    				'post'=>'/backend/sdac/awards/6',
    				'countent'=>array(
    						'1'=>'謝佳見 - 獨家保鑣',
    						'2'=>'王傳一 - 飛魚高校生',
    						'3'=>'張軒睿 - 狼王子',
    						'4'=>'林佑威 - 我的極品男友',
    						'5'=>'黃騰浩 - 我的極品男友',
    						'6'=>'炎亞綸 - 後菜鳥的燦爛時代',
    						'7'=>'Darren - 大人情歌',
    						'8'=>'張立昂 - 1989一念間',
    				),
    		),
    		'7'=>array(
    				'title'=>'最佳女演員獎',
    				'post'=>'/backend/sdac/awards/7',
    				'countent'=>array(
    						'1'=>'洪小鈴 - 獨家保鑣',
    						'2'=>'魏蔓 - 飛魚高校生',
    						'3'=>'安心亞 - 狼王子',
    						'4'=>'林逸欣 - 我的極品男友',
    						'5'=>'曾之喬 - 後菜鳥的燦爛時代',
    						'6'=>'李維維 - 大人情歌',
    						'7'=>'邵雨薇 - 1989一念間',
    						'8'=>'蔡黃汝 - 1989一念間',
    				),
    		),
    		'8'=>array(
    				'title'=>'觀眾票選最受歡迎戲劇節目獎',
    				'post'=>'/backend/sdac/awards/8',
    				'countent'=>array(
    						'1'=>'獨家保鑣',
    						'2'=>'飛魚高校生',
    						'3'=>'狼王子',
    						'4'=>'我的極品男友',
    						'5'=>'後菜鳥的燦爛時代',
    						'6'=>'1989一念間',
    						'7'=>'大人情歌',
    				),
    		),
    		'9'=>array(
    				'title'=>'觀眾票選最佳年度戲劇歌曲獎',
    				'post'=>'/backend/sdac/awards/9',
    				'countent'=>array(
    						'1'=>'周湯豪 帥到分手 - 飛魚高校生',
    						'2'=>'林宥嘉 熱血無賴 - 飛魚高校生',
    						'3'=>'畢書盡 愛戀魔法 - 狼王子',
    						'4'=>'吳建豪 屬於你和我之間的事 - 我的極品男友',
    						'5'=>'畢書盡 38 - 我的極品男友',
    						'6'=>'韋禮安 第一個想到你 - 後菜鳥的燦爛時代',
    						'7'=>'丁噹 想戀一個愛 - 大人情歌',
    						'8'=>'曹格 我們是朋友 - 1989一念間',
    						'9'=>'黃鴻升 兩個人 - 1989一念間',
    						'10'=>'陳零九 你他我 - 1989一念間',
    				),
    		),
    );
    private $afternoon_arr = array (
    		'1' => array (
    				'title' => '最佳男主角',
    				'post'=>'/backend/sdac/afternoon_awards/1',
    				'countent' => array (
    						'1' => '謝佳見',
    						'2' => '王傳一',
    						'3' => '張軒睿',
    						'4' => '張立昂',
    				)
    		),
    		'2' => array (
    				'title' => '最佳女主角',
    				'post'=>'/backend/sdac/afternoon_awards/2',
    				'countent' => array (
    						'1' => '魏蔓',
    						'2' => '安心亞',
    						'3' => '邵雨薇',
    						'4' => '蔡黃汝',
    				)
    		),
    );
	
    function __construct ()
    {
        parent::__construct();
        // 資料庫
        //$this->load->database();
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
        $this->lang->load('table_vidol', 'traditional-chinese');
        //$this->config->load('vidol');
        // 初始化
        $this->data_view = format_helper_backend_view_data('sdac_content');
        $this->data_view['system']['action'] = 'Sdac';
        $this->data_view['right_countent']['tags']['tag_2'] = array(
                'title' => '華劇大賞',
                'link' => '/backend/tools',
                'class' => 'fa-wheelchair'
        );
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }
	
    //華劇大賞
    public function vote ()
    {
            try {
            //if ($this->flexi_auth->is_privileged('Tools View')) {
            	// 寫log
            	//$this->fun->logs('觀看票數管理');
            	
            	$this->load->model ( 'postgre/vidol_production_model' );
            	$query = $this->vidol_production_model->get_votes();
            	if ($query->num_rows () > 0) {
            		foreach ( $query->result () as $row ) {
            			//sum
            			if(isset($this->data_view['right_countent']['view_data'][$row->category_no]['sum'])){
            				$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] += $row->tickets;
            			}else{
            				$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] = $row->tickets;
            			}
            			//title
            			$this->data_view['right_countent']['view_data'][$row->category_no]['title'] = $this->vote_arr[$row->category_no]['title'];
            			$this->data_view['right_countent']['view_data'][$row->category_no]['post'] = $this->vote_arr[$row->category_no]['post'];
            			//title
            			$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['title'] = $this->vote_arr[$row->category_no]['countent'][$row->video_id_no];
            			//
            			$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['tickets'] = $row->tickets;
            			//
            			$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['add_ticket'] = $row->tickets - $row->couns;
            			//print_r($row);
            		}
            	}
                // 變數
                $data_post = array();
                // 資料整理
                $this->data_view['right_countent']['view_path'] = 'AdminLTE/sdac/vote';
                $this->data_view['right_countent']['tags']['tag_3'] = array(
                        'title' => '票數管理',
                        'link' => '/backend/sdac/vote',
                        'class' => 'fa-qrcode'
                );
                // 套版
                $this->load->view('AdminLTE/include/html5', $this->data_view);
            //}
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    //華劇大賞-灌票
    public function awards($category_no){
    	try {
    		$this->load->model ( 'postgre/vidol_production_model' );
    		// 變數
    		$data_input = array ();
    		if(count($this->vote_arr[$category_no]['countent'])>0){
    			foreach ($this->vote_arr[$category_no]['countent'] as $video_id_no=>$val){
    				if(isset($_POST['video_id_' . $video_id_no])){
    					$data_input['sum'] = $_POST['sum'];
    					$data_input['video_id_' . $video_id_no] = $_POST['video_id_' . $video_id_no];
    					if(($data_input['video_id_' . $video_id_no] / $data_input['sum'] * 100) <= 5 || true){
   							$query = $this->vidol_production_model->update_votes_by_category_video($category_no, $video_id_no, $data_input['video_id_' . $video_id_no]);    						
    					}
    				}
    			}
    		}
    		//print_r($data_input);
    		redirect('/backend/sdac/vote');
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    //華劇大賞-明星午茶
    public function afternoon ()
    {
    	try {
    		//if ($this->flexi_auth->is_privileged('Tools View')) {
    		// 寫log
    		//$this->fun->logs('觀看票數管理');
    		 
    		$this->load->model ( 'postgre/vidol_production_model' );
    		$query = $this->vidol_production_model->get_afternoon();
    		if ($query->num_rows () > 0) {
    			foreach ( $query->result () as $row ) {
    				//sum
    				if(isset($this->data_view['right_countent']['view_data'][$row->category_no]['sum'])){
    					$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] += $row->tickets;
    				}else{
    					$this->data_view['right_countent']['view_data'][$row->category_no]['sum'] = $row->tickets;
    				}
    				//title
    				$this->data_view['right_countent']['view_data'][$row->category_no]['title'] = $this->afternoon_arr[$row->category_no]['title'];
    				$this->data_view['right_countent']['view_data'][$row->category_no]['post'] = $this->afternoon_arr[$row->category_no]['post'];
    				//title
    				$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['title'] = $this->afternoon_arr[$row->category_no]['countent'][$row->video_id_no];
    				//
    				$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['tickets'] = $row->tickets;
    				//
    				$this->data_view['right_countent']['view_data'][$row->category_no]['countent'][$row->video_id_no]['add_ticket'] = $row->tickets - $row->couns;
    				//print_r($row);
    			}
    		}
    		// 變數
    		$data_post = array();
    		// 資料整理
    		$this->data_view['right_countent']['view_path'] = 'AdminLTE/sdac/afternoon';
    		$this->data_view['right_countent']['tags']['tag_3'] = array(
    				'title' => '票數管理',
    				'link' => '/backend/sdac/afternoon',
    				'class' => 'fa-qrcode'
    		);
    		// 套版
    		$this->load->view('AdminLTE/include/html5', $this->data_view);
    		//}
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
    
    //華劇大賞-明星午茶-灌票
    public function afternoon_awards($category_no){
    	try {
    		$this->load->model ( 'postgre/vidol_production_model' );
    		// 變數
    		$data_input = array ();
    		if(count($this->afternoon_arr[$category_no]['countent'])>0){
    			foreach ($this->afternoon_arr[$category_no]['countent'] as $video_id_no=>$val){
    				if(isset($_POST['video_id_' . $video_id_no])){
    					$data_input['sum'] = $_POST['sum'];
    					$data_input['video_id_' . $video_id_no] = $_POST['video_id_' . $video_id_no];
    					if(($data_input['video_id_' . $video_id_no] / $data_input['sum'] * 100) <= 5 || true){
    						$query = $this->vidol_production_model->update_afternoon_by_category_video($category_no, $video_id_no, $data_input['video_id_' . $video_id_no]);
    					}
    				}
    			}
    		}
    		//print_r($data_input);
    		redirect('/backend/sdac/afternoon');
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
}
