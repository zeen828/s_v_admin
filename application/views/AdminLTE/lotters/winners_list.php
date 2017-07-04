					<div>
						開始時間<input type="text" name="fname" />
						結束時間<input type="text" name="fname" />
						抽獎名額<input type="text" name="fname" />
						<button type="button" id="lotters_clear">抽獎</button>
						<br/>
						<button type="button" id="lotters_clear">清空得獎清單</button>
					</div>
                    <div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title">開獎條件</h3>
						</div>
                        <div class="box-body">
							<div class="form-group">
								<div class="col-lg-3">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="date_range" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -8 day")), ' - ', date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -1 day"));?>" class="form-control pull-right" id="reservation" placeholder="請選擇日期...">
									</div>
								</div>
								<div class="col-lg-3">
									<input type="text" name="oredr_sn" value="" class="form-control pull-right" id="reservation" placeholder="訂單號碼...">
								</div>
								<div class="col-lg-1">
									<button type="button" class="search_btn btn btn-info btn-flat">查詢</button>
								</div>
								<div class="col-lg-1">
									<button type="button" class="search_btn btn btn-info btn-flat">查詢</button>
								</div>
							</div>
							<div class="col-xs-12 text-center loading">
								<button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
									<i class="fa fa-spin fa-refresh">開獎中請稍候...</i>
								</button>
							</div>
						</div>
                    </div>
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
					<script type="text/javascript">
					$(document).ready(function(){
						$('#lotters_clear').off('click').on('click', function() {
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
										location.reload();
									}
								}
							});
						});
					});
					</script>
