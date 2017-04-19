function formatNumber(number){
	var num = number.toString();
	var pattern = /(-?\d+)(\d{3})/;
	while(pattern.test(num)){
		num = num.replace(pattern, "$1,$2");
	}
	return num;
}

var AnalyticsBrightcove = function AnalyticsBrightcove() {
	var myClass = '.analytics_content';
	var _this = this;
	
	//圓餅圖用
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

	this.report = function (){
		var start_date = $('input[name="start"]').val();
		var end_date = $('input[name="end"]').val();
		$.ajax({
			url: '/api/analytics/report.json',
			type: 'GET',
			cache: false,
			headers: {
				//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
				'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
			},
			dataType: 'json',
			data: {
				'start' : start_date,
				'end' : end_date
			},
			error: function(xhr){
				alert('Ajax request error');
			},
			statusCode: {
				200: function(json, statusText, xhr) {
					console.log(json);
					if(json.status === true){
						$(myClass + ' .report_view').html(json.data.view);
						$(myClass + ' .report_city').html(json.data.city);
						$(myClass + ' .report_device_os').html(json.data.device_os);
						$(myClass + ' .report_device_type').html(json.data.device_type);
					}
				}
			}
		});
	}
	
	this.test = function (){
		var start_date = $('input[name="start"]').val();
		var end_date = $('input[name="end"]').val();
		$.ajax({
			url: '/api/analytics/test.json',
			type: 'GET',
			cache: false,
			headers: {
				//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
				'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
			},
			dataType: 'json',
			data: {
				'start' : start_date,
				'end' : end_date
			},
			error: function(xhr){
				alert('Ajax request error');
			},
			statusCode: {
				200: function(json, statusText, xhr) {
					console.log(json);
					if(json.status === true){
						$(myClass + ' .report_view').html(json.data.view);
						$(myClass + ' .report_city').html(json.data.city);
						$(myClass + ' .report_device_os').html(json.data.device_os);
						$(myClass + ' .report_device_type').html(json.data.device_type);
					}
				}
			}
		});
	}
	
	this.restart_event = function (){
		$(myClass + ' .registered_tw').off('click').on('click', function() {
			$(myClass + ' .registered_tab').removeClass('active')
			$(this).addClass('active');
		});
		$(myClass + ' .registered_utc').off('click').on('click', function() {
			$(myClass + ' .registered_tab').removeClass('active')
			$(this).addClass('active');
		});
	}
}

$(document).ready(function(){
	var run = new AnalyticsBrightcove();
	run.restart_event();
	run.report();
	$('input[name="daterange"]').daterangepicker(
			{
				format: 'YYYY-MM-DD',
				startDate: $('input[name="start"]').val(),
			    endDate: $('input[name="end"]').val()
			}, 
			function(start, end, label) {
				$('input[name="start"]').val(start.format('YYYY-MM-DD'));
				$('input[name="end"]').val(end.format('YYYY-MM-DD'));
				run.report();
				run.test();
			}
	);
});
