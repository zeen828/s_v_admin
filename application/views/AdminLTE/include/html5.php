<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="robots" content="noindex,nofollow">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="/assets/img/favicon.ico">
        <title><?php echo $meta['title'];?></title>
<?php $this->load->view(sprintf("%s/include/meta_javascript" , $system['themes']), $meta);?>
<?php $this->load->view(sprintf("%s/include/meta_style" , $system['themes']), $meta);?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    	<div class="wrapper">
<?php $this->load->view(sprintf("%s/include/body_header" , $system['themes']), $header);?>
<?php $this->load->view(sprintf("%s/include/body_left_menu" , $system['themes']), $left_menu);?>
<?php $this->load->view(sprintf("%s/include/body_right_content" , $system['themes']), $right_countent);?>
<?php $this->load->view(sprintf("%s/include/body_footer" , $system['themes']), $footer);?>
<?php $this->load->view(sprintf("%s/include/body_setting" , $system['themes']), $footer);?>
    	</div>
<?php $this->load->view(sprintf("%s/include/body_javascript" , $system['themes']), array('action'=>$system['action'],'view_data'=>null));?>
    </body>
</html>