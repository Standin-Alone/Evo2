<script>
	$('#change_password_form').ready(function(){
		$('#change_password_form').validate({
			errorClass: "invalid",
   			validClass: "valid",
			rules: {
            	// old_password: {
                // 	required: true
            	// },
				new_password: {
                	required: true,
            	},
			},
			messages: {
				// old_password: '<div class="text-danger">*The old password field is required!</div>',
				new_password: '<div class="text-danger">*The new password field is required!</div>',
			},
			// Customize placement of created message error labels. 
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().find(".error_msg") );
        	}
		});
	});
	$(document).on('submit', 'form#change_password_form', function(e){
		e.preventDefault();

		var route = $('form#change_password_form').attr('action');
		var form_data = $(this);

        $("button.btn-change-pass").attr("disabled", true);
        $(".btn-change-pass").text("Processing...");

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'PATCH',
			url: route,
			data: form_data.serialize(),
			success: function(success_response){
                setTimeout(function(){
					$("button.btn-change-pass").attr("disabled", false);
					$(".btn-change-pass").text("Confirm new password");
                    Swal.fire({
					position: 'center',
					icon: 'success',
					title: success_response.message,
					showConfirmButton: true,
					// timer: 1500,
                    }).then(function(){ 
                        window.location = "{{route('main.page')}}";
                    });
                }, 1500);
          	},
			error: function(error_response){
                setTimeout(function(){
					$("button.btn-change-pass").attr("disabled", false);
					$(".btn-change-pass").text("Confirm new password");
                    $('span.error_password').empty();
				    $('#change_password_form')[0].reset();
				    // append() = Inserts content at the end of the selected elements
				    $('span.error_password').append('<div class="alert alert-danger">'+error_response.responseJSON['message']+'</div>');
                }, 1500);
			}
		});
	});
</script>