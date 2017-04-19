                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-comments-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">三立台灣台</span> <span class="info-box-number"><?php echo $view_data['header']['taiwan'];?><small>人觀看</small></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-comments-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">三立都會台</span> <span class="info-box-number"><?php echo $view_data['header']['metro'];?><small>人觀看</small></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix visible-sm-block"></div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-facebook"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">FB會員數</span> <span class="info-box-number"><?php echo $view_data['header']['user_fb_count'];?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">總會員數</span> <span class="info-box-number"><?php echo $view_data['header']['users_count'];?></span>
                                </div>
                            </div>
                        </div>
                    </div>
<?php
if(!empty($view_data['tip'])){
?>
                    <div class="callout callout-info">
                        <h4>公告</h4>
                        <p><?php echo $view_data['tip'];?></p>
                    </div>
<?php
}
?>
					<!-- 註冊 -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <section class="connectedSortable">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right ui-sortable-handle">
                                    <li class="registered_tab registered_tw active"><a href="#" data-toggle="tab">台灣時間</a></li>
                                    <li class="registered_tab registered_utc"><a href="#" data-toggle="tab">格林威治</a></li>
                                    <li class="pull-left header"><i class="fa fa-inbox"></i> 註冊數</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <div class="chart tab-pane active" id="registered_count" style="position: relative; height: 300px;"></div>
                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                                </div>
                            </div>
                        </section>
                    </div>
					<!-- 不重複登入 -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
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
                    <!-- Morris.js charts() -->
                    <script src="/assets/plugins/raphael/2.2.0/raphael.min.js"></script>
                    <script src="/assets/plugins/morris/0.5.1/morris.js"></script>
                    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
                    <script src="/assets/js/pages/dashboard.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/homes/home.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
