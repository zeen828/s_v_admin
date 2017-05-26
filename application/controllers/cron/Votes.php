<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
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
					//print_r($row);
					if(isset($votes_arr['1']['countent'][$row->school_code_no])){
						$count = $this->vote_model->get_count_vote_mrplay(1, $row->school_code_no);
						if(empty($count)){
							//新增
							$this->vote_model->insert_vote_mrplay(1, $row->school_code_no, $row->school_code, $votes_arr['1']['countent'][$row->school_code_no], $row->ticket_count, $row->ticket_sum);
						}else{
							//更新
							$this->vote_model->update_vote_mrplay(1, $row->school_code_no, $row->ticket_count, $row->ticket_sum);
						}
						unset($count);
					}
					unset($row);
				}
			}
			unset($query);
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
	
	public function mrplay_list() {
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
}
