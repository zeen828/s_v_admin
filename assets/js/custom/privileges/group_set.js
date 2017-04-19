var PrivilegesGroup_set = function PrivilegesGroup_set() {
	var myClass = '.privileges_content';
	var _this = this;

	this.send_privileges = function (){
		//多勾選
		var group_id = $('input[name="group_id"]').val();
		var cbxVehicle = new Array();
		$('input:checkbox:checked[name="privileges"]').each(function(i) {
			cbxVehicle[i] = this.value;
		});
		$.ajax({
			url: '/api/privileges/group_set.json',
			type: 'POST',
			cache: false,
			headers: {
				//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
				'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
			},
			dataType: 'json',
			data: {
				'group_id' : group_id,
				'privileges' : cbxVehicle
			},
			error: function(xhr){
				alert('Ajax request error');
			},
			statusCode: {
				200: function(json, statusText, xhr) {
					if(json.status === true){
						history.go(-1);
					}else{
						alert('設定錯誤');
					}
				}
			}
		});
	}
	this.restart_event = function (){
		$(myClass + ' .send_btn').off('click').on('click', function() {
			_this.send_privileges();
		});
	}
}

$(document).ready(function(){
	var run = new PrivilegesGroup_set();
	run.restart_event();
});
