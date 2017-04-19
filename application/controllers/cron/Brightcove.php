<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Brightcove extends CI_Controller
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
        $this->load->library('vidol/brightcove_api');
        $this->load->model('brightcove_model');
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
     * 影片觀看數據
     *
     * @param string $date
     *            指定時間
     *            http://xxx.xxx.xxx/cron/brightcove/report/20160607
     *            http://xxx.xxx.xxx/cron/brightcove/report
     *            # 分 時 日 月 週 指令
     *            01 * * * * php /var/www/codeigniter/3.0.6/admin/index.php cron
     *            brightcove report
     */
    public function report ($date = '')
    {
        try {
            // 變數
            $data_brightcove = array(
                    'date' => '',
                    'account' => '0',
                    'city' => '0',
                    'country' => '0',
                    'region' => '0',
                    'device_os' => '0',
                    'device_type' => '0'
            );
            // 時間
            // $start_time = strtotime($date . "-1 day");
            // $data_brightcove['date'] = date("Y-m-d", $start_time);
            $data_brightcove['date'] = date("Y-m-d");
            // 查詢
            $this->brightcove_api->get_token();
            // $this->brightcove_api->set_time('2016-07-01', '2016-07-01');
            $this->brightcove_api->set_time($data_brightcove['date'], $data_brightcove['date']);
            // $this->brightcove_api->set_limit('10');
            $api_account = $this->brightcove_api->analytics_report('account');
            if (isset($api_account['item_count']) && $api_account['item_count'] > 0) {
                $data_brightcove['account'] = $api_account['summary']['video_view'];
            }
            $api_city = $this->brightcove_api->analytics_report('city');
            if (isset($api_city['item_count'])) {
                $data_brightcove['city'] = $api_city['item_count'];
            }
            $api_country = $this->brightcove_api->analytics_report('country');
            if (isset($api_country['item_count'])) {
                $data_brightcove['country'] = $api_country['item_count'];
            }
            $api_region = $this->brightcove_api->analytics_report('region');
            if (isset($api_region['item_count'])) {
                $data_brightcove['region'] = $api_region['item_count'];
            }
            $api_device_os = $this->brightcove_api->analytics_report('device_os');
            if (isset($api_device_os['item_count'])) {
                $data_brightcove['device_os'] = $api_device_os['item_count'];
            }
            $api_device_type = $this->brightcove_api->analytics_report('device_type');
            if (isset($api_device_type['item_count'])) {
                $data_brightcove['device_type'] = $api_device_type['item_count'];
            }
            // db
            if ($this->brightcove_model->insert_report_data($data_brightcove)) {
                // log
                trigger_error(sprintf("統計Brightcove數據[%s].", $data_brightcove['date']), 1024);
                $this->data_result['status'] = true;
            }
            // 輸出
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->data_result));
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function report_test ($start = '', $end = '')
    {
        try {
            $st = (empty($start)) ? 10 : $start;
            $en = (empty($end)) ? 0 : $end;
            $this->brightcove_api->get_token();
            for ($i = $st; $i >= $en; $i --) {
                // 變數
                $data_brightcove = array(
                        'date' => '',
                        'account' => '0',
                        'city' => '0',
                        'country' => '0',
                        'region' => '0',
                        'device_os' => '0',
                        'device_type' => '0'
                );
                // 時間
                $start_time = strtotime("-" . $i . " day");
                // $start_time = strtotime($date . "-1 day");
                $data_brightcove['date'] = date("Y-m-d", $start_time);
                // 查詢
                // $this->brightcove_api->get_token();
                // $this->brightcove_api->set_time('2016-07-01', '2016-07-01');
                $this->brightcove_api->set_time($data_brightcove['date'], $data_brightcove['date']);
                // $this->brightcove_api->set_limit('10');
                $api_account = $this->brightcove_api->analytics_report('account');
                if (isset($api_account['item_count']) && $api_account['item_count'] > 0) {
                    $data_brightcove['account'] = $api_account['summary']['video_view'];
                }
                $api_city = $this->brightcove_api->analytics_report('city');
                if (isset($api_city['item_count'])) {
                    $data_brightcove['city'] = $api_city['item_count'];
                }
                $api_country = $this->brightcove_api->analytics_report('country');
                if (isset($api_country['item_count'])) {
                    $data_brightcove['country'] = $api_country['item_count'];
                }
                $api_region = $this->brightcove_api->analytics_report('region');
                if (isset($api_region['item_count'])) {
                    $data_brightcove['region'] = $api_region['item_count'];
                }
                $api_device_os = $this->brightcove_api->analytics_report('device_os');
                if (isset($api_device_os['item_count'])) {
                    $data_brightcove['device_os'] = $api_device_os['item_count'];
                }
                $api_device_type = $this->brightcove_api->analytics_report('device_type');
                if (isset($api_device_type['item_count'])) {
                    $data_brightcove['device_type'] = $api_device_type['item_count'];
                }
                // db
                if ($this->brightcove_model->insert_report_data($data_brightcove)) {
                    // log
                    // trigger_error(sprintf("統計Brightcove數據[%s].",
                    // $data_brightcove['date']), 1024);
                    $this->data_result['status'] = true;
                }
            }
            // 輸出
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->data_result));
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /**
     * 影片觀看數據
     *
     * @param string $date
     *            指定時間
     *            http://xxx.xxx.xxx/cron/brightcove/report_type/20160607
     *            http://xxx.xxx.xxx/cron/brightcove/report_type
     *            # 分 時 日 月 週 指令
     *            10 03 * * * php /var/www/codeigniter/3.0.6/admin/index.php
     *            cron brightcove report_type
     */
    public function report_type ($date = '')
    {
        try {
            // 時間
            $start_time = strtotime($date . "-1 day");
            $date = date("Y-m-d", $start_time);
            // 取得筆數
            $data_brightcove = $this->brightcove_model->get_report($date, $date);
            // print_r($data_brightcove);
            if (count($data_brightcove) > 0) {
                $this->brightcove_api->get_token();
                $this->brightcove_api->set_time($date, $date);
                foreach ($data_brightcove as $key => $val) {
                    switch ($key) {
                        case 'city':
                            if (! empty($val)) {
                                $this->brightcove_api->set_limit($val);
                                $data_api = $this->brightcove_api->analytics_report($key);
                                $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                            }
                            break;
                        case 'country':
                            if (! empty($val)) {
                                $this->brightcove_api->set_limit($val);
                                $data_api = $this->brightcove_api->analytics_report($key);
                                $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                            }
                            break;
                        case 'region':
                            if (! empty($val)) {
                                $this->brightcove_api->set_limit($val);
                                $data_api = $this->brightcove_api->analytics_report($key);
                                $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                            }
                            break;
                        case 'device_os':
                            if (! empty($val)) {
                                $this->brightcove_api->set_limit($val);
                                $data_api = $this->brightcove_api->analytics_report($key);
                                $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                            }
                            break;
                        case 'device_type':
                            if (! empty($val)) {
                                $this->brightcove_api->set_limit($val);
                                $data_api = $this->brightcove_api->analytics_report($key);
                                $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                            }
                            break;
                        default:
                            break;
                    }
                }
                // log
                trigger_error(sprintf("統計Brightcove Report數據[%s].", $date), 1024);
                $this->data_result['status'] = true;
            }
            // 輸出
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->data_result));
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function report_type_test ($start = '', $end = '')
    {
        try {
            $st = (empty($start)) ? 10 : $start;
            $en = (empty($end)) ? 0 : $end;
            $this->brightcove_api->get_token();
            for ($i = $st; $i >= $en; $i --) {
                // 時間
                $start_time = strtotime("-" . $i . " day");
                // $start_time = strtotime($date . "-1 day");
                $date = date("Y-m-d", $start_time);
                // 取得筆數
                $data_brightcove = $this->brightcove_model->get_report($date, $date);
                // print_r($data_brightcove);
                if (count($data_brightcove) > 0) {
                    // $this->brightcove_api->get_token();
                    $this->brightcove_api->set_time($date, $date);
                    foreach ($data_brightcove as $key => $val) {
                        switch ($key) {
                            case 'city':
                                if (! empty($val)) {
                                    $this->brightcove_api->set_limit($val);
                                    $data_api = $this->brightcove_api->analytics_report($key);
                                    $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                                }
                                break;
                            case 'country':
                                if (! empty($val)) {
                                    $this->brightcove_api->set_limit($val);
                                    $data_api = $this->brightcove_api->analytics_report($key);
                                    $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                                }
                                break;
                            case 'region':
                                if (! empty($val)) {
                                    $this->brightcove_api->set_limit($val);
                                    $data_api = $this->brightcove_api->analytics_report($key);
                                    $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                                }
                                break;
                            case 'device_os':
                                if (! empty($val)) {
                                    $this->brightcove_api->set_limit($val);
                                    $data_api = $this->brightcove_api->analytics_report($key);
                                    $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                                }
                                break;
                            case 'device_type':
                                if (! empty($val)) {
                                    $this->brightcove_api->set_limit($val);
                                    $data_api = $this->brightcove_api->analytics_report($key);
                                    $this->brightcove_model->insert_report_type_data($date, $key, $data_api);
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
                // log
                // trigger_error(sprintf("統計Brightcove Report數據[%s].", $date),
                // 1024);
                $this->data_result['status'] = true;
            }
            // 輸出
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->data_result));
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
