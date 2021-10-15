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
    $(".btn-prof").text("Processing...");

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'PATCH',
        url: route,
        data: form_data.serialize(),
        success: function(success_response){
                $("button.btn-prof").attr("disabled", true);
                $(".btn-prof").text("Saving...");
                setTimeout(function(){
                    $("button.btn-prof").attr("disabled", false);
                    $(".btn-prof").text("Save Profile");
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
                $(".btn-prof").text("Save Profile");
                $('span.error_info').empty();
                $('#user_profile_info')[0].reset();
                // append() = Inserts content at the end of the selected elements
                $('span.error_info').append('<div class="alert alert-danger">'+error_response.responseJSON['message']+'</div>');
            }, 1500);
        }
    });
});
</script>