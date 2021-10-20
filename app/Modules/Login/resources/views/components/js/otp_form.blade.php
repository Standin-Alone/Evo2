<script>
	$('#otp_form').ready(function(){
		$('#otp_form').validate({
			errorClass: "invalid",
   			validClass: "valid",
			rules:{
				otp: {
                	required: true,
					maxlength: 4,
				}
			},
			messages: {
				otp: {
					required:'<div class="text-danger">*The OTP field is required!</div>',
					maxlength: '<div class="text-danger">*The OTP Pin is only 4 digits!</div>'
				}
				
			},
				// Customize placement of created message error labels. 
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().find(".error_msg") );
        	}
		});
	});
	$(document).on('submit', 'form#otp_form', function(e){
		e.preventDefault();

		var route = "{{ route('form.check_otp_verification') }}";
		var form_data = $(this);

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'POST',
			url: route,
			data: form_data.serialize(),
			success: function(otp_verified_response){
				Swal.fire({
					position: 'center',
					icon: 'success',
					title: otp_verified_response.message,
					showConfirmButton: false,
					timer: 1500
				}).then(function(){ 
					window.location.href = "{{route('main.home')}}";
				});
          	},
			error: function(error_response){
				$('span.error_otp').empty();
				$('#otp_form')[0].reset();
				// append() = Inserts content at the end of the selected elements
				$('span.error_otp').append('<div class="alert alert-danger"><span class="close" data-dismiss="alert">×</span>'+error_response.responseJSON['message']+'</div>');
			}
		});
	});
</script>