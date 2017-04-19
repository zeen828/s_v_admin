                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <label>請輸入查詢間距</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="spacing" class="form-control" value="0,18,24,34,44,54,64,150" placeholder="查詢間距...">
                                    <span class="input-group-btn">
                                        <button type="button" class="search_btn btn btn-info btn-flat">查詢</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">註冊數(小時)</h3>
                        </div>
                        <div class="box-body">
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
                    </div>
                    <!-- Morris.js charts() -->
                    <script src="/assets/plugins/raphael/2.2.0/raphael.min.js"></script>
                    <script src="/assets/plugins/morris/0.5.1/morris.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/users/age.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
