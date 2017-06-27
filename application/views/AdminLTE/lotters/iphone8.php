<script type="text/javascript">
$(document).ready(function(){
	$('#lotters_clear').off('click').on('click', function() {
		alert('清空');
	});
});
</script>
					<div><button type="button" id="lotters_clear">清空得獎清單</button></div>
					<div class="box-grocery_CRUD">
<?php
if(count($view_data->js_files) > 0){
	foreach($view_data->js_files as $file){
		//echo sprintf('<script type="text/javascript" src="%s"></script>', $file);
		?>
<script type="text/javascript" src="<?php echo $file;?>?v=20160601"></script>
<?php
    }
}
if(count($view_data->css_files) > 0){
    foreach($view_data->css_files as $file){
        //echo sprintf('<link type="text/css" rel="stylesheet" href="%s" />', $file);
?>
<link type="text/css" rel="stylesheet" href="<?php echo $file;?>?v=20160601" />
<?php
    }
}
echo $view_data->output;
?>
					</div>
