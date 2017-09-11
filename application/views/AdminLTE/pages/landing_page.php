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
							<div class="box box-info">
								<div class="box-body">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $view_data['1']['url'];?>" target="_blank">
												<img src="<?php echo $view_data['1']['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="channel_title[]" value="<?php echo $view_data['1']['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<input type="hidden" name="channel_pk[]" value="1">
												<div class="col-xs-6">
													<select name="channel_type[]" class="form-control">
														<option value="channel"<?php if($view_data['1']['video_type'] == 'channel'){ echo ' selected'; }?>>channel</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="channel_id[]" value="<?php echo $view_data['1']['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box box-info">
								<div class="box-body">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $view_data['2']['url'];?>" target="_blank">
												<img src="<?php echo $view_data['2']['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="channel_title[]" value="<?php echo $view_data['2']['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<input type="hidden" name="channel_pk[]" value="2">
												<div class="col-xs-6">
													<select name="channel_type[]" class="form-control">
														<option value="channel"<?php if($view_data['2']['video_type'] == 'channel'){ echo ' selected'; }?>>channel</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="channel_id[]" value="<?php echo $view_data['2']['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>
					
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
							<div class="box box-success">
								<div class="box-body">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $view_data['3']['url'];?>" target="_blank">
												<img src="<?php echo $view_data['3']['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="video_title[]" value="<?php echo $view_data['3']['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<input type="hidden" name="video_pk[]" value="3">
												<div class="col-xs-6">
													<select name="video_type[]" class="form-control">
														<option value="programme"<?php if($view_data['3']['video_type'] == 'programme'){ echo ' selected'; }?>>programme</option>
														<option value="episode"<?php if($view_data['3']['video_type'] == 'episode'){ echo ' selected'; }?>>episode</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="video_id[]" value="<?php echo $view_data['3']['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box box-success">
								<div class="box-body">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $view_data['4']['url'];?>" target="_blank">
												<img src="<?php echo $view_data['4']['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="video_title[]" value="<?php echo $view_data['4']['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<input type="hidden" name="video_pk[]" value="4">
												<div class="col-xs-6">
													<select name="video_type[]" class="form-control">
														<option value="programme"<?php if($view_data['4']['video_type'] == 'programme'){ echo ' selected'; }?>>programme</option>
														<option value="episode"<?php if($view_data['4']['video_type'] == 'episode'){ echo ' selected'; }?>>episode</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="video_id[]" value="<?php echo $view_data['4']['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box box-success">
								<div class="box-body">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $view_data['5']['url'];?>" target="_blank">
												<img src="<?php echo $view_data['5']['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<div class="col-xs-12">
													<input type="text" name="video_title[]" value="<?php echo $view_data['5']['title'];?>" class="form-control" disabled>
												</div>
											</div>
											<div class="row">
												<input type="hidden" name="video_pk[]" value="5">
												<div class="col-xs-6">
													<select name="video_type[]" class="form-control">
														<option value="programme"<?php if($view_data['5']['video_type'] == 'programme'){ echo ' selected'; }?>>programme</option>
														<option value="episode"<?php if($view_data['5']['video_type'] == 'episode'){ echo ' selected'; }?>>episode</option>
													</select>
												</div>
												<div class="col-xs-6">
													<input type="text" name="video_id[]" value="<?php echo $view_data['5']['video_id'];?>" class="form-control" placeholder="影片編號">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>
					
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
							<div class="box box-warning">
								<div class="box-body">
									<div class="media">
										<div class="media-left">
											<a href="<?php echo $view_data['6']['url'];?>" target="_blank">
												<img src="<?php echo $view_data['6']['image'];?>" alt="Edura" class="media-object" style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
											</a>
										</div>
										<div class="media-body">
											<div class="row">
												<input type="hidden" name="event_pk[]" value="6">
												<div class="col-xs-6">
													<input type="text" name="event_title[]" value="<?php echo $view_data['6']['title'];?>" class="form-control" placeholder="標題">
												</div>
												<div class="col-xs-6">
													<input type="text" name="event_des[]" value="<?php echo $view_data['6']['des'];?>" class="form-control" placeholder="描述">
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6">
													<input type="text" name="event_img[]" value="<?php echo $view_data['6']['image'];?>" class="form-control" placeholder="圖片網址">
												</div>
												<div class="col-xs-6">
													<input type="text" name="event_url[]" value="<?php echo $view_data['6']['url'];?>" class="form-control" placeholder="連結位置">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="submit" value="Submit" class="btn btn-info pull-right">
						</div>
						</form>
					</div>
