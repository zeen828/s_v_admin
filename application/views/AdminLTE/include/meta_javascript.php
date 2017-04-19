        <!-- jQuery 2.2.3 -->
        <script src="/assets/plugins/jQuery/2.2.3/jquery-2.2.3.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="/assets/plugins/jQuery-UI/1.11.4/jquery-ui.min.js"></script>
        <!-- Bootstrap 3.3.6() -->
        <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
        	$.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- jQuery validate 1.15.0(表單檢查) -->
        <script src="/assets/plugins/jQuery-validate/1.15.0/jquery.validate.min.js"></script>
        <script src="/assets/plugins/jQuery-validate/1.15.0/additional-methods.min.js"></script>
        <!-- loadCSS -->
        <script src="/assets/plugins/loadCSS/loadCSS.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
<?php
if(count($javascript) > 0){
	foreach($javascript as $key=>$js){
?>
		<script type="text/javascript" src="<?php echo $js;?>"></script>
<?php
	}
}
?>
