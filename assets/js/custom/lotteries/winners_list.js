var myData = {};
var LotteriesWinners = function LotteriesWinners() {
	var myClass = '.lotters_content';
	var _this = this;
	var _datatable;
	this.get_value = function (){
		myData = {
			'pk': $(myClass+ ' input[name="pk"]').val(),
			'date_range': $(myClass+ ' input[name="date_range"]').val(),
			'lottery_count': $(myClass+ ' input[name="lottery_count"]').val()
		};
	}
	this.lottery = function (){
		if(typeof(myData.pk) != 'undefined' && myData.pk != '0' && typeof(myData.lottery_count) != 'undefined' && myData.lottery_count != ''){
			console.log('A');
		}else{
			console.log('B');
		}
		_this.restart_event();
	}
	this.clear = function (){
		if(typeof(myData.pk) != 'undefined' && myData.pk != '0'){
			console.log('C');
		}else{
			console.log('D');
		}
		_this.restart_event();
	}
	this.restart_event = function (){
		//查詢
		$(myClass + ' .lottery_btn').off('click').on('click', function() {
			console.log('抽獎');
			_this.lottery();
		});
		$(myClass + ' .clear_btn').off('click').on('click', function() {
			console.log('清空得獎清單');
			_this.clear();
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