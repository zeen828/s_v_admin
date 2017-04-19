<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Episodes extends CI_Controller
{
	
    private $data_result;
	
    function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 權限
        $this->auth = new stdClass();
        $this->load->library('flexi_auth');
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
    
    /**
     * 串流資料取得
     * http://xxx.xxx.xxx/cron/episodes/load
     * # 分 時 日 月 週 指令
     * 0 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron episodes load
     */
    public function load ()
    {
    	try {
    		// 引用
    		$this->load->model('vidol_old/episodes_model');
    		$this->load->model('postgre/postgre_episodes_model');
    		// 變數
    		$data_tmpe = array();
    		//取得目前爬取到的筆數
    		$cron_no = $this->episodes_model->get_episodes_max_type_no ();
    		//var_dump($cron_no);
    		//
    		//var_dump(is_numeric($cron_no));
    		if(is_numeric($cron_no)){
				$query = $this->postgre_episodes_model->get_episodes ($cron_no, 50);
    			 if ($query->num_rows() > 0){
	    			 foreach ($query->result() as $row){
		    			 //print_r($row);
		    			 if($row->free == 'f'){
			    			 $row->free = false;
		    			 }else{
			    			 $row->free = true;
		    			 }
		    			 $this->episodes_model->insert_episodes ('episode', $row->id, $row->programme_id, $row->episode_number, $row->free, $row->created_at);
		    			 //$this->episodes_model->insert_episodes ('episode', 999, 888, 777, false);
	    			 }
    			 }
    		}
    		$this->data_result['status'] = true;
    		$this->output->set_content_type('application/json');
    		$this->output->set_output(json_encode($this->data_result));
    	} catch (Exception $e) {
    		show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
    	}
    }
}
