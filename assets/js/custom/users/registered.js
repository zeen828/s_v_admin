function formatNumber(number){
	var num = number.toString();
	var pattern = /(-?\d+)(\d{3})/;
	while(pattern.test(num)){
		num = num.replace(pattern, "$1,$2");
	}
	return num;
}

var UsersRegistered = function UsersRegistered() {
	var myClass = '.users_content';
	var _this = this;

	//區域用
	var time_zome = 'tw';
	var Morris_line;

	this.registered_count = function (lib){
		$(myClass + ' #registered_count').html('');
		var start_date = $('input[name="start"]').val();
		var end_date = $('input[name="end"]').val();
		var chart = $('input[name="chart"]').val();
		$.ajax({
			url: '/api/users/registered_count.json',
			type: 'GET',
			cache: false,
			headers: {
				//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
				'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
			},
			dataType: 'json',
			data: {
				'start' : start_date,
				'end' : end_date,
				'chart' : chart,
				'time_zome' : time_zome
			},
			error: function(xhr){
				alert('Ajax request error');
			},
			statusCode: {
				200: function(json, statusText, xhr) {
					if(lib = 'new'){
						Morris_line = new Morris.Line({
							element: 'registered_count',
							resize: true,
							data: json.data,
							xkey: 'date_' + time_zome,
							xLabels: [chart],
							xLabelAngle: 45,
							ykeys: ['count_re', 'count_fb', 'count_mobile', 'count'],
							labels: ['一般註冊', 'FB註冊', '電話註冊', '所有註冊'],
							lineColors: ['#FFFF00', '#0000FF', '#FF0088', '#FF0000'],
							units: '人',
							smooth: false,
							hoverCallback: function (index, options, content, row) {
								return "<div>" +
								"<div>[台灣時間]&nbsp;" + row.date_tw + "</div>" +
								"<div>[格林威治]&nbsp;" + row.date_utc + "</div>" +
								"<div><span class='fa fa-vimeo' style='color: #FFFF00;'>[一般註冊]&nbsp;" +  formatNumber(row.count_re) + " 人</span></div>" +
								"<div><span class='fa fa-facebook-square' style='color: #0000FF;'>[FB註冊]&nbsp;" +  formatNumber(row.count_fb) + " 人</span></div>" +
								"<div><span class='fa fa-phone' style='color: #FF0088;'>[電話註冊]&nbsp;" +  formatNumber(row.count_mobile) + " 人</span></div>" +
								"<div><span class='fa fa-line-chart' style='color: #FF0000;'>[所有註冊]&nbsp;" +  formatNumber(row.count) + " 人</span></div>" +
								"</div>";
							}
						});
					}else{
						Morris_line.setData({
							element: 'registered_count',
							resize: true,
							data: json.data,
							xkey: 'date_' + time_zome,
							xLabels: [chart],
							xLabelAngle: 45,
							ykeys: ['count_re', 'count_fb', 'count_mobile', 'count'],
							labels: ['一般註冊', 'FB註冊', '電話註冊', '所有註冊'],
							lineColors: ['#FFFF00', '#0000FF', '#FF0088', '#FF0000'],
							units: '人',
							smooth: false,
							hoverCallback: function (index, options, content, row) {
								return "<div>" +
								"<div>[台灣時間]&nbsp;" + row.date_tw + "</div>" +
								"<div>[格林威治]&nbsp;" + row.date_utc + "</div>" +
								"<div><span class='fa fa-vimeo' style='color: #FFFF00;'>[一般註冊]&nbsp;" +  formatNumber(row.count_re) + " 人</span></div>" +
								"<div><span class='fa fa-facebook-square' style='color: #0000FF;'>[FB註冊]&nbsp;" +  formatNumber(row.count_fb) + " 人</span></div>" +
								"<div><span class='fa fa-phone' style='color: #FF0088;'>[電話註冊]&nbsp;" +  formatNumber(row.count_mobile) + " 人</span></div>" +
								"<div><span class='fa fa-line-chart' style='color: #FF0000;'>[所有註冊]&nbsp;" +  formatNumber(row.count) + " 人</span></div>" +
								"</div>";
							}
						});
					}
				}
			}
		});
	}
	this.restart_event = function (){
		$(myClass + ' .registered_tw').off('click').on('click', function() {
			$(myClass + ' .registered_tab').removeClass('active')
			$(this).addClass('active');
			time_zome = 'tw';
			_this.registered_count('ching');
		});
		$(myClass + ' .registered_utc').off('click').on('click', function() {
			$(myClass + ' .registered_tab').removeClass('active')
			$(this).addClass('active');
			time_zome = 'utc';
			_this.registered_count('ching');
		});
	}
}

$(document).ready(function(){
	var run = new UsersRegistered();
	run.restart_event();
	run.registered_count('new');
	if($('input[name="daterange"]').length > 0) {
		$('input[name="daterange"]').daterangepicker(
				{
					format: 'YYYY-MM-DD',
					startDate: $('input[name="start"]').val(),
				    endDate: $('input[name="end"]').val()
				}, 
				function(start, end, label) {
					console.log('1');
					$('input[name="start"]').val(start.format('YYYYMMDD'));
					$('input[name="end"]').val(end.format('YYYYMMDD'));
					run.registered_count('ching');
				}
		);
	}
	if($('input[name="datepicker"]').length > 0) {
		$('input[name="datepicker"]').datepicker({
			format: 'yyyymmdd',
			autoclose: true
		}).on('changeDate', function(e) {
			$('input[name="start"]').val($('input[name="datepicker"]').val() + '000000');
			$('input[name="end"]').val($('input[name="datepicker"]').val() + '235959');
			run.registered_count('ching');
		});
	}
});
