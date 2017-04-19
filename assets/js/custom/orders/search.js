var myData = {};
var OrdersSearch = function OrdersSearch() {
	var myClass = '.orders_content';
	var _this = this;
	var _datatable;
	this.row_detail = function (r){
		return '<table cellpadding="10" cellspacing="0" border="0" style="padding-left:50px;">'+
				'<tr class="bg-yellow">'+
					'<td>Mongo ID:</td>'+
					'<td>' + r.mongo_id + '</td>'+
					'<td>Member ID:</td>'+
					'<td>' + r.member_id + '</td>'+
				'</tr>'+
				'<tr class="bg-yellow">'+
					'<td>Package NO:</td>'+
					'<td>' + r.package_no + '</td>'+
					'<td>Package Title:</td>'+
					'<td>' + r.package_title + '</td>'+
					'<td>Package Unit:</td>'+
					'<td>' + r.package_unit + '</td>'+
					'<td>Package Unit Value:</td>'+
					'<td>' + r.package_unit_value + '</td>'+
				'</tr>'+
				'<tr class="bg-yellow">'+
					'<td>Coupon No:</td>'+
					'<td>' + r.coupon_no + '</td>'+
					'<td>Coupon Title:</td>'+
					'<td>' + r.coupon_title + '</td>'+
				'</tr>'+
				'<tr class="bg-yellow">'+
					'<td>Cost:</td>'+
					'<td>' + r.cost + '</td>'+
					'<td>Price:</td>'+
					'<td>' + r.price + '</td>'+
					'<td>invoice_type:</td>'+
					'<td>' + r.invoice_type + '</td>'+
					'<td>invoice:</td>'+
					'<td>' + r.invoice + '</td>'+
				'</tr>'+
				'<tr class="bg-yellow">'+
					'<td>time_creat:</td>'+
					'<td>' + r.time_creat + '</td>'+
					'<td>time_update:</td>'+
					'<td>' + r.time_update + '</td>'+
					'<td>time_active:</td>'+
					'<td>' + r.time_active + '</td>'+
					'<td>time_deadline:</td>'+
					'<td>' + r.time_deadline + '</td>'+
				'</tr>'+
			'</table>';
	}
	this.search = function (){
//		$('#example tfoot th').each(function(){
//			var title = $(this).text();
//			$(this).html('<input type="text" placeholder="Search '+title+'" />');
//		});
		_datatable = $('#example').DataTable({
			'ajax': {
				'url': '/api/orders/search.json',
				'type': 'GET',
				'cache': false,
				'headers': {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				'dataType': 'json',
				'data': function ( d ) {
                   return  $.extend(d, myData);
                },
				'dataSrc': function ( json ) {
					//成功資料處理
					console.log('完成');
					$('.loading').hide();
					return json.data;
				}
			},
			'columns': [
				{ 'data': 'pk' },
				{
					'className': 'details-control',
					'data': 'order_sn'
				},
				{ 'data': 'mongo_id' },
				{ 'data': 'member_id' },
				{ 'data': 'package_title' },
				{ 'data': 'coupon_title' },
				{ 'data': 'cost' },
				{ 'data': 'price' },
				{ 'data': 'invoice' },
				{ 'data': 'status' },
				{ 'data': 'time_creat' }
//	            {
//	                'className': 'details-control',
//	                'orderable': false,
//	                'data': null,
//	                'defaultContent': 'detail'
//	            }
			],
			'columnDefs': [
			   	{
					'targets': 4,//欄位位置,0開始算
					'render': function ( data, type, row ) {
						return '(' + row.package_no + ') ' + data;
					}
				},
			   	{
					'targets': 5,//欄位位置,0開始算
					'render': function ( data, type, row ) {
						if(data == null){
							return '<span class="">未使用</span>';
						}
						return '(' + row.coupon_no + ') ' + data;
					}
				},
			   	{
					'targets': 8,//欄位位置,0開始算
					'render': function ( data, type, row ) {
						if(data == null){
							return '<span class="text-red">未開立</span>';
						}
						return data;
					}
				},
				{
					'targets': 9,//欄位位置,0開始算
					'render': function ( data, type, row ) {
						switch(data){
						case '-1':
							return '<span class="text-red">付款失敗</span>';
							break;
						case '0':
							return '<span class="text-yellow">等待付款</span>';
							break;
						case '1':
							return '<span class="text-green">付款完成</span>';
							break;
						case '2':
							return '<span class="">退款</span>';
							break;
						default:
							return '<span class="text-red">未定義狀態</span>';
							break;
						}
					}
				}
			],
			'scrollX': true,
			'lengthMenu': [[30, 50, 100, -1], [30, 50, 100, 'All']]
		});
//		_datatable.columns().every(function(){
//			var that = this;
//			$( 'input', this.footer() ).on( 'keyup change', function(){
//				if ( that.search() !== this.value ){
//					that.search( this.value ).draw();
//				}
//			});
//		});
		_this.restart_event();
	}
	this.restart_event = function (){
		//查詢
		$(myClass + ' .search_btn').off('click').on('click', function() {
			$('.loading').show();
			myData = {
				'date_range': $(myClass + ' input[name="date_range"]').val(),
				'status': $(myClass + ' select[name="status"]').val(),
				'oredr_sn': $(myClass + ' input[name="oredr_sn"]').val(),
				'user': $(myClass + ' input[name="user"]').val()
			};
			_datatable.ajax.reload();
			_this.restart_event();
		});
		$(myClass + ' #example tbody').off('click').on('click', 'td.details-control', function() {
	        var tr = $(this).closest('tr');
	        var row = _datatable.row( tr );
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            row.child( _this.row_detail(row.data()) ).show();
	            tr.addClass('shown');
	        }
		});
	}
}

$(document).ready(function(){
	//時間
	if($('input[name="date_range"]').length > 0) {
		$('input[name="date_range"]').daterangepicker(
				{
					format: 'YYYY-MM-DD'
					//minDate: '-10d',
					//maxDate: '+10d'
				}
		);
	}
	myData = {
		'date_range': $('.orders_content input[name="date_range"]').val(),
		'status': $('.orders_content select[name="status"]').val(),
		'oredr_sn': $('.orders_content input[name="oredr_sn"]').val(),
		'user': $('.orders_content input[name="user"]').val()
	};
	var run = new OrdersSearch();
	run.restart_event();
	run.search();
});