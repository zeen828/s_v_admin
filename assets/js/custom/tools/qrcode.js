var ToolsQrcode = function ToolsQrcode() {
	var myClass = '.tools_content';
	var _this = this;

	this.send_qrcode = function (){
		//多勾選
		var qrcode_size = $('select[name="qrcode_size"]').val();
		var qrcode_data = $('input[name="qrcode_data"]').val();
		if(typeof(qrcode_data) != 'undefined' && qrcode_data != ''){
			$.ajax({
				url: '/api/tools/qrcode.json',
				type: 'POST',
				cache: false,
				headers: {
					//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
					'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
				},
				dataType: 'json',
				data: {
					'qrcode_size' : qrcode_size,
					'qrcode_data' : qrcode_data,
					'output' : 'json',
				},
				error: function(xhr){
					alert('Ajax request error');
				},
				statusCode: {
					200: function(json, statusText, xhr) {
						if(json.status === true){
							$('.javascript_workspace').html('<img src="' + json.data + '" />');
						}else{
							alert('設定錯誤');
						}
					}
				}
			});
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
