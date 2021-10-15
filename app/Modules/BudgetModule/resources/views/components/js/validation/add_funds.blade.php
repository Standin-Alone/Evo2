<script>
    $('#fund_encoding_ors').ready(function(){
		$('#fund_encoding_ors').validate({
			errorClass: "invalid",
   			validClass: "valid",
			rules: {
                select_program: {
                    required: true,
                },
                uacs: {
                    required: true,
                },
				select_gfi: {
                    required: true,
                },
				no_of_farmers: {
					required: true,
				},
                amount: {
                    required: true,
                },
                select_region: {
                    required: true,
                },
                select_province: {
                    required: true,
                },
                particulars: {
                    required: true,
                },
			},
			messages: {
                select_program: '<div class="text-danger">*Please select a program!</div>',
				uacs: '<div class="text-danger">*The UACS field is required!</div>',
				select_gfi: '<div class="text-danger">*Please select a GFI!</div>',
                select_region: '<div class="text-danger">*Please select a region!</div>',
                select_province: '<div class="text-danger">*Please select a province!</div>',
				no_of_farmers: '<div class="text-danger">*The no. of farmers field is required!</div>',
				amount: '<div class="text-danger">*The Amount field is required!</div>',
                particulars: '<div class="text-danger">*The particulars field is required!</div>',
			}, 
			// Customize placement of created message error labels. 
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().find(".error_msg") );
				$('span.error_form').remove();
        	}
		});
	});
    $(document).on('submit', 'form#fund_encoding_ors', function(e){
		e.preventDefault();

		var route = "{{ route('submit_encoding_form') }}";
        
		var form_data = $(this);

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'POST',
			url: route,
			data: form_data.serialize(),
			success: function(success_response){
				Swal.fire({
					position: 'center',
					icon: 'success',
					title: success_response.message,
					showConfirmButton: true,
					// timer: 1500
				}).then(function(){ 
					window.location.href = "{{route('fund_encoding')}}";
				});
          	},
		});
	});
</script>