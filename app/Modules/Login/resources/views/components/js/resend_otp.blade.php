<script>
	$(document).on('submit', 'form#reset_otp_form', function(e){
		e.preventDefault();

		var route = "{{ route('reset_otp_link') }}";
		var form_data = $(this);

		$("button.btn-resend-otp").attr("disabled", true);
        $(".btn-resend-otp").text("Re-sending...");

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'PATCH',
			url: route,
			data: form_data.serialize(),
			success: function(reset_otp_mail_success){
				setTimeout(function(){
					$("button.btn-resend-otp").attr("disabled", false);
					$(".btn-resend-otp").text("Resend OTP");
					Swal.fire({
						position: 'center',
						icon: 'success',
						title: reset_otp_mail_success.message,
						showConfirmButton: true,
					});
					$('span.error_otp').remove();
				}, 1500);
          	},
		});
	});
</script>