<?php
defined('BASEPATH') or exit('No direct script access allowed');

//伺服器IP
$config['server_ip'] = '127.0.0.1';

//伺服器Domain
$config['server_domain'] = 'localhost';

// 圖形驗證
$config['captcha_path'] = '';
$config['captcha_url'] = '';

// qrcode
$config['qrcode_path'] = '';
$config['qrcode_url'] = '';
$config['qrcode_http_url'] = '';
$config['qrcode_logo_str'] = '';

// 寄送信件使用
$config['email_from'] = 'noreply@email'; // 寄件者
$config['email_reply'] = 'noreply@email'; // 信件回覆
$config['email_bcc'] = array(
		'noreply@email',
); // 不記名副本

// 寄送mail忘記密碼
$config['email_user_password_uri'] = '';
$config['email_user_password_doman'] = '';
$config['email_user_password_id'] = '';

// 寄送mail信箱認證
$config['email_user_verify_vidol_uri'] = '';
$config['email_user_verify_uri'] = '';
$config['email_user_verify_doman'] = '';
$config['email_user_verify_id'] = '';

// paykit api
$config['paykit']['staging']['api_domain'] = '';
$config['paykit']['staging']['secretKey'] = '';
$config['paykit']['production']['api_domain'] = '';
$config['paykit']['production']['secretKey'] = '';

// brightcove api
$config['brightcove']['staging']['Client_ID'] = '';
$config['brightcove']['staging']['Client_Secret'] = '';
$config['brightcove']['staging']['accounts'] = '';
$config['brightcove']['production']['Client_ID'] = '';
$config['brightcove']['production']['Client_Secret'] = '';
$config['brightcove']['production']['accounts'] = '';

// RESTful key
$config['rest_ful_key'] = '';

// google authenticator key
$config['google_authenticator_key'] = '';