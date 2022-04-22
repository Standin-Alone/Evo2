<script>
    // Validation
    $('#create_program_item').ready(function(){
        $('#create_program_item').validate({
            // errorElement: 'div',
            errorClass: "invalid",
            validClass: "valid",
            rules: {
                item_profile:{
                    required: true,
                    extension: "jpg|jpeg|png",
                },
                item_name:{
                    required: true,
                },

                unit_measure:{
                    required: true,
                    // number: true,
                },

                // ceiling_amount:{
                //     // required: true,
                // },

                selectProgramRegion:{
                    required: true,
                },

                selectProgramProvince:{
                    required: true,
                }
            },
            messages: {
                item_profile: {
                    required: '<div class="text-danger">*Please insert an item image!</div>',
                    extension: '<div class="text-danger">*Only image format allowed to upload are jpg, jpeg or png images!</div>',
                },
                item_name: '<div class="text-danger">*The Item Name field is required!</div>',
                unit_measure: '<div class="text-danger">*The Unit Measure field is required!</div>',
                // ceiling_amount: '<div class="text-danger">*The Ceiling Amount field is required!</div>',
                selectProgramRegion: '<div class="text-danger">*Please select a Region!</div>',
                selectProgramProvince: '<div class="text-danger">*Please select a Province!</div>',
            },
            
            // Customize placement of created message error labels. 
            errorPlacement: function(error, element) {
                error.appendTo( element.parent().find(".error_msg") );
                $('span.error_form').remove();
            }
        });
    });

    // Upload image
    $(document).ready(function() {
        $(".file-upload").on('change', function(){
            readURL(this);
        });

        $(".upload-button").on('click', function() {
            $(".file-upload").click();
        });

        var readURL = function(input) {
            var x = input.files[0].name;
            function get_name(){
                return x;
            }

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.profilepic__image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });

    // Post created new program items
    $(document).on('submit', 'form#create_program_item', function(e){
        e.preventDefault();
        
        var route = "{{ route('program_item_module.submit_create_program_item_form') }}";

        // Get form
        var form = $('#create_program_item')[0];

        // Create an FormData object 
        var data = new FormData(form);

        Swal.fire({
                    title: 'Do you want to save the new item?',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Save',
                    denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    enctype: 'multipart/form-data',
                    url: route,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(success_response){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: success_response.message,
                            showConfirmButton: true,
                            // timer: 1500
                        }).then(function(){ 
                            $("#list-of-program-item-datatable").DataTable().ajax.reload();
                            $('#create_new_program_item_modal').modal('hide');
                        });
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
    });


    $('#preview_new_program_item_modal').on('hidden.bs.modal', function(e)
    { 
        $("#create_new_program_item_modal").modal("show");
    });

    // Province dropdown
    $(document).ready(function () {
        $('#selectProgramProvince').append('<option value="">-- Select Province --</option>').prop('disabled', true);
        $('#selectProgramRegion').on('change', function () {
            var reg_code = $(this).val();
            if (reg_code) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('program_item_module.region_code',['reg_code'=>':id'])}}".replace(':id', reg_code),
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        // console.log(provinces);
                        if (provinces) {
                            $('select[name="selectProgramProvince"]').empty();
                            $('select[name="selectProgramProvince"]').focus;
                            $('select[name="selectProgramProvince"]').append('<option value="">-- Select Province --</option>');
                            $.each(provinces, function (key, province) {
                                $('select[name="selectProgramProvince"]').append('<option value="' + province.prov_code + '">' + province.prov_name + ' </option>').prop('disabled', false).prop('selected', true);
                            }); 
                        } else {
                            $('#selectProgramProvince').empty();
                        }
                    }
                });
            } else {
                $('#selectProgramProvince').empty();
            }
        });
    }); 

    // Modal close function
    $('#create_new_program_item_modal').on('hidden.bs.modal', function(){

        var $validation_msg = $('#create_program_item');
        $validation_msg.validate().resetForm();

        $(this).find('form').trigger('reset');
        $('#selectProgramProvince').append('<option value="">-- Select Province --</option>').prop('disabled', true);

    });

</script>