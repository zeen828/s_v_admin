function formatNumber(number){
	var num = number.toString();
	var pattern = /(-?\d+)(\d{3})/;
	while(pattern.test(num)){
		num = num.replace(pattern, "$1,$2");
	}
	return num;
}

var UsersLogin = function UsersLogin() {
	var myClass = '.users_content';
	var _this = this;

	//區域用
	var time_zome = 'tw';
	var Morris_line;

	this.login_count = function (lib){
		$(myClass + ' #login_count').html('');
		var start_date = $('input[name="start"]').val();
		var end_date = $('input[name="end"]').val();
		var chart = $('input[name="chart"]').val();
		$.ajax({
			url: '/api/users/login_count.json',
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
							element: 'login_count',
							resize: true,
							data: json.data,
							xkey: 'date',
							xLabels: [chart],
							xLabelAngle: 45,
							ykeys: ['count', 'count_repeat'],
							labels: ['一般註冊', 'FB註冊', '所有註冊'],
							lineColors: ['#FF8800', '#0000FF'],
							units: '人',
							smooth: false,
							hoverCallback: function (index, options, content, row) {
								return "<div>" +
								"<div>[時間]&nbsp;" + row.date + "</div>" +
								"<div><span class='fa fa-vimeo' style='color: #0000FF;'>[重複登入]&nbsp;" + formatNumber(row.count_repeat) + " 人</span></div>" +
								"<div><span class='fa fa-vimeo' style='color: #FF8800;'>[不重複登入]&nbsp;" + formatNumber(row.count) + " 人</span></div>" +
								"</div>";
							}
						});
					}else{
						Morris_line.setData({
							element: 'login_count',
							resize: true,
							data: json.data,
							xkey: 'date',
							xLabels: [chart],
							xLabelAngle: 45,
							ykeys: ['count', 'count_repeat'],
							labels: ['一般註冊', 'FB註冊', '所有註冊'],
							lineColors: ['#FF8800', '#0000FF'],
							units: '人',
							smooth: false,
							hoverCallback: function (index, options, content, row) {
								return "<div>" +
								"<div>[時間]&nbsp;" + row.date + "</div>" +
								"<div><span class='fa fa-vimeo' style='color: #0000FF;'>[重複登入]&nbsp;" + formatNumber(row.count_repeat) + " 人</span></div>" +
								"<div><span class='fa fa-vimeo' style='color: #FF8800;'>[不重複登入]&nbsp;" + formatNumber(row.count) + " 人</span></div>" +
								"</div>";
							}
						});
					}
				}
			}
		});
	}
	this.restart_event = function (){
		$(myClass + ' .login_tw').off('click').on('click', function() {
			$(myClass + ' .login_tab').removeClass('active')
			$(this).addClass('active');
			time_zome = 'tw';
			_this.login_count('ching');
		});
		$(myClass + ' .login_utc').off('click').on('click', function() {
			$(myClass + ' .login_tab').removeClass('active')
			$(this).addClass('active');
			time_zome = 'utc';
			_this.login_count('ching');
		});
	}
}

$(document).ready(function(){
	var run = new UsersLogin();
	run.restart_event();
	run.login_count('new');
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
					run.login_count('ching');
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
			run.login_count('ching');
		});
	}
});
