                    <div class="callout callout-info">
                        <h4>提示</h4>
                        <p><a href="/cron/coupons/coupon_sn" target="_blank">序號產生(1分鐘)</a></p>
                        <p><a href="/cron/coupons/coupon_sn_expired" target="_blank">序號過期處理(2小時)</a></p>
                    </div>

                    <div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title">篩選條件</h3>
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
									<select name="status" class="form-control">
										<option value="all">全部</option>
										<option value="0">等待付款</option>
										<option value="1">付款成功</option>
										<option value="2">退訂單</option>
										<option value="-1">付款失敗</option>
									</select>
								</div>
								<div class="col-lg-3">
									<input type="text" name="oredr_sn" value="" class="form-control pull-right" id="reservation" placeholder="訂單號碼...">
								</div>
								<div class="col-lg-2">
									<input type="text" name="user" value="" class="form-control pull-right" id="reservation" placeholder="會員...">
								</div>
								<div class="col-lg-1">
									<button type="button" class="search_btn btn btn-info btn-flat">查詢</button>
								</div>
							</div>
							<div class="col-xs-12 text-center loading">
								<button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
									<i class="fa fa-spin fa-refresh"></i>資料讀取中請稍候...
								</button>
							</div>
						</div>
                    </div>
					
					<table id="example" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('fields_pk');?></th>
								<th><?php echo $this->lang->line('fields_order_sn');?></th>
								<th><?php echo $this->lang->line('fields_mongo_id');?></th>
								<th><?php echo $this->lang->line('fields_member_id');?></th>
								<th><?php echo $this->lang->line('fields_package_title');?></th>
								<th><?php echo $this->lang->line('fields_coupon_title');?></th>
								<th><?php echo $this->lang->line('fields_cost');?></th>
								<th><?php echo $this->lang->line('fields_price');?></th>
								<th><?php echo $this->lang->line('fields_invoice');?></th>
								<th><?php echo $this->lang->line('fields_status');?></th>
								<th><?php echo $this->lang->line('fields_time_creat_utc');?></th>
							</tr>
						</thead>
					</table>
                    <!-- date-range-picker -->
                    <script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
                    <!-- datatable -->
					<link type="text/css" rel="stylesheet" href="/assets/jquery_plugind/DataTables-1.10.13/media/css/dataTables.jqueryui.min.css" />
					<script src="/assets/jquery_plugind/DataTables-1.10.13/media/js/jquery.dataTables.min.js"></script>
					<script src="/assets/jquery_plugind/DataTables-1.10.13/media/js/dataTables.jqueryui.min.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/orders/search.js?<?php echo time();?>"></script>
