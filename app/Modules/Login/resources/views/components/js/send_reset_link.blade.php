<script>
	$('#reset_form').ready(function(){
		$('#reset_form').validate({
			errorClass: "invalid",
   			validClass: "valid",
			rules: {
				email: {
                	required: true,
					email: true,
           		},
			},
			messages: {
				email: 	{
							required: '<div class="text-danger">*The email field is required!</div>',
							email: '<div class="text-danger">*Please enter a valid email address!</div>',
        				},
			},
			// Customize placement of created message error labels. 
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().find(".error_msg") );
        	}
		});
	});
	$(document).on('submit', 'form#reset_form', function(e){
		e.preventDefault();

		var route = "{{ route('send.req.pwd.link') }}";
		var form_data = $(this);

        $("button.btn-rst-pass-link").attr("disabled", true);
        // $(".btn-rst-pass-link").text("Processing...");
		$(".btn-rst-pass-link").html('<span id="submit-btn"><i class="fas fa-spinner fa-pulse"></i> SENDING...</span>');

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'POST',
			url: route,
			data: form_data.serialize(),
			success: function(success_response){
                setTimeout(function(){
					$("button.btn-rst-pass-link").attr("disabled", false);
					$(".btn-rst-pass-link").html('<span id="submit-btn">SEND RESET PASSWORD LINK</span>');
                    // console.log(success_response);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: success_response.message,
                        showConfirmButton: true,
                        // timer: 1500
                    }).then(function(){ 
                        window.location = "{{route('main.page')}}";
                    });
                }, 1500);	
          	},
			error: function(error_response){
                setTimeout(function(){
					$("button.btn-rst-pass-link").attr("disabled", false);
					$(".btn-rst-pass-link").html('<span id="submit-btn">SEND RESET PASSWORD LINK</span>');
				    $('span.error_email').empty();
				    $('#reset_form')[0].reset();
				    // append() = Inserts content at the end of the selected elements
				    $('span.error_email').append('<div class="alert alert-danger"><span class="close" data-dismiss="alert">×</span>'+error_response.responseJSON['message']+'</div>');
			    }, 1500);
            }
		});
	});
</script>