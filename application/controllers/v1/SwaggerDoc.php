<?php
defined ( "BASEPATH" ) or exit ( "No direct script access allowed" );
class SwaggerDoc extends CI_Controller {
	private $data_view;
	function __construct() {
		parent::__construct ();
	}
	public function index() {
		if (isset ( $_GET [''] )) {
		}
		// $api_host = (ENVIRONMENT == 'production') ? "plugin-billing.vidol.tv" : "cplugin-billing.vidol.tv";
		$api_host = $_SERVER ['HTTP_HOST'];
		$doc_array = array (
				"swagger" => "2.0",
				"info" => array (
						"title" => "RESTful API Documentation",
						"description" => "RESTful api control panel of technical documents.",
						"termsOfService" => "#",
						"contact" => array (
								"email" => "zeren828@gmail.com" 
						),
						"license" => array (
								"name" => "Apache 2.0",
								"url" => "#" 
						),
						"version" => "V 1.0" 
				),
				"host" => $api_host,
				"basePath" => "/v1",
				"tags" => array (
						array (
								"name" => "1.Lottery",
								"description" => "1.抽獎" 
						) 
				),
				"schemes" => array (
						"http" 
				),
				"paths" => array (
						"/events/lotteries/lottery.json" => array (
								"post" => array (
										"tags" => array (
												"1.Lottery" 
										),
										"summary" => "投票名單抽獎",
										"description" => "投票名單抽獎",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "random",
														"description" => "隨機碼(JQ:$.now();)",
														"in" => "formData",
														"type" => "integer",
														"required" => TRUE 
												),
												array (
														"name" => "config_id",
														"description" => "投票活動ID",
														"in" => "formData",
														"type" => "integer",
														"required" => TRUE 
												),
												array (
														"name" => "debug",
														"description" => "除錯用多列印出取得資料變數",
														"in" => "formData",
														"type" => "string",
														"enum" => array (
																'debug'
														)
												)
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"result" => $this->__get_responses_data ( "vote_config_info" ),
																		"code" => array (
																				"type" => "string",
																				"description" => "狀態碼" 
																		),
																		"message" => array (
																				"type" => "string",
																				"description" => "訊息" 
																		),
																		"time" => array (
																				"type" => "integer",
																				"description" => "耗費時間" 
																		) 
																) 
														) 
												),
												"403" => array (
														"description" => "token未授權" 
												),
												"408" => array (
														"description" => "請求超時" 
												),
												"416" => array (
														"description" => "傳遞資料錯誤" 
												) 
										) 
								) 
						) 
				) 
		);
		$this->output->set_content_type ( "application/json" );
		$this->output->set_output ( json_encode ( $doc_array ) );
	}
	
	/**
	 * 回傳的資料整理
	 *
	 * @param unknown $type        	
	 * @return string[]
	 */
	function __get_responses_data($type) {
		$responses = array ();
		switch ($type) {
			case "ml_api_oauth_token" :
				$responses = array (
						"title" => "user login info",
						"type" => "object",
						"description" => "會員登入訊息",
						"properties" => array (
								"access_token" => array (
										"type" => "string",
										"description" => "access token" 
								),
								"token_type" => array (
										"type" => "string",
										"description" => "token type" 
								),
								"expires_in" => array (
										"type" => "integer",
										"description" => "有效時限" 
								),
								"refresh_token" => array (
										"type" => "string",
										"description" => "refresh token" 
								),
								"created_at" => array (
										"type" => "string",
										"description" => "建立時間" 
								) 
						) 
				);
				break;
			case "vote_config_info" :
				$responses = array (
						"title" => "vote config info",
						"type" => "object",
						"description" => "投票項目資料",
						"properties" => array (
								"config" => array (
										"type" => "string",
										"description" => "設定檔ID" 
								),
								"title" => array (
										"type" => "string",
										"description" => "標題" 
								),
								"des" => array (
										"type" => "string",
										"description" => "描述" 
								),
								"login" => array (
										"type" => "integer",
										"description" => "登入規則(0:不需登入1:需要登入2:FB登入3:V登入)" 
								),
								"vote" => array (
										"type" => "integer",
										"description" => "投票規則(0:不重複1:重複)" 
								),
								"vote_int" => array (
										"type" => "integer",
										"description" => "每天可投票次數" 
								),
								"start" => array (
										"type" => "string",
										"description" => "開始時間(+8)" 
								),
								"end" => array (
										"type" => "string",
										"description" => "結束時間(+8)" 
								),
								"item" => $this->__get_responses_data ( "vote_item_info" ) 
						) 
				);
				break;
			case "vote_item_info" :
				$responses = array (
						"title" => "vote item info",
						"type" => "object",
						"description" => "投票項目資料",
						"properties" => array (
								"item_id" => array (
										"type" => "integer",
										"description" => "項目ID" 
								),
								"group_no" => array (
										"type" => "integer",
										"description" => "分群" 
								),
								"sort" => array (
										"type" => "integer",
										"description" => "順序" 
								),
								"title" => array (
										"type" => "string",
										"description" => "標題" 
								),
								"des" => array (
										"type" => "string",
										"description" => "描述" 
								),
								"img" => array (
										"type" => "string",
										"description" => "圖片網址" 
								),
								"url" => array (
										"type" => "string",
										"description" => "連結網址" 
								),
								"proportion" => array (
										"type" => "integer",
										"description" => "得票率" 
								) 
						) 
				);
				break;
			default :
				$responses = array (
						"description" => "OK" 
				);
				break;
		}
		return $responses;
	}
}

/* End of file swaggerDoc.php */
/* Location: ./application/controllers/v1/swaggerDoc.php */
