var myData = {};
var LotteriesWinners = function LotteriesWinners() {
	var myClass = '.lotters_content';
	var _this = this;
	var _datatable;
	this.get_value = function (){
		myData = {
			'pk': $(myClass+ ' input[name="pk"]').val(),
			'date_range': $(myClass+ ' input[name="date_range"]').val(),
			'count': $(myClass+ ' input[name="count"]').val()
		};
	}
	this.lottery = function (){
		_this.get_value();
		if(typeof(myData.pk) != 'undefined' && myData.pk != '0' && typeof(myData.count) != 'undefined' && myData.count != ''){
			$.ajax({
				url: '/api/lotters/lottery.json',
				type: 'POST',
				cache: false,
				headers: {
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: myData,
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {  
					200: function(json, statusText, xhr) {
						console.log(json);
						location.reload();
					}
				}
			});
		}else{
			alert('資料錯誤');
		}
		_this.restart_event();
	}
	this.clear = function (){
		_this.get_value();
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