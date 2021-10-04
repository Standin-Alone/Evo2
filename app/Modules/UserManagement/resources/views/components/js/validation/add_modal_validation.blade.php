<script>
    $('#add_role_form').ready(function(){
        $('#add_role_form').validate({
            errorClass: "invalid",
               validClass: "valid",
            rules: {
                select_user:{
                    required: true,
                },

                select_role:{
                    required: true,
                },

                select_program: { 
                    required: true,
                }
            },
            messages: {
                select_user: '<div class="text-danger">*Please select a user!</div>',
                select_role: '<div class="text-danger">*Please select a role!</div>',
                select_program: '<div class="text-danger">*Please select a program!</div>',
            },
            
            // Customize placement of created message error labels. 
            errorPlacement: function(error, element) {
                error.appendTo( element.parent().find(".error_msg") );
                $('span.error_form').remove();
            }
        });
    });
    $(document).on('submit', 'form#add_role_form', function(e){
    e.preventDefault();

    var route = "{{ route('list-of-users.add_user_role') }}";
    
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
                window.location.href = "{{route('list-of-users.index')}}";
            });
          },
    });
});
</script>