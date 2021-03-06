                    <div class="callout callout-info">
                        <h4>提示</h4>
                        <p><a href="http://event.vidol.tv/landingPage/index.html" target="_blank">登陸跳轉頁面</a></p>
                        <p>api有做20分鐘的cache,更新需<a href="#" class="cancel_cache">清除cache</a>重整.</p>
                    </div>
<!-- 頻道 -->
					<div class="box box-default">
						<form action="/backend/pages/landing_page_channel" method="post">
						<div class="box-header with-border">
							<h3 class="box-title">頻道</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">
<?php
if(count($view_data['channel'])>=1){
	foreach ($view_data['channel'] as $key=>$channel){
?>
							<div class="box box-info">
								<div class="box-body">
									<input type="hidden" name="channel_pk[]" value="<?php echo $channel['pk'];?>">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $channel['url'];?>" target="_blank">
												<img src="<?php echo $channel['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="channel_title[]" value="<?php echo $channel['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6">
													<select name="channel_type[]" class="form-control">
														<option value="channel"<?php if($channel['video_type'] == 'channel'){ echo ' selected'; }?>>channel</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="channel_id[]" value="<?php echo $channel['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
<?php
	}
}
?>
						</div>
						<div class="box-footer">
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>
<!-- 跑馬燈 -->
					<div class="box box-default">
						<form action="/backend/pages/landing_page_text" method="post">
						<div class="box-header with-border">
							<h3 class="box-title">跑馬燈</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body text_form">
<?php
if(count($view_data['text'])>=1){
	foreach ($view_data['text'] as $key=>$text){
?>
							<div class="box box-danger">
								<div class="box-body">
									<input type="hidden" name="text_pk[<?php echo $key;?>]" value="<?php echo $text['pk'];?>">
									<div class="row">
										<div class="col-xs-5">
											<input type="text" name="text_title[<?php echo $key;?>]" value="<?php echo $text['title'];?>" class="form-control" placeholder="標題">
										</div>
										<div class="col-xs-5">
											<input type="text" name="text_url[<?php echo $key;?>]" value="<?php echo $text['url'];?>" class="form-control" placeholder="連結位置">
										</div>
										<div class="col-xs-2">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="text_delete[<?php echo $key;?>]" value="<?php echo $text['pk'];?>">del
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
<?php
	}
}
?>
						</div>
						<div class="box-footer">
							<button type="button" class="btn btn-default text_add">add</button>
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>
					<div class="text_add_html" style="display:none">
						<div class="box box-warning">
							<div class="box-body">
								<input type="hidden" name="text_pk[]" value="">
								<div class="row">
									<div class="col-xs-6">
										<input type="text" name="text_title[]" value="" class="form-control" placeholder="標題">
									</div>
									<div class="col-xs-6">
										<input type="text" name="text_url[]" value="" class="form-control" placeholder="連結位置">
									</div>
								</div>
							</div>
						</div>
					</div>
<!-- 影音 -->
					<div class="box box-default">
						<form action="/backend/pages/landing_page_video" method="post">
						<div class="box-header with-border">
							<h3 class="box-title">影片</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">
<?php
if(count($view_data['video'])>=1){
	foreach ($view_data['video'] as $key=>$video){
?>
							<div class="box box-success">
								<div class="box-body">
									<input type="hidden" name="video_pk[]" value="<?php echo $video['pk'];?>">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $video['url'];?>" target="_blank">
												<img src="<?php echo $video['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="video_title[]" value="<?php echo $video['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6">
													<select name="video_type[]" class="form-control">
														<option value="programme"<?php if($video['video_type'] == 'programme'){ echo ' selected'; }?>>programme</option>
														<option value="episode"<?php if($video['video_type'] == 'episode'){ echo ' selected'; }?>>episode</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="video_id[]" value="<?php echo $video['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
<?php
	}
}
?>
						</div>
						<div class="box-footer">
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>
<!-- 活動 -->
					<div class="box box-default">
						<form action="/backend/pages/landing_page_event" method="post">
						<div class="box-header with-border">
							<h3 class="box-title">活動</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">
<?php
if(count($view_data['event'])>=1){
	foreach ($view_data['event'] as $key=>$event){
?>
							<div class="box box-warning">
								<div class="box-body">
									<input type="hidden" name="event_pk[]" value="<?php echo $event['pk'];?>">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $event['url'];?>" target="_blank">
												<img src="<?php echo $event['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-6">
													<input type="text" name="event_title[]" value="<?php echo $event['title'];?>" class="form-control" placeholder="標題">
												</div>
												<div class="col-xs-6">
													<input type="text" name="event_des[]" value="<?php echo $event['des'];?>" class="form-control" placeholder="描述">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6">
													<input type="text" name="event_img[]" value="<?php echo $event['image'];?>" class="form-control" placeholder="圖片網址">
												</div>
												<div class="col-xs-6">
													<input type="text" name="event_url[]" value="<?php echo $event['url'];?>" class="form-control" placeholder="連結位置">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
<?php
	}
}
?>
						</div>
						<div class="box-footer">
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>

                    <script type="text/javascript">
                    $(document).ready(function(){
                        $('.cancel_cache').off('click').on('click', function() {
                            if (confirm('清除cache？')) {
                                $.ajax({
                                    url: 'http://event.api.vidol.tv/v1/pages/landing_cached.jsonp',
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
                        $('.text_add').off('click').on('click', function() {
                            console.log('增加表單');
                            $('.text_form').append($('.text_add_html').html());
                        });
                    });
                    </script>
