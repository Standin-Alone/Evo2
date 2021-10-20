<script>
	$('#login_form').ready(function(){
		// e.preventDefault();
		$('#login_form').validate({
			errorClass: "invalid",
   			validClass: "valid",
			rules: {
				email: {
                	required: true,
					email: true,
           		},
            	password: {
                	required: true,
            	},
			},
			messages: {
				email: 	{
							required: '<div class="text-danger">*The email field is required!</div>',
							email: '<div class="text-danger">*Please enter a valid email address!</div>',
        				},
            	password: '<div class="text-danger">*The password field is required!</div>',
			},
			// Customize placement of created message error labels. 
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().find(".error_msg"));
        	}
		});
	});
	$(document).on('submit', 'form#login_form', function(e){
		e.preventDefault();

		var route = "{{route('user.login')}}";
		var form_data = $(this);

		$("button.btn-log").attr("disabled", true);
        // $(".btn-log").text("Processing...");
		$(".btn-log").html('<span><i class="fas fa-spinner fa-pulse"></i></span>');

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'POST',
			url: route,
			data: form_data.serialize(),
			success: function(otp_mail_success ){
				route = "{{route('otp_page',['uuid'=>':id'])}}".replace(':id', otp_mail_success['uuid']);

				console.log(route);
				
				setTimeout(function(){
					$("button.btn-log").attr("disabled", false);
					$(".btn-log").html('<span id="submit-btn">SIGN ME IN</span>');
					Swal.fire({
					position: 'center',
					icon: 'success',
					title: otp_mail_success.message,
					showConfirmButton: true,
					}).then(function(){ 
						window.location.href = route;
					});
				}, 1500);	
          	},
			error: function(error_response){
				setTimeout(function(){
					$("button.btn-log").attr("disabled", false);
					$(".btn-log").html('<span id="submit-btn">SIGN ME IN</span>');
					$('span.error_email_pass').empty();
					$('#login_form')[0].reset();
					// append() = Inserts content at the end of the selected elements
					// stay on the same page and shows error
					$('span.error_email_pass').append('<div class="alert alert-danger"><span class="close" data-dismiss="alert">×</span>'+error_response.responseJSON['message']+'</div>');
				}, 1500);
			}
		});
	});
</script>