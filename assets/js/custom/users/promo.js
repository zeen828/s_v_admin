var UsersPromo = function UsersPromo() {
	var myClass = '.users_content';
	var _this = this;
	this.set_promo = function (){
		var promo_package = $(myClass + ' select[name="promo_package"]').val();
		$.ajax({
			url: '/api/users/promo.json',
			type: 'POST',
			cache: false,
			headers: {
				//'VIDOL-API-KEY' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8',
				'Authorization' : 'sw84sc888kkcg0ogo8cw4swgkswkw048cc48swk8'
			},
			dataType: 'json',
			data: {
				'promo_package' : promo_package
			},
			error: function(xhr){
				alert('Ajax request error');
			},
			statusCode: {  
				200: function(json, statusText, xhr) {
					//
					console.log(json);
				} 
			} 
		});
	}
	this.restart_event = function (){
		//設定
		$(myClass + ' .send_btn').off('click').on('click', function() {
			console.log('abcd');
			_this.set_promo();
		});
	}
}

$(document).ready(function(){
	var run = new UsersPromo();
	run.restart_event();
});