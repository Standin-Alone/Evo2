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
        $(".btn-rst-pass-link").text("Processing...");

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
					$(".btn-rst-pass-link").text("Send Reset Password Link");
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
					$(".btn-rst-pass-link").text("Send Reset Password Link");
				    $('span.error_email').empty();
				    $('#reset_form')[0].reset();
				    // append() = Inserts content at the end of the selected elements
				    $('span.error_email').append('<div class="alert alert-danger">'+error_response.responseJSON['message']+'</div>');
			    }, 1500);
            }
		});
	});
</script>