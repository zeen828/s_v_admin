var run_obj;
var g_loop = {};
var g_Interval = 1;//間隔
var g_Lottery = [];//抽獎
var g_LotteryList = [];//預設抽獎名單避免AJAX錯誤沒名單
var g_LotteryArray = new Array();//中獎清單避開重副
var g_lottery_count = '0';
var g_Timer;//計時器
var g_running = false;
var start_date = '';
var end_date = ''

var EventVote = function EventVote() {
	var myClass = '.Lottery';
	var _this = this;
	//開始跑亂數
	this.random_show = function (){
		//迴圈不超過資料算法
		if(g_Interval > g_lottery_count){
			g_Interval = g_Interval - g_lottery_count;
		}
		g_Interval = g_Interval + 33;
		console.log(g_Interval);
		//是否繼續亂數
		if(g_running == true){
			g_loop = setTimeout(_this.random_show, 5);
		}
		$('#ResultNum').text(g_LotteryArray[g_Interval]);
	}
	//開獎
	this.lottery = function (){
		console.log('load_json');
		$.ajax({
			url: '/v1/events/lotteries/lottery.json',
			type: 'POST',
			cache: false,
			headers: {
				'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
			},
			dataType: 'json',
			data: {
				'random' : $.now(),
				'config_id' : '2',
				'tmp' : 'tmp'
			},
			error: function(xhr){
				console.log('Ajax request error');
				console.log(xhr);
				g_running = false;
			},
			success: function(response) {
				console.log('Ajax OK');
				console.log(response);
				g_running = false;
			},
			statusCode: {
				200: function(json, statusText, xhr) {
					console.log('statusCode 200');
					console.log(json);
					g_running = false;
					g_Lottery[0] = json.result;
				},
				416: function(json, statusText, xhr) {
					console.log('statusCode 416');
					console.log(json);
				}
			}
		});
	}
	this.restart_event = function (){
		$('.my_but').click(function(e) {
			console.log('my_but click');
			console.log(g_running);
			g_lottery_count = g_LotteryList.length;
			if(g_running == true){
				console.log('開獎');
				_this.lottery();
				g_running = false;
				//clearTimeout(g_loop);
			}else{
				console.log('啟動');
				g_running = true;
				_this.random_show();
				
			}
		});
	}
}

$(document).ready(function(){
	run_obj = new EventVote();
	run_obj.restart_event();
});
