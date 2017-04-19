<?php
if(ENVIRONMENT == 'production'){
	$api_domain = 'plugin-background.vidol.tv';
	$cron_domain = 'admin-background.vidol.tv';
}else{
	$api_domain = 'cplugin-background.vidol.tv';
	$cron_domain = 'cadmin-background.vidol.tv';
}
?>
                    <div class="callout callout-info">
                        <h4>提示</h4>
                        <p><a href="http://<?php echo $api_domain;?>/api/tools/afternoon.json" target="_blank">JSON</a></p>
                        <p>api有做30分鐘的統計,更新需<a href="#" class="run_cron">即時統計</a>.</p>
                        <p>api有做5分鐘的cache,更新需<a href="#" class="cancel_cache">清除cache</a>重整.</p>
                    </div>

<?php
foreach ($view_data as $category_no=>$video){
?>
                    <div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title"><?php echo $video['title'];?> (1% 約 <?php echo ceil($video['sum']/100);?> 票)</h3>
						</div>
                        <div class="box-body">
                           	<form role="form_<?php echo $category_no;?>" action="<?php echo $video['post'];?>" method="post">
                           		<input type="hidden" name="sum" value="<?php echo $video['sum'];?>">
                            	
<?php	
	foreach ($video['countent'] as $video_id_no=>$val){
?>
							<div class="form-group">
								<div class="col-xs-12">
									<label><?php echo $val['title'];?> (現有票數: <?php echo $val['tickets']," <b style='color:#ff0000;'>[",sprintf ( '%2.2f', $val['tickets']/$video['sum']*100 );?>%]</b>)</label>
									<input type="text" name="video_id_<?php echo $video_id_no;?>" class="form-control" value="<?php echo $val['add_ticket'];?>">
								</div>
							</div>

<?php
	}
?>
							<div class="form-group">
								<div class="col-xs-12">
									<label></label>
									<div class="input-group input-group-sm">
										<span class="input-group-btn">
											<button type="submit" class="send_btn btn btn-info btn-flat">更新</button>
										</span>
									</div>
								</div>
							</div>
							</form>
						</div>
                    </div>
<?php	
}
?>
                    <!-- page js -->
                    <script src="/assets/js/custom/sdac/vote.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace">
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function(){
                        $('.cancel_cache').off('click').on('click', function() {
                            if (confirm('清除cache？')) {
                                $.ajax({
                                    url: 'http://<?php echo $api_domain;?>/api/caches/cancel_vote.json',
                                    type: 'GET',
                                    cache: false,
                                    headers: {
                                        'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
                                    },
                                    dataType: 'jsonp',
                                    error: function(xhr){
                                        //alert('Ajax request error');
                                    },
                                    statusCode: {  
                                        200: function(data, statusText, xhr) {
                                            //alert('清除cache!');
                                        } 
                                    } 
                                });
                            }
                        });
                        $('.run_cron').off('click').on('click', function() {
                            if (confirm('即時統計？')) {
                                $.ajax({
                                    url: 'http://<?php echo $cron_domain;?>/cron/sdac/subtotal',
                                    type: 'GET',
                                    cache: false,
                                    headers: {
                                        'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
                                    },
                                    dataType: 'jsonp',
                                    error: function(xhr){
                                        //alert('Ajax request error');
                                    },
                                    statusCode: {  
                                        200: function(data, statusText, xhr) {
                                            //alert('即時統計!');
                                        } 
                                    } 
                                });
                            }
                        });
                    });
                    </script>

