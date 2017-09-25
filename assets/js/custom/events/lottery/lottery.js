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
}

function writeLotteryList(){
	console.log('紀錄得獎者');
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
		writeLotteryList();//
		g_LotteryArray.push($('#ResultNum').html());
		$(trigger).val("開始抽獎");
		$('#ResultNum').css('color','red');
	}else{
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
	g_Timer = setTimeout(beat, g_Interval);
}

function beat() {
	console.log('beat');
	g_Timer = setTimeout(beat, g_Interval);
	updateRndNum();
}
