var UsersLogin_log = function UsersLogin_log() {
	var myClass = '.users_content';
	var _this = this;
	this.main_templated = function (){
		var templated_html = '<div id="javascript_main" class="box box-info">' +
		'<div id="accordion">' +
		'</div>' +
		'</div>';
		return templated_html;
	}
	this.row_templated = function (data){
		var templated_html = '';
		templated_html = '<h3>' + data.creat_time + '</h3>' +
		'<ul>' +
			'<li>Session id  : <span class="member_id">' + data.session_id + '</span></li>' +
			'<li>User ID   	 : <span class="member_id">' + data.user_id + '</span><span class="navbar-right fa fa-credit-card"><a href="/backend/users/order/' + data.user_id + '">訂單紀錄</a></span></li>' +
			'<li>Action   	 : <span class="member_id">' + data.action + '</span></li>' +
			'<li>Action type : <span class="member_id">' + data.action_type + '</span></li>' +
			'<li>Created     : <span class="member_id">' + data.creat_time + '</span></li>' +
			'<li>Updated     : <span class="member_id">' + data.update_time + '</span></li>' +
			'<li>Expires     : <span class="member_id">' + data.expires_time + '</span></li>' +
		'</ul>';
		return templated_html;
	}
	this.add_html = function (data){
		$('#accordion').append(_this.row_templated(data));
	}
	this.search = function (){
		var keyword = $(myClass + ' input[name="keyword"]').val();
		if(typeof(keyword) != 'undefined' && keyword != ''){
			$.ajax({
				url: '/api/mongodbs/user_login_log.json',
				type: 'GET',
				cache: false,
				headers: {
					//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'id' : keyword
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(json, statusText, xhr) {
						console.log(json);
						if(json.status === true && json.data.length >=1){
							$(myClass + ' .javascript_workspace').append(_this.main_templated());
							$.each( json.data, function( key, value ) {
								_this.add_html(value);
							});
							$('#accordion').accordion();
						}
						_this.restart_event();
					},
					204: function(json, statusText, xhr) {
						alert('查無資料或查詢錯誤');
					}
				}
			});
		}
	}
	this.restart_event = function (){
		//查詢
		$(myClass + ' .search_btn').off('click').on('click', function() {
			$('#javascript_main').remove();
			_this.search();
		});
	}
}

$(document).ready(function(){
	var run = new UsersLogin_log();
	run.restart_event();
	if($('input[name="keyword"]').val() != ''){
		run.search();
	}
});