$(document).ready(function(){
	console.log('ready');
	$('#change_pass_form').validate({
		rules: {
			password: {
				required: true,
				minlength: 8
			},
			new_pass: {
				required: true,
				minlength: 8
			},
			confirm_pass: {
				required: true,
				minlength: 8,
				equalTo: "#new_pass"
			}
		},
		errorPlacement: function(error, element) {
			element.css('border', '2px solid #ff0000');
		}
	});
});
