var myData = {};
var LotteriesWinners = function LotteriesWinners() {
	var myClass = '.lotters_content';
	var _this = this;
	var _datatable;
	this.get_value = function (){
		myData = {
			'pk': $('.lotters_content select[name="pk"]').val(),
			'date_range': $('.lotters_content input[name="date_range"]').val(),
			'lottery_count': $('.lotters_content input[name="lottery_count"]').val()
		};
	}
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
				}
		);
	}
	var run = new LotteriesWinners();
	run.get_value();
	run.restart_event();
});