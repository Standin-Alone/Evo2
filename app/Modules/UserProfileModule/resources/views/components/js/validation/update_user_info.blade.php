<script>
    $('#user_profile_info').ready(function(){
        $('#user_profile_info').validate({
            errorClass: "invalid",
               validClass: "valid",
            rules: {
                email: {
                    email: true,
                },
            },
            messages: {
                email: 	{
                    email: '<div class="text-danger">*Please enter a valid email address!</div>',
                },
            },
            
            // Customize placement of created message error labels. 
            errorPlacement: function(error, element) {
                error.appendTo( element.parent().find(".error_msg") );
            }
        });
    });
    $(document).on('submit', 'form#user_profile_info', function(e){
    e.preventDefault();

    var route = $('form#user_profile_info').attr('action');
    
    var form_data = $(this);

    $("button.btn-prof").attr("disabled", true);
    // $(".btn-prof").text("Processing...");
    $(".btn-prof").html('<span id="submit-btn"><i class="fas fa-spinner fa-pulse"></i> VALIDATING...</span>');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'PATCH',
        url: route,
        data: form_data.serialize(),
        success: function(success_response){
                $("button.btn-prof").attr("disabled", true);
                $(".btn-prof").html('<span id="submit-btn"><i class="fas fa-spinner fa-pulse"></i> UPDATING...</span>');
                setTimeout(function(){
                    $("button.btn-prof").attr("disabled", false);
                    $(".btn-prof").html('<span id="submit-btn">SAVE PROFILE</span>');
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
            console.log('error');
            setTimeout(function(){
                $("button.btn-prof").attr("disabled", false);
                $(".btn-prof").html('<span id="submit-btn">SAVE PROFILE</span>');
                $('span.error_info').empty();
                $('#user_profile_info')[0].reset();
                // append() = Inserts content at the end of the selected elements
                $('span.error_info').append('<div class="alert alert-danger"><span class="close" data-dismiss="alert">×</span>'+error_response.responseJSON['message']+'</div>');
            }, 1500);
        }
    });
});
</script>