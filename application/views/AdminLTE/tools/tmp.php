					<div class="box box-info">
						<div class="box-body">
						<form method="post" id="fileinfo" name="fileinfo">
							<div class="form-group">
								<div class="col-lg-10">
									<input type="file" name="file" required />
									<a href="http://admin-background.vidol.tv/assets/uploads/device/demo_device_mac.csv">CSV格式範例</a>
								</div>
								<div class="col-lg-2">
									<button type="button" class="submit_btn btn btn-info btn-flat">上傳</button>
								</div>
							</div>
						</form>
						</div>
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
					<script type="text/javascript">
					$(document).ready(function(){
						$('.submit_btn').off('click').on('click', function() {
							var Fd = new FormData(document.getElementById('fileinfo'));
							$.ajax({
								'url': '/api/uploads/user_pay_tmp',
								'type': 'POST',
								'cache': false,
								'headers': {
									'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
								},
								'dataType': 'json',
								'data': Fd,
								'processData': false,  // tell jQuery not to process the data
								'contentType': false   // tell jQuery not to set contentType
							}).done(function( data ) {
								console.log('PHP Output:');
								console.log( data );
								if(data.status == true){
									alert('匯入完成');
								}
							});
							return false;
						});
					});
					</script>
