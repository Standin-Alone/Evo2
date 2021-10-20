<script>
    $('#user_profile_password').ready(function(){
        $('#user_profile_password').validate({
            errorClass: "invalid",
               validClass: "valid",
            rules: {
                current_password:{
                    required: true,
                },

                new_password:{
                    required: true,
                },

                confirm_password: { 
                    required: true,
                }
            },
            messages: {
                current_password: '<div class="text-danger">*The current password field is required!</div>',
                new_password: '<div class="text-danger">*The new password field is required!</div>',
                confirm_password: '<div class="text-danger">*The confirm password field is required!</div>',
            },
            
            // Customize placement of created message error labels. 
            errorPlacement: function(error, element) {
                error.appendTo( element.parent().find(".error_msg") );
            }
        });
    });
    $(document).on('submit', 'form#user_profile_password', function(e){
    e.preventDefault();

    var route = $('form#user_profile_password').attr('action');
    
    var form_data = $(this);

    $("button.btn-pass").attr("disabled", true);
    // $(".btn-pass").text("Processing...");
    $(".btn-pass").html('<span id="submit-btn"><i class="fas fa-spinner fa-pulse"></i> VALIDATING...</span>');

    $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'PATCH',
        url: route,
        data: form_data.serialize(),
        success: function(success_response){
            $("button.btn-pass").attr("disabled", true);
            $(".btn-pass").html('<span id="submit-btn"><i class="fas fa-spinner fa-pulse"></i> UPDATING...</span>');
            setTimeout(function(){
                $("button.btn-pass").attr("disabled", false);
                $(".btn-pass").html('<span id="submit-btn"> CHANGE PASSWORD </span>');
                Swal.fire({
                position: 'center',
                icon: 'success',
                title: success_response.message,
                showConfirmButton: true,
                // timer: 1500
                }).then(function(){ 
                    window.location.href = "{{route('user.profile')}}";
                });
            }, 1500);
        },
        error: function(error_response){
            setTimeout(function(){
                $("button.btn-pass").attr("disabled", false);
                $(".btn-pass").html('<span id="submit-btn"> CHANGE PASSWORD </span>');
                $('span.error_password').empty();
                $('#user_profile_password')[0].reset();
                // append() = Inserts content at the end of the selected elements
                $('span.error_password').append('<div class="alert alert-danger"><span class="close" data-dismiss="alert">×</span>'+error_response.responseJSON['message']+'</div>');
            }, 1500);
        }
    });
});
</script>