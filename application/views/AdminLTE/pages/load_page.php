<div class="box box-default">
<form action="/action_page.php" method="post">
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
					<input type="hidden" name="channel_pk_1" value="1">
					<div class="col-xs-6">
						<select name="channel_type_1" class="form-control">
							<option value="channel">channel</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="channel_id_1" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
		<div class="box box-info">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="channel_pk_2" value="2">
					<div class="col-xs-6">
						<select name="channel_type_2" class="form-control">
							<option value="channel">channel</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="channel_id_2" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right btn_channel">Submit</button>
		<input type="submit" class="btn btn-info pull-right btn_channel">Submit</button>
	</div>
</form>
</div>

<div class="box box-default">
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
					<input type="hidden" name="video_pk_1" value="3">
					<div class="col-xs-6">
						<select name="video_type_1" class="form-control">
							<option value="programme">programme</option>
							<option value="episode">episode</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="video_id_1" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
		<div class="box box-success">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="video_pk_2" value="4">
					<div class="col-xs-6">
						<select name="video_type_2" class="form-control">
							<option value="programme">programme</option>
							<option value="episode">episode</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="video_id_2" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
		<div class="box box-success">
			<div class="box-body">
				<div class="row">
					<input type="hidden" name="video_pk_3" value="5">
					<div class="col-xs-6">
						<select name="video_type_3" class="form-control">
							<option value="programme">programme</option>
							<option value="episode">episode</option>
						</select>
					</div>
					<div class="col-xs-6">
						<input type="text" name="video_id_3" class="form-control" placeholder="影片編號">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right btn_programme">Submit</button>
	</div>
</div>

<div class="box box-default">
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
					<input type="hidden" name="event_pk" value="6">
					<div class="col-xs-6">
						<input type="text" name="event_title" class="form-control" placeholder="標題">
					</div>
					<div class="col-xs-6">
						<input type="text" name="event_des" class="form-control" placeholder="描述">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<input type="text" name="event_img" class="form-control" placeholder="圖片網址">
					</div>
					<div class="col-xs-6">
						<input type="text" name="event_url" class="form-control" placeholder="連結位置">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right btn_event">Submit</button>
	</div>
</div>