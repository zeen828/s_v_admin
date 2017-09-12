                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <label>請輸入要觀看區間</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="daterange" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -7 day")), ' - ', date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -1 day"));?>" class="form-control pull-right" id="reservation" placeholder="請選擇日期...">
                                    <input type="hidden" name="start" value="<?php echo date('Ymdh0000', strtotime(date('Y-m-d H:i:s') . " -7 day"));?>">
                                    <input type="hidden" name="end" value="<?php echo date('Ymdh0000', strtotime(date('Y-m-d H:i:s') . " -1 day"));?>">
                                    <input type="hidden" name="chart" value="day">
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">登入數(每日)</h3>
                        </div>
                        <div class="box-body">
                            <section class="connectedSortable">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                                        <li class="login_tab login_tw active"><a href="#" data-toggle="tab">台灣時間</a></li>
                                        <li class="login_tab login_utc"><a href="#" data-toggle="tab">格林威治</a></li>
                                        <li class="pull-left header"><i class="fa fa-inbox"></i> 登入數</li>
                                    </ul>
                                    <div class="tab-content no-padding">
                                        <div class="chart tab-pane active" id="login_count" style="position: relative; height: 300px;"></div>
                                        <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <!-- Morris.js charts() -->
                    <script src="/assets/plugins/raphael/2.2.0/raphael.min.js"></script>
                    <script src="/assets/plugins/morris/0.5.1/morris.js"></script>
                    <!-- datepicker() -->
                    <script src="/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
                    <!-- date-range-picker -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
                    <script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/users/login.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
