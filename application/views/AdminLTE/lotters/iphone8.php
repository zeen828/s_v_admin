<<script type="text/javascript">
$('#lotters_clear').off('click').on('click', function() {
	alert('清空');
	$.ajax({
		url: '/api/lotters/clear.json',
		type: 'GET',
		cache: false,
		headers: {
			'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
		},
		dataType: 'json',
		data: {
			'debug' : 'debug'
		},
		error: function(xhr){
			alert('Ajax request error');
		},
		statusCode: {
			200: function(json, statusText, xhr) {
				console.log(json);
			}
		}
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
