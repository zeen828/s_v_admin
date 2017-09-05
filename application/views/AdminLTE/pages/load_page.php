<div class="box box-default">
	<form action="/backend/pages/load_page_channel" method="post">
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
				<div class="row">
					<input type="hidden" name="channel_pk[]" value="1">
					<div class="col-xs-6">
						<select name="channel_type[]" class="form-control">
							<option value="channel">channel</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="channel_id[]" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
		<div class="box box-info">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="channel_pk[]" value="2">
					<div class="col-xs-6">
						<select name="channel_type[]" class="form-control">
							<option value="channel">channel</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="channel_id[]" class="form-control" placeholder="影片編號">
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
	<form action="/backend/pages/load_page_video" method="post">
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
				<div class="row">
					<input type="hidden" name="video_pk[]" value="3">
					<div class="col-xs-6">
						<select name="video_type[]" class="form-control">
							<option value="programme">programme</option>
							<option value="episode">episode</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="video_id[]" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
		<div class="box box-success">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="video_pk[]" value="4">
					<div class="col-xs-6">
						<select name="video_type[]" class="form-control">
							<option value="programme">programme</option>
							<option value="episode">episode</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="video_id[]" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
		<div class="box box-success">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="video_pk[]" value="5">
					<div class="col-xs-6">
						<select name="video_type[]" class="form-control">
							<option value="programme">programme</option>
							<option value="episode">episode</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="video_id[]" class="form-control" placeholder="影片編號">
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
	<form action="/backend/pages/load_page_event" method="post">
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
				<div class="row">
					<input type="hidden" name="event_pk[]" value="6">
					<div class="col-xs-6">
						<input type="text" name="event_title[]" class="form-control" placeholder="標題">
					</div>
					<div class="col-xs-6">
						<input type="text" name="event_des[]" class="form-control" placeholder="描述">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<input type="text" name="event_img[]" class="form-control" placeholder="圖片網址">
					</div>
					<div class="col-xs-6">
						<input type="text" name="event_url[]" class="form-control" placeholder="連結位置">
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