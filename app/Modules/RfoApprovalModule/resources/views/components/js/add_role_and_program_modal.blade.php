<script>
    $(document).on('click', '#addBtn_role_and_progs', function (e) {
        e.preventDefault();

        var user_id = $(this).data('uuid');
        var program_id = $(this).data('program_id');
        var program_title = $(this).data('program_title');
        var fullname = $(this).data('fullname');

        var append_fullname = "";

        // append the fullname to Update status modal
        append_fullname += '<span class="setup_user_name_value">'+ fullname + '<span>';

        $("span.user_name_value_on_setup").append(append_fullname);

        $('#add_role_and_program_form').ready(function(){
            $('#add_role_and_program_form').validate({
                errorClass: "invalid",
                validClass: "valid",
                rules: {
                    select_role:{
                        required: true,
                    },

                    select_program: { 
                        required: true,
                    }
                },
                messages: {
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

        $(document).on('submit', 'form#add_role_and_program_form', function(e){

            e.preventDefault();
                
            var route = "{{ route('rfo_approval_module.create_setup_role_and_account') }}";

            var selected_role = $('select[name="select_role"]').val();
            var selected_program = $('select[name="select_program"]').val();

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: route,
                data: {
                        user_id:user_id,
                        selected_role:selected_role,
                        selected_program:selected_program
                },
                success: function(success_response){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: success_response.message,
                        showConfirmButton: true,
                        // timer: 1500
                    }).then(function(){ 
                        window.location.href = "{{route('rfo_approval_module.account_activation')}}";
                    });
                },
            });
        });

    }); 
    
    // This function is actived when the modal was close
    $('#add_role_and_program').on('hidden.bs.modal', function()
    {
        // remove the duplicate fullname
        $("span.setup_user_name_value").remove();
    });
</script>