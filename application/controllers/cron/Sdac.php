<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Sdac extends CI_Controller {
	private $data_result;
	private $vote_arr = array (
			'1' => array (
					'title' => '最佳催淚獎',
					'countent' => array (
							'1' => '飛魚高校生',
							'2' => '狼王子',
							'3' => '我的極品男友',
							'4' => '大人情歌',
							'5' => '1989一念間' 
					) 
			),
			'2' => array (
					'title' => '最佳接吻獎',
					'countent' => array (
							'1' => '獨家保鑣',
							'2' => '飛魚高校生',
							'3' => '狼王子',
							'4' => '我的極品男友',
							'5' => '後菜鳥的燦爛時代',
							'6' => '大人情歌',
							'7' => '1989一念間' 
					) 
			),
			'3' => array (
					'title' => '最佳潛力新星獎',
					'countent' => array (
							'1' => '管麟 - 飛魚高校生',
							'2' => '張軒睿 - 狼王子',
							'3' => '連俞涵 - 我的極品男友',
							'4' => '張立昂 - 1989一念間' 
					) 
			),
			'4' => array (
					'title' => '最佳實力派演員獎',
					'countent' => array (
							'1' => '林煒 - 狼王子',
							'2' => '檢場 - 我的極品男友',
							'3' => '苗可麗 - 大人情歌',
							'4' => '梁家榕 - 1989一念間',
							'5' => '尹昭德 - 1989一念間' 
					) 
			),
			'5' => array (
					'title' => '最佳螢幕情侶獎',
					'countent' => array (
							'1' => '謝佳見、黃薇渟 - 獨家保鑣',
							'2' => '王傳一、魏蔓 - 飛魚高校生',
							'3' => '安心亞、張軒睿 - 狼王子',
							'4' => '林佑威、連俞涵 - 我的極品男友',
							'5' => '黃騰浩、林逸欣 - 我的極品男友',
							'6' => '簡宏霖、林可彤 - 我的極品男友',
							'7' => '炎亞綸、曾之喬 - 後菜鳥的燦爛時代',
							'8' => 'Darren、李維維 - 大人情歌',
							'9' => '張立昂、邵雨薇 - 1989一念間',
							'10' => '蔡黃汝、孫沁岳 - 1989一念間' 
					) 
			),
			'6' => array (
					'title' => '最佳男演員獎',
					'countent' => array (
							'1' => '謝佳見 - 獨家保鑣',
							'2' => '王傳一 - 飛魚高校生',
							'3' => '張軒睿 - 狼王子',
							'4' => '林佑威 - 我的極品男友',
							'5' => '黃騰浩 - 我的極品男友',
							'6' => '炎亞綸 - 後菜鳥的燦爛時代',
							'7' => 'Darren - 大人情歌',
							'8' => '張立昂 - 1989一念間' 
					) 
			),
			'7' => array (
					'title' => '最佳女演員獎',
					'countent' => array (
							'1' => '洪小鈴 - 獨家保鑣',
							'2' => '魏蔓 - 飛魚高校生',
							'3' => '安心亞 - 狼王子',
							'4' => '林逸欣 - 我的極品男友',
							'5' => '曾之喬 - 後菜鳥的燦爛時代',
							'6' => '李維維 - 大人情歌',
							'7' => '邵雨薇 - 1989一念間',
							'8' => '蔡黃汝 - 1989一念間' 
					) 
			),
			'8' => array (
					'title' => '觀眾票選最受歡迎戲劇節目獎',
					'countent' => array (
							'1' => '獨家保鑣',
							'2' => '飛魚高校生',
							'3' => '狼王子',
							'4' => '我的極品男友',
							'5' => '後菜鳥的燦爛時代',
							'6' => '1989一念間',
							'7' => '大人情歌' 
					) 
			),
			'9' => array (
					'title' => '觀眾票選最佳年度戲劇歌曲獎',
					'countent' => array (
							'1' => '周湯豪 帥到分手 - 飛魚高校生',
							'2' => '林宥嘉 熱血無賴 - 飛魚高校生',
							'3' => '畢書盡 愛戀魔法 - 狼王子',
							'4' => '吳建豪 屬於你和我之間的事 - 我的極品男友',
							'5' => '畢書盡 38 - 我的極品男友',
							'6' => '韋禮安 第一個想到你 - 後菜鳥的燦爛時代',
							'7' => '丁噹 想戀一個愛 - 大人情歌',
							'8' => '曹格 我們是朋友 - 1989一念間',
							'9' => '黃鴻升 兩個人 - 1989一念間',
							'10' => '陳零九 你他我 - 1989一念間' 
					) 
			) 
	);
	private $afternoon_arr = array (
			'1' => array (
					'title' => '最佳男主角',
					'countent' => array (
							'1' => '謝佳見',
							'2' => '王傳一',
							'3' => '張軒睿',
							'4' => '張立昂',
					)
			),
			'2' => array (
					'title' => '最佳女主角',
					'countent' => array (
							'1' => '魏蔓',
							'2' => '安心亞',
							'3' => '邵雨薇',
							'4' => '蔡黃汝',
					)
			),
	);
	function __construct() {
		parent::__construct ();
	}
	public function index() {
		show_404 ();
	}
	//華劇大賞
	//./30 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron sdac subtotal
	public function subtotal() {
		$this->load->model ( 'postgre/vidol_production_model' );
		$this->load->model ( 'vidol_old/vote_model' );
		$query = $this->vidol_production_model->cron_subtotal();
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				//print_r($row);
				//$this->vote_model->insert_vote($row->category_no, $this->vote_arr[$row->category_no]['title'], $row->video_id_no, $this->vote_arr[$row->category_no]['countent'][$row->video_id_no] ,$row->ticket_count, $row->ticket_sum);
				$this->data_result[] = $this->vote_model->update_vote ($row->category_no, $row->video_id_no, $row->ticket_count, $row->ticket_sum);
			}
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($this->data_result));
	}
	
	//華劇大賞,統計表用(將會員加入時間回寫)
	public function add_join_at(){
		$this->load->model ( 'postgre/vidol_production_model' );
		$this->load->model('mongo_model');
		$query = $this->vidol_production_model->cron_get_voters(1000);
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				//print_r($row);
				$user = $this->mongo_model->get_user_by_memberid($row->member_id);
				//print_r($user);
				if(isset($user['_created_at'])){
					//$tmp_date = date(DATE_ISO8601, $user['_created_at']->sec);
					$this->data_result[] = $this->vidol_production_model->cron_update_voters($row->id, $user['_created_at']->sec);
				}
			}
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($this->data_result));
	}
	
	//明星午茶
	//./30 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron sdac afternoon_subtotal
	public function afternoon_subtotal() {
		$this->load->model ( 'postgre/vidol_production_model' );
		$this->load->model ( 'vidol_old/vote_model' );
		$query = $this->vidol_production_model->cron_afternoon_subtotal();
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				//print_r($row);
				//$this->vote_model->insert_afternoon($row->category_no, $this->afternoon_arr[$row->category_no]['title'], $row->video_id_no, $this->afternoon_arr[$row->category_no]['countent'][$row->video_id_no] ,$row->ticket_count, $row->ticket_sum);
				$this->data_result[] = $this->vote_model->update_afternoon ($row->category_no, $row->video_id_no, $row->ticket_count, $row->ticket_sum);
			}
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($this->data_result));
	}
	
	//明星午茶,統計表用(將會員加入時間回寫)
	public function afternoon_add_join_at(){
		$this->load->model ( 'postgre/vidol_production_model' );
		$this->load->model('mongo_model');
		$query = $this->vidol_production_model->cron_get_fvoters(1000);
		if ($query->num_rows () > 0) {
			foreach ( $query->result () as $row ) {
				//print_r($row);
				$user = $this->mongo_model->get_user_by_memberid($row->member_id);
				//print_r($user);
				if(isset($user['_created_at'])){
					//$tmp_date = date(DATE_ISO8601, $user['_created_at']->sec);
					$this->data_result[] = $this->vidol_production_model->cron_update_fvoters($row->id, $user['_created_at']->sec);
				}
			}
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($this->data_result));
	}
}
