<script>
    $('#edit_amount').ready(function(){
		$('#edit_amount').validate({
			errorClass: "invalid",
   			validClass: "valid",
			rules: {
                amount: {
                    required: true,
                },
			},
			messages: {
				amount: '<div class="text-danger">*The Amount field is required!</div>',
			}, 
			// Customize placement of created message error labels. 
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().find(".error_msg") );
				$('span.error_form').remove();
        	}
		});
	});
    $(document).on('submit', 'form#edit_amount', function(e){
		e.preventDefault();

		var route = "{{ route('fund_update_amount') }}";
        
		var form_data = $(this);

		$.ajax({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
			method: 'PATCH',
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
					window.location.href = "{{route('fund_overview')}}";
				});
          	},
		});
	});
</script>