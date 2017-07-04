var myData = {};
var LotteriesWinners = function LotteriesWinners() {
	var myClass = '.lotters_content';
	var _this = this;
	var _datatable;
	this.search = function (){
		_this.restart_event();
	}
	this.restart_event = function (){
		//查詢
		$(myClass + ' .lottery_btn').off('click').on('click', function() {
			console.log('抽獎');
		});
		$(myClass + ' .clear_btn').off('click').on('click', function() {
			console.log('清空得獎清單');
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
	var run = new LotteriesWinners();
	run.restart_event();
	run.search();
});