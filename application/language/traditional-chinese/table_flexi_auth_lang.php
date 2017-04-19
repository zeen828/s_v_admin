<?php

$lang['tabels_user_accounts'] = '帳號';
$lang['fields_uacc_id'] = '主鍵';
$lang['fields_uacc_group_fk'] = '群組';
$lang['fields_uacc_email'] = '電子郵件';
$lang['fields_uacc_username'] = '帳號';
$lang['fields_uacc_password'] = '密碼';
$lang['fields_uacc_ip_address'] = '上次訪問的IP';
$lang['fields_uacc_salt'] = '獨特解析密碼';
$lang['fields_uacc_activation_token'] = '激活令牌';
$lang['fields_uacc_forgotten_password_token'] = '忘記的密碼令牌';
$lang['fields_uacc_forgotten_password_expire'] = '忘記的密碼令牌期限';
$lang['fields_uacc_update_email_token'] = '更新電子郵件令牌';
$lang['fields_uacc_update_email'] = '更新電子信箱';
$lang['fields_uacc_active'] = '狀態';
$lang['fields_uacc_suspend'] = '封鎖';
$lang['fields_uacc_fail_login_attempts'] = '登入失敗數';
$lang['fields_uacc_fail_login_ip_address'] = '最後登入失敗IP';
$lang['fields_uacc_date_fail_login_ban'] = '禁止登入日期';
$lang['fields_uacc_date_last_login'] = '上次登入時間';
$lang['fields_uacc_date_added'] = '建立時間';
$lang['fields_uacc_personal_figure'] = '個人圖';

$lang['tabels_user_groups'] = '群組';
$lang['fields_ugrp_id'] = '主鍵';
$lang['fields_ugrp_name'] = '名稱';
$lang['fields_ugrp_desc'] = '描述';
$lang['fields_ugrp_admin'] = '管理員';

$lang['tabels_user_privileges'] = '權限';
$lang['fields_upriv_id'] = '主鍵';
$lang['fields_upriv_name'] = '權限';
$lang['fields_upriv_desc'] = '描述';

$lang['tabels_user_privilege_users'] = '帳號權限';
$lang['fields_upriv_users_id'] = '主鍵';
$lang['fields_upriv_users_uacc_fk'] = '帳號';
$lang['fields_upriv_users_upriv_fk'] = '權限';

$lang['tabels_user_privilege_groups'] = '群組權限';
$lang['fields_upriv_groups_id'] = '主鍵';
$lang['fields_upriv_groups_ugrp_fk'] = '群組';
$lang['fields_upriv_groups_upriv_fk'] = '權限';
