<?php
$lang['fields_pk'] = '主鍵';
$lang['fields_user_no'] = 'User 編號';
$lang['fields_mongo_id'] = 'User ID';
$lang['fields_member_id'] = 'Member ID';
$lang['fields_nick_name'] = '使用者暱稱';
$lang['fields_title'] = '標題';
$lang['fields_description'] = '描述';
$lang['fields_order_sn'] = '訂單號碼';
$lang['fields_package_no'] = '產品包編號';
$lang['fields_package_title'] = '產品包';
$lang['fields_coupon_no'] = '優惠卷編號';
$lang['fields_coupon_sn'] = '優惠卷序號';
$lang['fields_coupon_title'] = '優惠卷';
$lang['fields_type'] = '類型';
$lang['fields_cost'] = '成本';
$lang['fields_invoice'] = '發票';
$lang['fields_price'] = '價錢';
$lang['fields_sort'] = '排序';
$lang['fields_status'] = '狀態';
$lang['fields_remark'] = '備註';
$lang['fields_ip'] = 'IP';
$lang['fields_time_start_utc'] = '開始時間(UTC)';
$lang['fields_time_start_tw'] = '開始時間(TW)';
$lang['fields_time_end_utc'] = '結束時間(UTC)';
$lang['fields_time_end_tw'] = '結束時間(TW)';
$lang['fields_time_creat_utc'] = '建立時間(UTC)';
$lang['fields_time_creat_tw'] = '建立時間(TW)';
$lang['fields_time_creat_unix'] = '建立時間(unix)';
$lang['fields_time_update_utc'] = '更新時間(UTC)';
$lang['fields_time_update_tw'] = '更新時間(TW)';
$lang['fields_time_update_unix'] = '更新時間(unix)';

$lang['tabels_User_tbl'] = '會員';
$lang['fields_u_username'] = '使用者名字';
$lang['fields_u_profile'] = '使用者圖像';
$lang['fields_u_password'] = '使用者密碼[hashed]';
$lang['fields_u_token'] = '檢驗碼';
$lang['fields_u_session_token'] = 'session';
$lang['fields_u_email'] = '使用者信箱';
$lang['fields_u_email_contact'] = '聯繫信箱';
$lang['fields_u_email_domain'] = '信箱域名';
$lang['fields_u_email_verify_token'] = '驗證碼';
$lang['fields_u_email_verified'] = '驗證狀態[0:尚未,1:驗證]';
$lang['fields_u_facebook_data'] = 'fb資料';
$lang['fields_u_facebook_id'] = 'fb id';
$lang['fields_u_facebook_token'] = 'fb token';
$lang['fields_u_facebook_expiration'] = 'fb expiration';

$lang['tabels_Logs_tbl'] = '紀錄';
$lang['fields_l_errno'] = 'code';
$lang['fields_l_errtype'] = 'log類型';
$lang['fields_l_errstr'] = 'log訊息';
$lang['fields_l_errfile'] = 'log檔案';
$lang['fields_l_errline'] = '行數';
$lang['fields_l_user_agent'] = 'user資訊';
$lang['fields_l_time'] = '時間';

$lang['tabels_Rest_keys_tbl'] = 'REST金鑰';
$lang['fields_r_user_id'] = '用戶';
$lang['fields_r_key'] = '金鑰匙';
$lang['fields_r_level'] = '層級';
$lang['fields_r_ignore_limits'] = '';
$lang['fields_r_is_private_key'] = '私有需配合IP';
$lang['fields_r_ip_addresses'] = '允許ip';
$lang['fields_r_date_created'] = '建立時間';

$lang['tabels_Sold_packages_tbl'] = '產品包';
$lang['Sold_packages_tbl_fields_sp_type_des'] = '訂單類型描述';
$lang['Sold_packages_tbl_fields_sp_image'] = '產品包圖片';
$lang['Sold_packages_tbl_fields_sp_show'] = '顯示在前端';
$lang['Sold_packages_tbl_fields_sp_unit'] = '單位';
$lang['Sold_packages_tbl_fields_sp_unit_value'] = '單位值';

$lang['tabels_Program_tbl'] = '節目表';
$lang['fields_p_type'] = '節目類型';
$lang['fields_p_week'] = '星期';
$lang['fields_p_time_start'] = '節目開始時間';
$lang['fields_p_time_end'] = '節目結束時間';
$lang['fields_p_item_type'] = '';
$lang['fields_p_video_id'] = '';
$lang['fields_p_tag'] = '';

$lang['tabels_paykit_order_tbl'] = 'paykit訂單';
$lang['fields_id'] = 'transtionID';
$lang['fields_user_id'] = 'UID';
$lang['fields_mongo_id'] = 'User ID';
$lang['fields_member_id'] = 'Member ID';
$lang['fields_nick_name'] = '暱稱';
$lang['fields_action'] = '訂單動作';
$lang['fields_type'] = '訂單類型';
$lang['fields_packageNames'] = '產品名稱';
$lang['fields_comment'] = '註釋';
$lang['fields_cost'] = '價錢';
$lang['fields_coupon'] = '優惠券';
$lang['fields_createdAt'] = '建立時間';
$lang['fields_updatedAt'] = '更新時間';

$lang['tabels_Coupon_set_tbl'] = '序號設定';
$lang['Coupon_set_tbl_fields_cs_package_no'] = '產品包';
$lang['Coupon_set_tbl_fields_cs_discount'] = '折扣';
$lang['Coupon_set_tbl_fields_cs_cash'] = '現金折抵';
$lang['Coupon_set_tbl_fields_cs_word'] = '序號長度';
$lang['Coupon_set_tbl_fields_cs_count'] = '產生筆數';
$lang['Coupon_set_tbl_fields_cs_repeat'] = '重複使用數';
$lang['Coupon_set_tbl_fields_cs_user_repeat'] = '用戶兌換次數';
$lang['Coupon_set_tbl_fields_cs_assign'] = '指定使用者mongo_id(0:不指定)';
$lang['Coupon_set_tbl_fields_cs_produce'] = '生產序號(排程用)';
$lang['Coupon_set_tbl_fields_cs_expired'] = '過期檢查(排程用)';

$lang['tabels_Coupon_tbl'] = '序號表';
$lang['Coupon_tbl_fields_c_set_no'] = '序號設定編號';
$lang['Coupon_tbl_fields_c_set_type'] = '序號設定類型';
$lang['Coupon_tbl_fields_c_set_value'] = '對應type值';
$lang['Coupon_tbl_fields_c_sn'] = '序號';
$lang['Coupon_tbl_fields_c_repeat'] = '序號重複使用數';
$lang['Coupon_tbl_fields_c_assign'] = '指定使用者mongo_id(0:不指定)';

$lang['tabels_Payments_tbl'] = '金流通路';
$lang['Payments_tbl_fields_p_proxy'] = '金流代理';
$lang['Payments_tbl_fields_p_rs'] = '約定扣款';

$lang['tabels_Orders_tbl'] = '訂單';
$lang['Orders_tbl_fields_o_user_creat'] = '建立人員';
$lang['Orders_tbl_fields_o_package_type'] = '產包類型';
$lang['Orders_tbl_fields_o_package_unit'] = '產包單位';
$lang['Orders_tbl_fields_o_package_unit_value'] = '產包單位值';
$lang['Orders_tbl_fields_o_payment_no'] = '付款方式';
$lang['Orders_tbl_fields_o_expenses'] = '優惠金額';
$lang['Orders_tbl_fields_o_subtotal'] = '需付款總數';
$lang['Orders_tbl_fields_o_rs'] = '約定信用卡';
$lang['Orders_tbl_fields_o_invoice_type'] = '發票類型';
$lang['Orders_tbl_fields_o_remark_buyer'] = '備註';
$lang['Orders_tbl_fields_o_remark_response'] = '備註';
$lang['Orders_tbl_fields_o_remark_invoice'] = '備註';
$lang['Orders_tbl_fields_o_paykit'] = '備註';

$lang['tabels_Dealers_tbl'] = '合作經銷商';
$lang['Dealers_tbl_fields_d_logo_url'] = 'logo url';
$lang['Dealers_tbl_fields_d_coupon'] = '綁定優惠卷';
$lang['Dealers_tbl_fields_d_version'] = '版本';
$lang['Dealers_tbl_fields_d_update'] = '強制更新';
$lang['Dealers_tbl_fields_d_update_url'] = '更新檔位置';

$lang['tabels_User_binding_tbl'] = '用戶綁定';
$lang['User_binding_tbl_fields_ub_dealer'] = '經銷商';
$lang['User_binding_tbl_fields_ub_device_id'] = '裝置id';
$lang['User_binding_tbl_fields_ub_device_mac'] = '裝置mac';
$lang['User_binding_tbl_fields_ub_device_key'] = '裝置key';

$lang['tabels_OVO_ad_tbl'] = '用戶綁定';
$lang['OVO_ad_tbl_fields_oa_img_url'] = '圖片位置';
$lang['OVO_ad_tbl_fields_oa_app_uri'] = 'APP位置';

$lang['tabels_Tvbox_tbl'] = '電視盒版本管理';
$lang['Tvbox_tbl_fields_t_version'] = '版本';
$lang['Tvbox_tbl_fields_t_update'] = '強制更新';
$lang['Tvbox_tbl_fields_t_update_url'] = '更新檔位置';
