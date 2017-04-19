var UsersSearch = function UsersSearch() {
	var myClass = '.users_content';
	var _this = this;
	this.main_templated = function (){
		var html_templated = '<div id="javascript_main" class="box box-info">' +
		'<div id="accordion">' +
		'</div>' +
		'</div>';
		return html_templated;
	}
	this.row_templated = function (data){
		var html_templated = '';
		var html_fb = '';
		var html_password = '';
		var html_verifi = '';
		if(data.fb_id != ''){
			html_fb = '<li>Facebook ID   : <span class="fb_id">' + data.fb_id + '</span><span class="navbar-right fa fa-facebook-square"><a target="_blank" href="https://www.facebook.com/' + data.fb_id + '">會員FB連結</a></span></li>';
		}
		if(data._perishable_token != ''){
			html_password = '<span class="send_mail_password_but navbar-right fa fa-send" data-member-id="' + data.member_id + '"><a href="#">補發密碼重設</a></span>';
			html_password += '<span class="change_password_but navbar-right fa fa-send" data-member-id="' + data.member_id + '"><a href="#">修改密碼</a></span>';
		}else{
			html_password = '<span class="change_password_but navbar-right fa fa-send" data-member-id="' + data.member_id + '"><a href="#">修改密碼</a></span>';
		}
		if(data.verified != true){
			html_verifi = '<span class="send_mail_verifi_but navbar-right fa fa-send" data-member-id="' + data.member_id + '"><a href="#">補發認證信</a></span>';
			html_verifi += '<span class="mail_verifi_but navbar-right fa fa-send" data-member-id="' + data.member_id + '"><a href="#">直接認證</a></span>';
		}

		html_templated = '<h3>Member ID : <span class="member_id">' + data.member_id + '</span></h3>' +
		'<ul>' +
		html_fb +
		'<li>User ID	 : <span class="id">' + data.user_id + '</span><span class="navbar-right fa fa-credit-card"><a href="/backend/users/order/' + data.user_id + '">訂單紀錄</a></span><span class="navbar-right fa fa-align-center"><a href="/backend/users/login_log/' + data.user_id + '">登入紀錄</a></span></li>' +
		'<li>Member ID   : <span class="member_id">' + data.member_id + '</span></li>' +
		'<li>Nick Name   : <span class="nick_name">' + data.nick_name + '</span></li>' +
		'<li>User Name   : <span class="username">' + data.username + '</span></li>' +
		'<li>E-mail      : <span class="email">' + data.email + '</span>' + html_password + '</li>' +
		'<li>Verified    : <span class="verified">' + data.verified + '</span>' + html_verifi + '</li>' +
		'<li>Creat Time  : <span class="creat_time">' + data.creat_time + '</span></li>' +
		'<li>Update Time : <span class="update_time">' + data.update_time + '</span></li>' +
		'</ul>';
		
		html_templated += '<h3>個人資料 : <span class="member_id">' + data.member_id + '</span></h3>' +
		'<ul>' +
		'<li>會員編號	 : <span class="id">' + data.member_id + '</span></li>' +
		'<li>暱稱                   : <span class="member_id">' + data.nick_name + '</span></li>' +
		'<li>全名                   : <span class="nick_name">' + data.profile.full_name + '</span></li>' +
		'<li>Email   : <span class="username">' + data.profile.contact_email + '</span></li>' +
		'<li>聯絡電話          : <span class="email">' + data.profile.contact_number + '</span></li>' +
		'<li>生日                   : <span class="verified">' + data.profile.birth_date + '</span></li>' +
		'<li>性別                   : <span class="creat_time">' + data.profile.gender + '</span></li>' +
		'<li>職業                   : <span class="update_time">' + data.profile.occupation + '</span></li>' +
		'<li>地址                 : <span class="update_time">' + data.profile.address + '</span></li>' +
		'</ul>';
		return html_templated;
	}
	this.add_html = function (data){
		$('#accordion').append(_this.row_templated(data));
	}
	this.search = function (){
		var keyword = $(myClass + ' input[name="keyword"]').val();
		if(typeof(keyword) != 'undefined' && keyword != ''){
			$.ajax({
				url: '/api/mongodbs/search_user.json',
				type: 'GET',
				cache: false,
				headers: {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'email' : keyword
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(json, statusText, xhr) {
						if(json.status === true && json.data.length >=1){
							$(myClass + ' .javascript_workspace').append(_this.main_templated());
							$.each( json.data, function( key, value ) {
								_this.add_html(value);
							});
							$('#accordion').accordion();
						}
						_this.restart_event();
					} 
				} 
			});
		}
	}
	this.send_mail_password = function (member_id){
		if(typeof(member_id) != 'undefined' && member_id != ''){
			$.ajax({
				url: '/api/mongodbs/send_mail_password.json',
				type: 'GET',
				cache: false,
				headers: {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'member_id' : member_id
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(data, statusText, xhr) {
						alert('發送忘記密碼');
						_this.restart_event();
					} 
				} 
			});
		}
	}
	this.change_password = function (member_id){
		if(typeof(member_id) != 'undefined' && member_id != ''){
			$.ajax({
				url: '/api/mongodbs/change_password.json',
				type: 'GET',
				cache: false,
				headers: {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'member_id' : member_id
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(data, statusText, xhr) {
						console.log(data);
						alert('前往修改密碼');
						var newwin = window.open();
						newwin.location= data.result;
						//window.open(data.change_password_url, 'change_password', config='height=300,width=400');
					} 
				} 
			});
		}
	}
	this.send_mail_verifi = function (member_id){
		if(typeof(member_id) != 'undefined' && member_id != ''){
			$.ajax({
				url: '/api/mongodbs/send_mail_verify.json',
				type: 'GET',
				cache: false,
				headers: {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'member_id' : member_id
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(data, statusText, xhr) {
						alert('發送認證信');
						_this.restart_event();
					} 
				} 
			});
		}
	}
	this.mail_verifi = function (member_id){
		if(typeof(member_id) != 'undefined' && member_id != ''){
			$.ajax({
				url: '/api/mongodbs/mail_verify.json',
				type: 'GET',
				cache: false,
				headers: {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'member_id' : member_id
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(data, statusText, xhr) {
						alert('認證');
						_this.restart_event();
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
		//補發密碼重設
		$(myClass + ' .send_mail_password_but').off('click').on('click', function() {
			if (confirm('補發密碼重設？')) {
				var member_id = $(this).attr('data-member-id');
				_this.send_mail_password(member_id);
			}
		});
		//密碼重設
		$(myClass + ' .change_password_but').off('click').on('click', function() {
			if (confirm('修改密碼？')) {
				var member_id = $(this).attr('data-member-id');
				_this.change_password(member_id);
			}
		});
		//補發認證信
		$(myClass + ' .send_mail_verifi_but').off('click').on('click', function() {
			if (confirm('補發認證信？')) {
				var member_id = $(this).attr('data-member-id');
				_this.send_mail_verifi(member_id);
			}
		});
		//補發認證信
		$(myClass + ' .mail_verifi_but').off('click').on('click', function() {
			if (confirm('確認認證？')) {
				var member_id = $(this).attr('data-member-id');
				_this.mail_verifi(member_id);
			}
		});
	}
}

$(document).ready(function(){
	var run = new UsersSearch();
	run.restart_event();
});