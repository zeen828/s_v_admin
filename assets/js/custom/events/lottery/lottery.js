var g_Interval = 1;//間隔
var g_Lottery = [];//抽獎
var g_LotteryList = [];//預設抽獎名單避免AJAX錯誤沒名單
var g_LotteryArray = new Array();//中獎清單避開重副
var g_lottery_count = '0';
var g_Timer;//計時器
var running = false;
var start_date = '';
var end_date = ''
function getLottery(start_date, end_date){
	console.log('起始第一次取得抽獎名單');
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
		},
		success: function(response) {
			console.log('Ajax OK');
			console.log(response);
		},
		statusCode: {
			200: function(json, statusText, xhr) {
				console.log('statusCode 200');
				console.log(json);
			}
		}
	});
}

function getRandomArrayElements(arr, count) {
	console.log('亂數取資料');
    var shuffled = arr.slice(0), i = arr.length, min = i - count, temp, index;
    while (i-- > min) {
        index = Math.floor((i + 1) * Math.random());
        temp = shuffled[index];
        shuffled[index] = shuffled[i];
        shuffled[i] = temp;
    }
    return shuffled.slice(min);
}

function beginRndNum(trigger){
	console.log('beginRndNum');
	console.log(trigger);
	if(running){
		console.log('A');
		if(g_Lottery.length >= 1){
			var user = getRandomArrayElements(g_Lottery, 1);
			$('#ResultNum').html(user[0]);
		}
		//
		var lottery = $('#ResultNum').html();
		if($.inArray(lottery, g_LotteryArray) != -1){
			var user = getRandomArrayElements(g_LotteryList, 1);
			$('#ResultNum').html(user[0]);
		}
		running = false;
		clearTimeout(g_Timer);
		g_LotteryArray.push($('#ResultNum').html());
		$(trigger).val("開始抽獎");
		$('#ResultNum').css('color','red');
	}else{
		console.log('B');
		getLottery();
		running = true;
		$('#ResultNum').css('color','black');
		$(trigger).val("幸運得主");
		beginTimer();
	}
}

function updateRndNum(){
	console.log('updateRndNum');
	var user = getRandomArrayElements(g_LotteryList, 1);
	$('#ResultNum').html(user[0]);
}

function beginTimer(){
	console.log('beginTimer');
	g_Timer = setTimeout(beginTimer, g_Interval);
}

function beat() {
	console.log('beat');
	g_Timer = setTimeout(beat, g_Interval);
	updateRndNum();
}
