                    <div class="box box-info">
                        <div class="box-body">
                            <div class="form-group">
                                <label>請輸入要觀看區間</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="daterange" value="<?php echo date('Y-m-01'), ' - ', date('Y-m-d');?>" class="form-control pull-right" id="reservation" placeholder="請選擇日期...">
                                    <input type="hidden" name="start" value="<?php echo date('Y-m-01');?>">
                                    <input type="hidden" name="end" value="<?php echo date('Y-m-d');?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-film"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">觀看數</span> <span class="info-box-number report_view">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-globe"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">多少城市觀看</span> <span class="info-box-number report_city">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix visible-sm-block"></div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-console"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">觀看系統</span> <span class="info-box-number report_device_os">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-phone"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">觀看設備</span> <span class="info-box-number report_device_type">0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                        
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">觀看系統</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <canvas id="pieChart1" style="height:250px"></canvas>
                                </div>
                            </div>
                            
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Donut Chart</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <canvas id="pieChart2" style="height:250px"></canvas>
                                </div>
                            </div>
                            
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Donut Chart</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <canvas id="pieChart3" style="height:250px"></canvas>
                                </div>
                            </div>
                        
                        </div>
                        <div class="col-md-6">
                        
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">觀看設備</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <canvas id="pieChart4" style="height:250px"></canvas>
                                </div>
                            </div>
                            
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Donut Chart</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <canvas id="pieChart5" style="height:250px"></canvas>
                                </div>
                            </div>
                            
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Donut Chart</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <canvas id="pieChart6" style="height:250px"></canvas>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    <!-- datepicker() -->
                    <script src="/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
                    <!-- date-range-picker -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
                    <script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
                    <!-- ChartJS 1.0.1 -->
                    <script src="/assets/plugins/chartjs/Chart.min.js"></script>
                    <!-- page js -->
                    <script src="/assets/js/custom/analytics/brightcove.js?<?php echo time();?>"></script>
                    <div class="javascript_workspace"></div>
<script>
  $(function () {
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart1").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      {
        value: 700,
        color: "#f56954",
        highlight: "#f56954",
        label: "Chrome"
      },
      {
        value: 500,
        color: "#00a65a",
        highlight: "#00a65a",
        label: "IE"
      },
      {
        value: 400,
        color: "#f39c12",
        highlight: "#f39c12",
        label: "FireFox"
      },
      {
        value: 600,
        color: "#00c0ef",
        highlight: "#00c0ef",
        label: "Safari"
      },
      {
        value: 300,
        color: "#3c8dbc",
        highlight: "#3c8dbc",
        label: "Opera"
      },
      {
        value: 100,
        color: "#d2d6de",
        highlight: "#d2d6de",
        label: "Navigator"
      }
    ];
    var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: "#fff",
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: "easeOutBounce",
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
    
//     var pieChartCanvas = $("#pieChart2").get(0).getContext("2d");
//     var pieChart = new Chart(pieChartCanvas);
//     pieChart.Doughnut(PieData, pieOptions);
    
//     var pieChartCanvas = $("#pieChart3").get(0).getContext("2d");
//     var pieChart = new Chart(pieChartCanvas);
//     pieChart.Doughnut(PieData, pieOptions);

//     var pieChartCanvas = $("#pieChart4").get(0).getContext("2d");
//     var pieChart = new Chart(pieChartCanvas);
//     pieChart.Doughnut(PieData, pieOptions);

//     var pieChartCanvas = $("#pieChart5").get(0).getContext("2d");
//     var pieChart = new Chart(pieChartCanvas);
//     pieChart.Doughnut(PieData, pieOptions);

//     var pieChartCanvas = $("#pieChart6").get(0).getContext("2d");
//     var pieChart = new Chart(pieChartCanvas);
//     pieChart.Doughnut(PieData, pieOptions);
  });
</script>
