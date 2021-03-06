<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 * 分 時 日 月 星期 要執行的指令
 */
class Votes extends CI_Controller {
	private $data_debug;
	private $data_result;
	function __construct() {
		parent::__construct ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function index() {
		show_404 ();
	}
	
	/**
	 * 玩很大前進校園投票-統計學校名稱
	 */
	public function mrplay_boards() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->load->model ( 'vidol_websocket/boards_model' );
			$this->load->model ( 'vidol_websocket/mrplay_model' );
			$cron = $this->mrplay_model->get_row_Mrplay_cronno ( 'cron_no' );
			//
			$this->data_result ['cron'] = $cron;
			if (empty ( $cron ) || empty ( $cron->cron_no )) {
				$cron_no = 0;
			} else {
				$cron_no = $cron->cron_no;
			}
			$query = $this->boards_model->get_Board_by_type_typeno ( 'b_no, b_message', 'episode', '17899', $cron_no, '500' );
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					$message = $row->b_message;
					preg_match_all ( '/[\x{4e00}-\x{9fff}]+/u', $message, $matches );
					$message = join ( '', $matches [0] );
					$message = rtrim ( $message );
					$message = str_replace ( array (
							'!',
							'"',
							'#',
							'$',
							'%',
							'&',
							'\'',
							'(',
							')',
							'*',
							'+',
							',',
							'-',
							'.',
							'/',
							':',
							';',
							'<',
							'=',
							'>',
							'?',
							'@',
							'[',
							'\\',
							']',
							'^',
							'_',
							'`',
							'{',
							'|',
							'}',
							'~',
							'；',
							'﹔',
							'︰',
							'﹕',
							'：',
							'，',
							'﹐',
							'、',
							'．',
							'﹒',
							'˙',
							'·',
							'。',
							'？',
							'！',
							'～',
							'‥',
							'‧',
							'′',
							'〃',
							'〝',
							'〞',
							'‵',
							'‘',
							'’',
							'『',
							'』',
							'「',
							'」',
							'“',
							'”',
							'…',
							'❞',
							'❝',
							'﹁',
							'﹂',
							'﹃',
							'﹄',
							' ',
							'\n',
							'\t',
							'\r',
							'\r\n',
							'\n\r',
							'《',
							'》',
							'（',
							'）',
							'【',
							'】',
							'❤',
							'★',
							'我的學校是',
							'因為' 
					), '', $message );
					$message = trim ( $message );
					$str_sec = explode ( '我', $message );
					$message = $str_sec ['0'];
					$message = str_replace ( PHP_EOL, '', $message );
					$message = rtrim ( $message );
					$strlen = mb_strlen ( $message, "utf-8" );
					if (! empty ( $message ) && $strlen >= 4 && $strlen <= 10) {
						$school = $message;
						$mrplay = $this->mrplay_model->get_row_Mrplay_by_school ( '*', $school );
						if (empty ( $mrplay )) {
							// 新增
							$data = array (
									'school' => $school,
									'count' => '1',
									'cron_no' => $row->b_no 
							);
							$this->mrplay_model->insert_Mrplay_for_data ( $data );
						} else {
							// 更新
							$data = array (
									'count' => $mrplay->count + 1,
									'cron_no' => $row->b_no 
							);
							$this->mrplay_model->update_Mrplay_for_data ( $mrplay->pk, $data );
						}
					}
				}
			}
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 統計投票數
	 */
	public function mrplay() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			$this->load->model ( 'vidol_old/vote_model' );
			// 變數
			$votes_arr = $this->config->item ( 'votes_mrplay_1' );
			$data_input = array ();
			$data_cache = array ();
			// 接收變數
			$data_input ['cache'] = $this->input->get ( 'cache' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			// 取得學校總數
			$query = $this->vidol_production_model->cron_mrplay_subtotal ();
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					// print_r($row);
					if (isset ( $votes_arr ['1'] ['countent'] [$row->school_code_no] )) {
						$count = $this->vote_model->get_count_vote_mrplay ( 1, $row->school_code_no );
						if (empty ( $count )) {
							// 新增
							$this->vote_model->insert_vote_mrplay ( 1, $row->school_code_no, $row->school_code, $votes_arr ['1'] ['countent'] [$row->school_code_no], $row->ticket_count, $row->ticket_sum );
						} else {
							// 更新
							$this->vote_model->update_vote_mrplay ( 1, $row->school_code_no, $row->ticket_count, $row->ticket_sum );
						}
						unset ( $count );
					}
					unset ( $row );
				}
			}
			unset ( $query );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// 玩很大進校園
	public function mrplay_list($date = '') {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			$this->load->model ( 'vidol_old/vote_model' );
			$this->load->model ( 'vidol/registered_model' );
			// 變數
			$data_date = array ();
			$data_input = array ();
			$data_insert = array ();
			
			// 接收變數
			$data_input ['cache'] = $this->input->get ( 'cache' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			// 當天
			$data_date ['now_time'] = strtotime ( $date . "-1 hour" );
			$data_date ['now'] = date ( "Y-m-d 00:00:00", $data_date ['now_time'] );
			$data_date ['now_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['now'] . "-8 hour" ) );
			// 前天
			$data_date ['yesterday_time'] = strtotime ( $data_date ['now'] . "-1 day" );
			$data_date ['yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['yesterday_time'] );
			$data_date ['yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['yesterday'] . "-8 hour" ) );
			// 大前天
			$data_date ['big_yesterday_time'] = strtotime ( $data_date ['now'] . "-2 day" );
			$data_date ['big_yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['big_yesterday_time'] );
			$data_date ['big_yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['big_yesterday'] . "-8 hour" ) );
			// 時間
			$data_insert ['v_date'] = $data_date ['yesterday'];
			// 新投票會員
			$new_user_yesterday = $this->vidol_production_model->cron_mrplay_distinct_votel_count ( $data_date ['yesterday_utc'] );
			$new_user_now = $this->vidol_production_model->cron_mrplay_distinct_votel_count ( $data_date ['now_utc'] );
			$data_insert ['v_new_vote'] = $new_user_now - $new_user_yesterday;
			// 日投票數(昨天零晨到今天零晨)
			$data_insert ['v_vote'] = $this->vidol_production_model->cron_mrplay_day_votel_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 不重複投票數
			$data_insert ['v_single_vote'] = $this->vidol_production_model->cron_mrplay_day_votel_single_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 累計投票數(今天零晨以前)
			$data_insert ['v_total_vote'] = $this->vidol_production_model->cron_mrplay_total_votel_count ( $data_date ['now_utc'] ) - 18;
			// 投票註冊數
			$data_insert ['v_vote_registered'] = $this->vidol_production_model->cron_mrplay_registered_votel_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// vidol註冊數(昨天零晨到今天零晨)
			$registered_count_sum = $this->registered_model->get_row_registered_count_sum_by_date_utc ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			$data_insert ['v_registered'] = $registered_count_sum->r_count;
			// 累計投票註冊數
			$data_insert ['v_total_registered '] = 0;
			// 註冊占比
			$data_insert ['v_proportion  '] = (empty ( $data_insert ['v_vote_registered'] ) || empty ( $data_insert ['v_registered'] )) ? 0 : $data_insert ['v_vote_registered'] / $data_insert ['v_registered'];
			//
			if (empty ( $date )) {
				$this->vote_model->insert_vote_mrplay_list ( $data_insert );
			}
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_date'] = $data_date;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_insert'] = $data_insert;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// 玩粉(沒上線)
	public function mrplay_gifts_list($date = '') {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			$this->load->model ( 'vidol_old/vote_model' );
			$this->load->model ( 'vidol/registered_model' );
			// 變數
			$data_date = array ();
			$data_input = array ();
			$data_insert = array ();
			
			// 接收變數
			$data_input ['cache'] = $this->input->get ( 'cache' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			// 當天
			$data_date ['now_time'] = strtotime ( $date . "-1 hour" );
			$data_date ['now'] = date ( "Y-m-d 00:00:00", $data_date ['now_time'] );
			$data_date ['now_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['now'] . "-8 hour" ) );
			// 前天
			$data_date ['yesterday_time'] = strtotime ( $data_date ['now'] . "-1 day" );
			$data_date ['yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['yesterday_time'] );
			$data_date ['yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['yesterday'] . "-8 hour" ) );
			// 大前天
			$data_date ['big_yesterday_time'] = strtotime ( $data_date ['now'] . "-2 day" );
			$data_date ['big_yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['big_yesterday_time'] );
			$data_date ['big_yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['big_yesterday'] . "-8 hour" ) );
			// 時間
			$data_insert ['v_date'] = $data_date ['yesterday'];
			// 新投票會員
			$new_user_yesterday = $this->vidol_production_model->cron_mrplay_gifts_distinct_votel_count ( $data_date ['yesterday_utc'] );
			$new_user_now = $this->vidol_production_model->cron_mrplay_gifts_distinct_votel_count ( $data_date ['now_utc'] );
			$data_insert ['v_new_vote'] = $new_user_now - $new_user_yesterday;
			// 日投票數(昨天零晨到今天零晨)
			$data_insert ['v_vote'] = $this->vidol_production_model->cron_mrplay_gifts_day_votel_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 不重複投票數
			$data_insert ['v_single_vote'] = $this->vidol_production_model->cron_mrplay_gifts_day_votel_single_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 累計投票數(今天零晨以前)
			$data_insert ['v_total_vote'] = $this->vidol_production_model->cron_mrplay_gifts_total_votel_count ( $data_date ['now_utc'] );
			// 投票註冊數
			$data_insert ['v_vote_registered'] = $this->vidol_production_model->cron_mrplay_gifts_registered_votel_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// vidol註冊數(昨天零晨到今天零晨)
			$registered_count_sum = $this->registered_model->get_row_registered_count_sum_by_date_utc ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			$data_insert ['v_registered'] = $registered_count_sum->r_count;
			// 累計投票註冊數
			$data_insert ['v_total_registered '] = 0;
			// 註冊占比
			$data_insert ['v_proportion  '] = (empty ( $data_insert ['v_vote_registered'] ) || empty ( $data_insert ['v_registered'] )) ? 0 : $data_insert ['v_vote_registered'] / $data_insert ['v_registered'];
			//
			// if(empty($date)){
			$this->vote_model->insert_vote_mrplay_gifts_list ( $data_insert );
			// }
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_date'] = $data_date;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_insert'] = $data_insert;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// 愛上哥們贈東京票
	public function bromance_meetings_list($date = '') {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			$this->load->model ( 'vidol_old/vote_model' );
			$this->load->model ( 'vidol/registered_model' );
			// 變數
			$data_date = array ();
			$data_input = array ();
			$data_insert = array ();
			
			// 接收變數
			$data_input ['cache'] = $this->input->get ( 'cache' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			// 當天
			$data_date ['now_time'] = strtotime ( $date . "-1 hour" );
			$data_date ['now'] = date ( "Y-m-d 00:00:00", $data_date ['now_time'] );
			$data_date ['now_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['now'] . "-8 hour" ) );
			// 前天
			$data_date ['yesterday_time'] = strtotime ( $data_date ['now'] . "-1 day" );
			$data_date ['yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['yesterday_time'] );
			$data_date ['yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['yesterday'] . "-8 hour" ) );
			// 大前天
			$data_date ['big_yesterday_time'] = strtotime ( $data_date ['now'] . "-2 day" );
			$data_date ['big_yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['big_yesterday_time'] );
			$data_date ['big_yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['big_yesterday'] . "-8 hour" ) );
			// 時間
			$data_insert ['v_date'] = $data_date ['yesterday'];
			// 新投票會員
			$new_user_yesterday = $this->vidol_production_model->cron_bromance_meetings_distinct_votel_count ( $data_date ['yesterday_utc'] );
			$new_user_now = $this->vidol_production_model->cron_bromance_meetings_distinct_votel_count ( $data_date ['now_utc'] );
			$data_insert ['v_new_vote'] = $new_user_now - $new_user_yesterday;
			// 日投票數(昨天零晨到今天零晨)
			$data_insert ['v_vote'] = $this->vidol_production_model->cron_bromance_meetings_day_votel_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 不重複投票數
			$data_insert ['v_single_vote'] = $this->vidol_production_model->cron_bromance_meetings_day_votel_single_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 累計投票數(今天零晨以前)
			$data_insert ['v_total_vote'] = $this->vidol_production_model->cron_bromance_meetings_total_votel_count ( $data_date ['now_utc'] );
			// 投票註冊數
			$data_insert ['v_vote_registered'] = $this->vidol_production_model->cron_bromance_meetings_registered_votel_count ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// vidol註冊數(昨天零晨到今天零晨)
			$registered_count_sum = $this->registered_model->get_row_registered_count_sum_by_date_utc ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			$data_insert ['v_registered'] = $registered_count_sum->r_count;
			// 累計投票註冊數
			$data_insert ['v_total_registered '] = 0;
			// 註冊占比
			$data_insert ['v_proportion  '] = (empty ( $data_insert ['v_vote_registered'] ) || empty ( $data_insert ['v_registered'] )) ? 0 : $data_insert ['v_vote_registered'] / $data_insert ['v_registered'];
			// 寫資料
			$count = $this->vote_model->get_count_vote_tables_list ( 'vote_bromance_meetings_list_tbl', 'v_date', $data_date ['yesterday'] );
			if (empty ( $count )) {
				$this->vote_model->insert_vote_tables_list ( 'vote_bromance_meetings_list_tbl', $data_insert );
			} else {
				$this->vote_model->update_vote_tables_list ( 'vote_bromance_meetings_list_tbl', 'v_date', $data_date ['yesterday'], $data_insert );
			}
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_date'] = $data_date;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_insert'] = $data_insert;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// OB嚴選送iphone8
	public function ob_iphone8_list($date = '') {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'votes' );
			$this->load->model ( 'postgre/vidol_production_model' );
			$this->load->model ( 'vidol_old/vote_model' );
			$this->load->model ( 'vidol/registered_model' );
			// 變數
			$data_date = array ();
			$data_input = array ();
			$data_insert = array ();
			
			// 接收變數
			$data_input ['cache'] = $this->input->get ( 'cache' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			// 當天
			$data_date ['now_time'] = strtotime ( $date . "-1 hour" );
			$data_date ['now'] = date ( "Y-m-d 00:00:00", $data_date ['now_time'] );
			$data_date ['now_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['now'] . "-8 hour" ) );
			// 前天
			$data_date ['yesterday_time'] = strtotime ( $data_date ['now'] . "-1 day" );
			$data_date ['yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['yesterday_time'] );
			$data_date ['yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['yesterday'] . "-8 hour" ) );
			// 大前天
			$data_date ['big_yesterday_time'] = strtotime ( $data_date ['now'] . "-2 day" );
			$data_date ['big_yesterday'] = date ( "Y-m-d 00:00:00", $data_date ['big_yesterday_time'] );
			$data_date ['big_yesterday_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['big_yesterday'] . "-8 hour" ) );
			// 時間
			$data_insert ['v_date'] = $data_date ['yesterday'];
			// 新投票會員
			$new_user_yesterday = $this->vidol_production_model->cron_tables_distinct_votel_count ( 'ob_iphones', $data_date ['yesterday_utc'] );
			$new_user_now = $this->vidol_production_model->cron_tables_distinct_votel_count ( 'ob_iphones', $data_date ['now_utc'] );
			$data_insert ['v_new_vote'] = $new_user_now - $new_user_yesterday;
			// 日投票數(昨天零晨到今天零晨)
			$data_insert ['v_vote'] = $this->vidol_production_model->cron_tables_day_votel_count ( 'ob_iphones', $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 不重複投票數
			$data_insert ['v_single_vote'] = $this->vidol_production_model->cron_tables_day_votel_single_count ( 'ob_iphones', $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// 累計投票數(今天零晨以前)
			$data_insert ['v_total_vote'] = $this->vidol_production_model->cron_tables_total_votel_count ( 'ob_iphones', '2017-06-08 00:00:00', $data_date ['now_utc'] );
			// 投票註冊數
			$data_insert ['v_vote_registered'] = $this->vidol_production_model->cron_tables_registered_votel_count ( 'ob_iphones', $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			// vidol註冊數(昨天零晨到今天零晨)
			$registered_count_sum = $this->registered_model->get_row_registered_count_sum_by_date_utc ( $data_date ['yesterday_utc'], $data_date ['now_utc'] );
			$data_insert ['v_registered'] = $registered_count_sum->r_count;
			// 累計投票註冊數
			$data_insert ['v_total_registered '] = $this->vidol_production_model->cron_tables_total_votel_count ( 'ob_iphones', '2017-06-20 04:00:00', $data_date ['now_utc'] );
			// 註冊占比
			$data_insert ['v_proportion  '] = (empty ( $data_insert ['v_vote_registered'] ) || empty ( $data_insert ['v_registered'] )) ? 0 : $data_insert ['v_vote_registered'] / $data_insert ['v_registered'];
			// 寫資料
			$count = $this->vote_model->get_count_vote_tables_list ( 'vote_ob_iphone8_list_tbl', 'v_date', $data_date ['yesterday'] );
			if (empty ( $count )) {
				$this->vote_model->insert_vote_tables_list ( 'vote_ob_iphone8_list_tbl', $data_insert );
			} else {
				$this->vote_model->update_vote_tables_list ( 'vote_ob_iphone8_list_tbl', 'v_date', $data_date ['yesterday'], $data_insert );
			}
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_date'] = $data_date;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_insert'] = $data_insert;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	/**
	 * 投票系統統計報表,單選
	 * @param unknown $config_id
	 * @param string $date
	 */
	public function config_report($config_id, $date = '') {
		try {
			//
			$this->load->model ( 'vidol/registered_model' );
			$this->load->model ( 'vidol_event/event_vote_select_model' );
			$this->load->model ( 'vidol_event/event_vote_report_model' );
			$data_insert = array ();
			$date_arr = array ();
			$date_arr ['start_time'] = strtotime ( $date . ' -1 day' );
			$date_arr ['start'] = date ( "Y-m-d 00:00:00", $date_arr ['start_time'] );
			$date_arr ['end_time'] = strtotime ( $date_arr ['start'] . ' +1 day' );
			$date_arr ['end'] = date ( "Y-m-d 00:00:00", $date_arr ['end_time'] );
			$this->data_result ['date_arr'] = $date_arr;
			// 設定檔id
			$data_insert ['config_id'] = $config_id;
			// 時間(+8)
			$data_insert ['date_at'] = date ( "Y-m-d", $date_arr ['start_time'] );
			// 當日投票數(昨天零晨到今天零晨)
			$data_insert ['vote'] = $this->event_vote_select_model->get_vote_count_by_configid_date ( $config_id, $date_arr ['start'], $date_arr ['end'] );
			// 第一次投票會員
			$data_insert ['new_vote'] = $this->event_vote_select_model->get_new_vote_count_by_configid_date ( $config_id, $date_arr ['start'], $date_arr ['end'] );
			// 累計不重複投票數(全部GROUP BY後再查昨天零晨到今天零晨只有一筆的)
			$data_insert ['single_vote'] = $this->event_vote_select_model->get_single_vote_count_by_configid_date ( $config_id, $date_arr ['start'], $date_arr ['end'] );
			// 累計投票數(到今天零晨)
			$data_insert ['total_vote'] = $this->event_vote_select_model->get_total_vote_count_by_configid_date ( $config_id, $date_arr ['start'], $date_arr ['end'] );
			// 當日投票註冊數(昨天零晨到今天零晨)
			$data_insert ['registered'] = $this->event_vote_select_model->get_registered_count_by_configid_date ( $config_id, $date_arr ['start'], $date_arr ['end'] );
			// 累計投票註冊數
			$data_insert ['total_registered'] = $this->event_vote_select_model->get_total_registered_count_by_configid_date ( $config_id, $date_arr ['start'], $date_arr ['end'] );
			// vidol當日總註冊數
			$sum = $this->registered_model->get_registered_sum_by_day ( $data_insert ['date_at'] );
			$data_insert ['vidol_registered'] = $sum->r_count;
			// 投票註冊/投票數占比%
			if (empty ( $data_insert ['vote'] ) || empty ( $data_insert ['registered'] )) {
				$data_insert ['proportion'] = 0;
			} else {
				$data_insert ['proportion'] = $data_insert ['registered'] / $data_insert ['vote'] * 100;
			}
			// 投票註冊/當日總註冊數占比%
			if (empty ( $data_insert ['vote'] ) || empty ( $data_insert ['vidol_registered'] )) {
				$data_insert ['vidol_proportion'] = 0;
			} else {
				$data_insert ['vidol_proportion'] = $data_insert ['registered'] / $data_insert ['vidol_registered'] * 100;
			}
			//
			$count = $this->event_vote_report_model->get_count_by_configid_dateat ( $data_insert ['config_id'], $data_insert ['date_at'] );
			if (empty ( $count )) {
				$this->event_vote_report_model->insert_data ( $data_insert );
			} else {
				$this->event_vote_report_model->update_data_by_configid_dateat ( $data_insert ['config_id'], $data_insert ['date_at'], $data_insert );
			}
			$this->data_result ['data_insert'] = $data_insert;
			// 輸出
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
