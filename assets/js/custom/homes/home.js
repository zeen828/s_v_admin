function formatNumber(number){
	var num = number.toString();
	var pattern = /(-?\d+)(\d{3})/;
	while(pattern.test(num)){
		num = num.replace(pattern, "$1,$2");
	}
	return num;
}

var HomesMorrisLine = function HomesMorrisLine() {
	var myClass = '.homes_content';
	var _this = this;
	var websocket;

	//區域用
	var time_zome = 'tw';
	var Morris_line;

	this.registered_count = function (lib){
		$(myClass + ' #registered_count').html('');
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
				'chart' : 'hour',
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
							xLabelAngle: 45,
							ykeys: ['count_re', 'count_fb', 'count_mobile', 'count'],
							labels: ['一般註冊', 'FB註冊', '電話註冊', '所有註冊'],
							lineColors: ['#F39800', '#036EB8', '#E60012', '#009944'],
							units: '人',
							smooth: false,
							hoverCallback: function (index, options, content, row) {
								return "<div>" +
								"<div>[台灣時間]&nbsp;" + row.date_tw + "</div>" +
								"<div>[格林威治]&nbsp;" + row.date_utc + "</div>" +
								"<div><span class='fa fa-vimeo' style='color: #F39800;'>[一般註冊]&nbsp;" +  formatNumber(row.count_re) + " 人</span></div>" +
								"<div><span class='fa fa-facebook-square' style='color: #036EB8;'>[FB註冊]&nbsp;" +  formatNumber(row.count_fb) + " 人</span></div>" +
								"<div><span class='fa fa-phone' style='color: #E60012;'>[電話註冊]&nbsp;" +  formatNumber(row.count_mobile) + " 人</span></div>" +
								"<div><span class='fa fa-line-chart' style='color: #009944;'>[所有註冊]&nbsp;" +  formatNumber(row.count) + " 人</span></div>" +
								"</div>";
							}
						});
					}else{
						Morris_line.setData({
							element: 'registered_count',
							resize: true,
							data: json.data,
							xkey: 'date_' + time_zome,
							xLabelAngle: 45,
							ykeys: ['count_re', 'count_fb', 'count_mobile', 'count'],
							labels: ['一般註冊', 'FB註冊', '電話註冊', '所有註冊'],
							lineColors: ['#F39800', '#036EB8', '#E60012', '#009944'],
							units: '人',
							smooth: false,
							hoverCallback: function (index, options, content, row) {
								return "<div>" +
								"<div>[台灣時間]&nbsp;" + row.date_tw + "</div>" +
								"<div>[格林威治]&nbsp;" + row.date_utc + "</div>" +
								"<div><span class='fa fa-vimeo' style='color: #F39800;'>[一般註冊]&nbsp;" +  formatNumber(row.count_re) + " 人</span></div>" +
								"<div><span class='fa fa-facebook-square' style='color: #036EB8;'>[FB註冊]&nbsp;" +  formatNumber(row.count_fb) + " 人</span></div>" +
								"<div><span class='fa fa-phone' style='color: #E60012;'>[電話註冊]&nbsp;" +  formatNumber(row.count_mobile) + " 人</span></div>" +
								"<div><span class='fa fa-line-chart' style='color: #009944;'>[所有註冊]&nbsp;" +  formatNumber(row.count) + " 人</span></div>" +
								"</div>";
							}
						});
					}
				}
			}
		});
	}
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
	var run = new HomesMorrisLine();
	run.restart_event();
	run.registered_count('new');
	run.login_count('new');
});
