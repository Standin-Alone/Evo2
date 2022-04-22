<script>
    // Validation
    $('#update_program_item_form').ready(function(){
        $('#update_program_item_form').validate({
                errorClass: "invalid",
                validClass: "valid",
                rules: {
                    update_item_profile:{
                        // required: true,
                        extension: "jpg|jpeg|png",
                    },

                    update_item_name:{
                        required: true,
                    },

                    update_unit_measure:{
                        required: true,
                        // number: true,
                    },

                    // update_ceiling_amount:{
                    //     required: true,
                    //     // number: true,
                    // },

                    update_selectProgramRegion:{
                        required: true,
                    },

                    update_selectProgramProvince:{
                        required: true,
                    }
                },
                messages: {
                    update_item_profile: {
                        extension: '<div class="text-danger">*Only image format allowed to upload are jpg, jpeg or png images!</div>',
                    },
                    update_item_name: '<div class="text-danger">*The Item Name field is required!</div>',
                    update_unit_measure: {
                        required: '<div class="text-danger">*The Unit Measure field is required!</div>',
                    },
                    // update_ceiling_amount: {
                    //     required: '<div class="text-danger">*The Ceiling Amount field is required!</div>',
                    //     // number: '',
                    // },
                    update_selectProgramRegion: '<div class="text-danger">*Please select a Region!</div>',
                    update_selectProgramProvince: '<div class="text-danger">*Please select a Province!</div>',
                },
                
                // Customize placement of created message error labels. 
                errorPlacement: function(error, element) {
                    error.appendTo( element.parent().find(".error_msg") );
                    $('span.error_form').remove();
                }
        });
    });

    $(document).on('click', '#updateItemBtn', function () {

        var item_id = $(this).data('item_id');

        var item_profile = $(this).data('item_profile');
        // console.log(item_profile);
        var html = "";

        $.ajax({
            url: "{{ route('program_item_module.view_selected_program_item', ['program_item_id' => ':id']) }}".replace(':id', item_id),
            type: "GET",
            dataType: "json",
            success: function (datas){

                // console.log(datas);

                var selected_program_items = datas[0];
                var region = datas[1];
                var province = datas[2];
                var list_of_regions = datas[3];
                var list_of_provinces = datas[4];

                $.each(selected_program_items, function (key, pi){
                        html += '<div class="show_append_update_form">';

                            // html += '<div class="input_div mt-4">';
                                // html += '<h4 class="mt-4" style="text-align:center">INTERVENTION ITEM</h4>';
                                html +=     '<div class="row"> ';
                                    html +=     '<div class="col-md-5">'

                                    html +=     '<input type="hidden" name="update_item_id" value="'+item_id+'">'

                                    html +=          '<div class="profilepic mx-auto d-block" style="margin-top:20px; box-shadow: 0 4px 20px 0px rgb(0 0 0 / 20%), 0 7px 10px -5px rgb(0 188 212 / 100%);">';
                                    html +=               '<img id="update_previewImg" class="update_profilepic__image" src="'+item_profile+'">';
                                    html +=               '<div class="profilepic__content update_upload-button">';
                                    html +=                   '<span class="profilepic__icon"><i class="fa fa-camera fa-2x"></i></span>';
                                    html +=                   '<span class="profilepic__text">Upload </span>';
                                    html +=                   '<br /> Item Photo</span>';
                                    html +=               '</div>';
                                    html +=               '<input class="update_item_profile" name="update_item_profile" type="file" accept="image/*" />';
                                    html +=               '<span class="error_msg"></span>';
                                    html +=         '</div>';
                                    html +=     '</div>';

                                    html +=     '<div class="col-md-7">';

                                    html +=          '<h5 style="">ITEM DETAILS:</h5>';

                                    html +=          '<div class="form-group">';
                                    html +=               '<label class="form-label">Item Name: <span class="text-danger">*</span></label>';
                                    html +=               '<input id="update_item_name" name="update_item_name" value="'+pi.item_name+'" placeholder="Enter Item Name" class="form-control" type="text">';
                                    html +=               '<span class="error_msg"></span>';
                                    html +=          '</div>';

                                    html +=          '<div class="form-group">';
                                    html +=               '<label class="form-label"> Unit of Measure: <span class="text-danger">*</span></label>';
                                    html +=               '<input type="text" id="update_unit_measure" name="update_unit_measure" value="'+pi.unit_measure+'" placeholder="Enter Unit of Measurement" class="form-control">';
                                    html +=               '<span class="error_msg"></span>';
                                    html +=          '</div>';

                                    html +=          '<div class="form-group">';
                                    html +=               '<label class="form-label">Region: <span class="text-danger">*</span></label>';
                                    html +=               '<select class="form-control update_selectProgramRegion" id="update_selectProgramRegion" name="update_selectProgramRegion">';
                                    
                                    $.each(region, function(key, reg){
                                        html +=                 '<option value="'+reg.reg_code+'" selected>'+reg.reg_name+'</option>';
                                    });

                                    $.each(list_of_regions, function(key, region){
                                        html +=                    '<option value="'+ region.reg_code +'">'+ region.reg_name +'</option>' 
                                    });

                                    html +=               '</select>';
                                    html +=                '<span class="error_msg"></span>';
                                    html +=          '</div>';

                                    html +=          '<div class="form-group">';
                                    html +=              '<label class="form-label">Province: <span class="text-danger">*</span></label>';
                                    html +=              '<select class="form-control update_selectProgramProvince" id="update_selectProgramProvince" name="update_selectProgramProvince">';
                                    
                                    $.each(province, function(key, prov){
                                        html +=                 '<option value="'+ prov.prov_code +'" selected>'+ prov.prov_name +'</option>';
                                    });

                                    $.each(list_of_provinces, function(key, province){
                                        html +=                '<option value="'+ province.prov_code +'">'+ province.prov_name +'</option>' 
                                    });  
                                    
                                    html +=              '</select>';
                                    html +=              '<span class="error_msg"></span>';
                                    html +=         '</div>';

                                    html +=    '</div>';    
                                html +=     '</div>';

                            // html += '</div>';    

                        html += '</div>';

                        // Show append html in view
                        $("#append_start_here").append(html);

                        // Upload image
                        $(document).ready(function() {
                            $(".update_item_profile").on('change', function(){
                                readURL(this);
                            });

                            $(".update_upload-button").on('click', function() {
                                $(".update_item_profile").click();
                            });

                            var readURL = function(input) {
                                var x = input.files[0].name;
                                
                                function get_name(){
                                    return x;
                                }

                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function (e) {
                                        $('.update_profilepic__image').attr('src', e.target.result);
                                    }
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        })

                        $('#update_selectProgramRegion').on('change', function () {
                                // var reg_code = $(this).val();

                                var reg_code = $('select[name="update_selectProgramRegion"]').val();

                                if (reg_code != '') {
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

                                                $('select[name="update_selectProgramProvince"]').empty();
                                                $('select[name="update_selectProgramProvince"]').focus;
                                                
                                                $.each(provinces, function (key, province) {
                                                    $('select[name="update_selectProgramProvince"]').append('<option value="' + province.prov_code + '">' + province.prov_name + ' </option>').prop('disabled', false).prop('selected', true);
                                                }); 
                                            } else {
                                                $('#update_selectProgramProvince').empty();
                                            }
                                        }
                                    });
                                }
                        });
                });
            }

        });
    });

        // Patch edited program item
        $(document).on('submit', 'form#update_program_item_form', function(e){
            e.preventDefault();

            var route = "{{ route('program_item_module.submit_update_program_item_form') }}";

            // Get form
            var form = $('#update_program_item_form')[0];

            // Create an FormData object 
            var data = new FormData(form);

            Swal.fire({
                        title: 'Do you want to update the changes?',
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonText: 'Update',
                        denyButtonText: `Don't Update`,
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
                                    $('#update_program_item_modal').modal('hide');
                                });
                            }
                        });
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        });

    // Modal close function
    $('#update_program_item_modal').on('hidden.bs.modal', function(){

        $('.show_append_update_form').remove();
        // $('#append_start_here').remove();
        

        // $(this).find('form').trigger('reset');
        // $('#selectProgramProvince').append('<option value="">-- Select Province --</option>').prop('disabled', true);

    });
</script>