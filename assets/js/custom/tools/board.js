var ToolsQrcode = function ToolsQrcode() {
	var myClass = '.tools_content';
	var _this = this;

	this.send_qrcode = function (){
		//多勾選
		var video_type = $('select[name="video_type"]').val();
		var video_no = $('input[name="video_no"]').val();
		var update_video_type = $('select[name="update_video_type"]').val();
		var update_video_no = $('input[name="update_video_no"]').val();
		console.log(typeof(video_type));
		console.log(typeof(video_no));
		console.log(typeof(update_video_type));
		console.log(typeof(update_video_no));
		if(
			typeof(video_type) != 'undefined' && video_type != '' &&
			typeof(video_no) != 'undefined' && video_no != '' &&
			typeof(update_video_type) != 'undefined' && update_video_type != '' &&
			typeof(update_video_no) != 'undefined' && update_video_no != ''
		){
			$.ajax({
				url: '/api/tools/board.json',
				type: 'POST',
				cache: false,
				headers: {
					//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'video_type' : video_type,
					'video_no' : video_no,
					'update_video_type' : update_video_type,
					'update_video_no' : update_video_no,
					'output' : 'json',
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {
					200: function(json, statusText, xhr) {
						if(json.status === true){
							alert('轉移成功');
						}else{
							alert('設定錯誤');
						}
					}
				}
			});
		}else{
			alert('設定錯誤');
		}
	}
	this.restart_event = function (){
		$(myClass + ' .send_btn').off('click').on('click', function() {
			_this.send_qrcode();
		});
	}
}

$(document).ready(function(){
	var run = new ToolsQrcode();
	run.restart_event();
});
