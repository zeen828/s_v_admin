                    <div class="callout callout-info">
                        <h4>提示</h4>
                        <p><a href="/cron/coupons/coupon_sn" target="_blank">序號產生(1分鐘)</a></p>
                        <p><a href="/cron/coupons/coupon_sn_expired" target="_blank">序號過期處理(2小時)</a></p>
                    </div>
                    <div class="box-grocery_CRUD">
<?php
if(count($view_data->js_files) > 0){
    foreach($view_data->js_files as $file){ 
        echo sprintf('<script type="text/javascript" src="%s"></script>', $file);
    }
}
if(count($view_data->css_files) > 0){
    foreach($view_data->css_files as $file){
        echo sprintf('<link type="text/css" rel="stylesheet" href="%s" />', $file);
    }
}
echo $view_data->output;
?>
                    </div>
