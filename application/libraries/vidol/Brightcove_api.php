<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brightcove_api
{

    private $CI;

    private $Client_ID;

    private $Client_Secret;

    private $accounts;

    private $access_token;

    private $token_type;

    private $expires_in;

    private $time;

    private $limit;

    private $data_result;

    public function __construct ()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('vidol');
        $this->set_status('production');
    }

    public function debug ()
    {
        echo "<br/>\n", "Client_ID : ", $this->Client_ID, "<br/>\n";
        echo "<br/>\n", "Client_Secret : ", $this->Client_Secret, "<br/>\n";
        echo "<br/>\n", "accounts : ", $this->accounts, "<br/>\n";
        echo "<br/>\n", "access_token : ", $this->access_token, "<br/>\n";
        echo "<br/>\n", "token_type : ", $this->token_type, "<br/>\n";
        echo "<br/>\n", "expires_in : ", $this->expires_in, "<br/>\n";
        echo "<br/>\n", "time : ", $this->time, "<br/>\n";
        echo "<br/>\n", "limit : ", $this->limit, "<br/>\n";
    }

    /**
     * 切換到開發環境
     * @param string $dev
     */
    public function set_status ($dev = 'production')
    {
        $brightcove = $this->CI->config->item('brightcove');
        if ($dev == 'staging') {
            $this->Client_ID = $brightcove['staging']['Client_ID'];
            $this->Client_Secret = $brightcove['staging']['Client_Secret'];
            $this->accounts = $brightcove['staging']['accounts'];
        } else {
            $this->Client_ID = $brightcove['production']['Client_ID'];
            $this->Client_Secret = $brightcove['production']['Client_Secret'];
            $this->accounts = $brightcove['production']['accounts'];
        }
    }
    
    /**
     * 取得API的token
     */
    public function get_token ()
    {
        $data = array();
        $auth_string = sprintf('%s:%s', $this->Client_ID, $this->Client_Secret);
        $request = 'https://oauth.brightcove.com/v3/access_token?grant_type=client_credentials';
        $ch = curl_init($request);
        curl_setopt_array($ch, 
                array(
                        CURLOPT_POST => TRUE,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_USERPWD => $auth_string,
                        CURLOPT_HTTPHEADER => array(
                                'Content-type: application/x-www-form-urlencoded'
                        ),
                        CURLOPT_POSTFIELDS => $data
                ));
        $response = curl_exec($ch);
        curl_close($ch);
        // Decode the response
        $responseData = json_decode($response, TRUE);
        // var_dump($responseData);
        // print_r($responseData);
        $this->access_token = $responseData['access_token'];
        $this->token_type = $responseData['token_type'];
        $this->expires_in = $responseData['expires_in'];
    }

    /**
     * 設定時間條件
     * @param string $time_start    開始時間
     * @param string $time_end      結束時間
     */
    public function set_time ($time_start = '', $time_end = '')
    {
        if (! empty($time_start) && ! empty($time_end)) {
            $this->time = sprintf('&from=%s&to=%s', $time_start, $time_end);
        } else {
            if (! empty($time_start) && empty($time_end)) {
                $this->time = sprintf('&from=%s', $time_start);
            } else {
                $this->time = '';
            }
        }
    }

    /**
     * 設定取得筆數
     * @param string $limit
     */
    public function set_limit ($limit = '10')
    {
        if (! empty($limit)) {
            $this->limit = sprintf('&limit=%s', $limit);
        } else {
            $this->limit = '';
        }
    }

    public function analytics_report ($dimensions = 'account')
    {
        // Get Analytics Report
        $data = array();
        $method = 'GET';
        $request = sprintf('https://analytics.api.brightcove.com/v1/data?accounts=%s&dimensions=%s%s%s', $this->accounts, $dimensions, $this->time, $this->limit);
        // echo $request;
        $ch = curl_init($request);
        curl_setopt_array($ch, 
                array(
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_HTTPHEADER => array(
                                'Content-type: application/json',
                                sprintf('Authorization: %s %s', $this->token_type, $this->access_token)
                        ),
                        CURLOPT_POSTFIELDS => json_encode($data)
                ));
        $response = curl_exec($ch);
        curl_close($ch);
        $this->data_result = json_decode($response, TRUE);
        return $this->data_result;
    }

    public function analytics_status ($dimensions = 'account')
    {
        // Get Analytics Report
        $data = array();
        $method = 'GET';
        $request = sprintf('https://analytics.api.brightcove.com/v1/data/status?accounts=%s&dimensions=%s%s%s', $this->accounts, $dimensions, $this->time, $this->limit);
        // echo $request;
        $ch = curl_init($request);
        curl_setopt_array($ch, 
                array(
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_HTTPHEADER => array(
                                'Content-type: application/json',
                                sprintf('Authorization: %s %s', $this->token_type, $this->access_token)
                        ),
                        CURLOPT_POSTFIELDS => json_encode($data)
                ));
        $response = curl_exec($ch);
        curl_close($ch);
        $this->data_result = json_decode($response, TRUE);
        return $this->data_result;
    }

    public function analytics_engagement ()
    {
        // Get Analytics Report
        $data = array();
        $method = 'GET';
        $request = sprintf('https://analytics.api.brightcove.com/v1/engagement/accounts/%s?%s', $this->accounts, $this->time);
        // echo $request;
        $ch = curl_init($request);
        curl_setopt_array($ch, 
                array(
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_HTTPHEADER => array(
                                'Content-type: application/json',
                                sprintf('Authorization: %s %s', $this->token_type, $this->access_token)
                        ),
                        CURLOPT_POSTFIELDS => json_encode($data)
                ));
        $response = curl_exec($ch);
        curl_close($ch);
        $this->data_result = json_decode($response, TRUE);
        return $this->data_result;
    }

    public function analytics_engagement_players ($players = '')
    {
        // Get Analytics Report
        $data = array();
        $method = 'GET';
        $request = sprintf('https://analytics.api.brightcove.com/v1/engagement/accounts/%s/players/%s', $this->accounts, $players);
        // echo $request;
        $ch = curl_init($request);
        curl_setopt_array($ch, 
                array(
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_HTTPHEADER => array(
                                'Content-type: application/json',
                                sprintf('Authorization: %s %s', $this->token_type, $this->access_token)
                        ),
                        CURLOPT_POSTFIELDS => json_encode($data)
                ));
        $response = curl_exec($ch);
        curl_close($ch);
        $this->data_result = json_decode($response, TRUE);
        return $this->data_result;
    }

    public function analytics_alltime_videos ($videos = '')
    {
        // Get Analytics Report
        $data = array();
        $method = 'GET';
        $request = sprintf('https://analytics.api.brightcove.com/v1/alltime/accounts/%s/videos/%s', $this->accounts, $videos);
        // echo $request;
        $ch = curl_init($request);
        curl_setopt_array($ch, 
                array(
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_RETURNTRANSFER => TRUE,
                        CURLOPT_SSL_VERIFYPEER => FALSE,
                        CURLOPT_HTTPHEADER => array(
                                'Content-type: application/json',
                                sprintf('Authorization: %s %s', $this->token_type, $this->access_token)
                        ),
                        CURLOPT_POSTFIELDS => json_encode($data)
                ));
        $response = curl_exec($ch);
        curl_close($ch);
        $this->data_result = json_decode($response, TRUE);
        return $this->data_result;
    }
}
