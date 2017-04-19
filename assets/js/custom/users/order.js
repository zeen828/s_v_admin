var UsersOrder = function UsersOrder() {
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
		var packages_html = '';
		$.each( data.packages , function( key, value ) {
			packages_html += '<ul>' +
				'<li>packages</li>' +
				'<li>packages.id        : <span class="member_id">' + value.id + '</span></li>' +
				'<li>packages.name        : <span class="member_id">' + value.name + '</span></li>' +
				'<li>packages.order        : <span class="member_id">' + value.order + '</span></li>' +
				'<li>packages.owner        : <span class="member_id">' + value.owner + '</span></li>' +
				'<li>packages.package        : <span class="member_id">' + value.package + '</span></li>' +
				'<li>packages.paymentCycle        : <span class="member_id">' + value.paymentCycle + '</span></li>' +
				'<li>packages.validAmount        : <span class="member_id">' + value.validAmount + '</span></li>' +
				'<li>packages.validityType        : <span class="member_id">' + value.validityType + '</span></li>' +
				'<li>packages.coupon        : <span class="member_id">' + value.coupon + '</span></li>' +
				'<li>packages.cost        : <span class="member_id">' + value.cost + '</span></li>' +
				'<li>packages.finalCost        : <span class="member_id">' + value.finalCost + '</span></li>' +
				'<li>packages.createdAt        : <span class="member_id">' + value.createdAt + '</span></li>' +
				'<li>packages.updatedAt        : <span class="member_id">' + value.updatedAt + '</span></li>' +
			'</ul>';
		});
		
		templated_html = '<h3>' + data.packageNames + '</h3>' +
		'<ul>' +
			'<li>ID          : <span class="member_id">' + data.id + '</span><span class="navbar-right fa fa-money"><a href="/backend/orders/paykit_search/' + data.id + '">訂單查詢</a></span></li>' +
			'<li>type        : <span class="member_id">' + data.type + '</span></li>' +
			'<li>action      : <span class="member_id">' + data.action + '</span></li>' +
			'<li>comment     : <span class="member_id">' + data.comment + '</span></li>' +
			'<li>coupon      : <span class="member_id">' + data.coupon + '</span></li>' +
			'<li>cost        : <span class="member_id">' + data.cost + '</span></li>' +
			'<li>status      : <span class="member_id">' + data.status + '</span></li>' +
			'<li>createdAt   : <span class="member_id">' + data.createdAt + '</span></li>' +
			'<li>updatedAt   : <span class="member_id">' + data.updatedAt + '</span></li>' +
			'<li>' + packages_html + '</li>' +
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
				url: '/api/users/user_all_order.json',
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
	var run = new UsersOrder();
	run.restart_event();
	if($('input[name="keyword"]').val() != ''){
		run.search();
	}
});