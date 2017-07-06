                    <div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title">開獎條件</h3>
						</div>
                        <div class="box-body">
							<div class="form-group">
								<input type="hidden" name="pk" value="<?php echo $this->uri->segment(4, 0);?>">
								<div class="col-lg-4">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="date_range" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -8 day")), ' - ', date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -1 day"));?>" class="form-control pull-right" id="reservation" placeholder="請選擇日期...">
									</div>
								</div>
								<div class="col-lg-3">
									<input type="text" name="like" value="" class="form-control pull-right" id="reservation" placeholder="留言內容...">
								</div>
								<div class="col-lg-3">
									<input type="text" name="count" value="" class="form-control pull-right" id="reservation" placeholder="抽獎名額...">
								</div>
								<div class="col-lg-1">
									<button type="button" class="lottery_btn btn btn-info btn-flat">抽獎</button>
								</div>
								<div class="col-lg-1">
									<button type="button" class="clear_btn btn btn-info btn-flat">清空得獎清單</button>
								</div>
							</div>
							<div class="col-xs-12 text-center loading" style="display:none">
								<button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
									<i class="fa fa-spin fa-refresh"></i>開獎中請稍候...
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
                    <!-- date-range-picker -->
                    <script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/lotteries/winners_list.js?<?php echo time();?>"></script>
