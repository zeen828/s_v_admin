var OrdersPaykitSearch = function OrdersPaykitSearch() {
	var myClass = '.orders_content';
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
		//
		templated_html += '<h3>訂單資訊<span class="order_info"></span></h3>';
		templated_html += '<div>';
		$.each( data, function( key, value ) {
			if(key != 'buyer' && key != 'pay2goResponse' && key != 'pay2goInvoiceParams'){
				templated_html += key + '   : <span class="">' + value + '</span><br/>';
			}
		});
		templated_html += '</div>';

		//buyer
		templated_html += '<h3>買方:<span class="order_buyer">buyer</span></h3>';
		templated_html += '<div>';
		$.each( data.buyer, function( key, value ) {
			if(key == 'userId'){
				templated_html += key + '   : <span class="">' + value + '</span><span class="navbar-right fa fa-credit-card"><a href="/backend/users/order/' + value + '">訂單紀錄</a></span><br/>';	
			}else{
				templated_html += key + '   : <span class="">' + value + '</span><br/>';
			}
		});
		templated_html += '</div>';

		//pay2goInvoiceParams
		templated_html += '<h3>發票資訊<span class="order_pay2goInvoiceParams">pay2goInvoiceParams</span></h3>';
		templated_html += '<div>';
		$.each( data.pay2goInvoiceParams, function( key, value ) {
			templated_html += key + '   : <span class="">' + value + '</span><br/>';
		});
		templated_html += '</div>';

		//pay2goResponse
		templated_html += '<h3>付費狀態<span class="order_pay2goResponse">pay2goResponse</span></h3>';
		templated_html += '<div>';
		$.each( data.pay2goResponse, function( key, value ) {
			templated_html += key + '   : <span class="">' + value + '</span><br/>';
		});
		templated_html += '</div>';
		return templated_html;
	}
	this.add_html = function (data){
		$('#accordion').append(_this.row_templated(data));
	}
	this.search = function (){
		var keyword = $(myClass + ' input[name="keyword"]').val();
		if(typeof(keyword) != 'undefined' && keyword != ''){
			$.ajax({
				url: '/api/orders/paykit_search.json',
				type: 'GET',
				cache: false,
				headers: {
					//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'transtionID' : keyword
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(json, statusText, xhr) {
						if(json.status === true){
							$(myClass + ' .javascript_workspace').append(_this.main_templated());
							_this.add_html(json.data);
							$('#accordion').accordion();
						}
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
	var run = new OrdersPaykitSearch();
	run.restart_event();
	if($('input[name="keyword"]').val() != ''){
		run.search();
	}
});