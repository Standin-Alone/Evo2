<script src="{{ url('assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
<script src="{{ url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js') }}"></script>

{{-- Select2 plugin --}}
<script src="{{ url('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

{{-- Validation --}}
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#supplier_profile_update_form').ready(function(){
        $('#supplier_profile_update_form').validate({
            errorClass: "invalid",
               validClass: "valid",
            rules: {
                mainSupplierName: {
                    required: true,
                },
                main_complete_address: {
                    required: true,
                },
                mainSelectSupplierRegion: {
                    required: true,
                },
                mainSelectProvince: {
                    required: true,
                },
                mainSelectCity: {
                    required: true,
                },
                mainSelectSupplierBarangay: {
                    required: true,
                },
                main_business_permit: {
                    required: true,
                },
                main_email: {
                    required: true,
                    email: true,
                },
                main_contact_no: {
                    required: true,
                    number: true,
                    minlength:11,
                    maxlength:12,
                },
                main_first_name: {
                    required: true,
                },
                main_last_name: {
                    required: true,
                },
                select_main_owner_bank_name: {
                    required: true,
                },
                main_bank_account_name: {
                    required: true,
                },
                main_bank_account_no: {
                    required: true,
                    number: true
                },
                main_phone_no: {
                    required: true,
                    number: true,
                    minlength:8,
                    maxlength:8,
                },
            },
            messages: {
                mainSupplierName: '<div class="text-danger"> *The Supplier Name field is required! </div>',
                main_complete_address: '<div class="text-danger">*The Complete Address field is required!</div>',
                mainSelectSupplierRegion: '<div class="text-danger"> *Selected Region is required! </div>',
                mainSelectProvince: '<div class="text-danger"> *Selected Province is required! </div>',
                mainSelectCity: '<div class="text-danger"> *Selected Municipality/City is required! </div>',
                mainSelectSupplierBarangay: '<div class="text-danger"> *Selected Barangay is required! </div>',
                main_business_permit: '<div class="text-danger"> *The Business Permit is required! </div>',
                main_email: {
                                required: '<div class="text-danger">*The Email field is required!</div>',
                                email: '<div class="text-danger">*Invalid email!</div>',
                },
                main_contact_no: {
                                    required:'<div class="text-danger">*The Contact No. field is required!</div>',
                                    number: '<div class="text-danger">*Please enter a valid number.</div>',
                                    minlength:'<div class="text-danger">*Please enter 11 or 12 digit phone number.</div>', 
                                    maxlength:'<div class="text-danger">*The minimum value are 11 or 12 digits.</div>',
                },
                main_first_name: '<div class="text-danger">*The First Name field is required!</div>',
                main_last_name: '<div class="text-danger">*The Last Name field is required!</div>',
                select_main_owner_bank_name: '<div class="text-danger">*Selected Bank is required!</div>',
                main_bank_account_name: '<div class="text-danger">*The Bank Account Name field is required!</div>',
                main_bank_account_no: '<div class="text-danger">*The Bank Account No. field is required!</div>',
                main_phone_no: {
                                    required: '<div class="text-danger">*The Phone No. field is required!</div>',
                                    number: '<div class="text-danger">*Please enter a valid number.</div>',
                                    minlength:'<div class="text-danger">*Please enter 8 digit phone number.</div>', 
                                    maxlength:'<div class="text-danger">*The minimum value are 8 digits.</div>',
                },
            }, 
            // Customize placement of created message error labels. 
            errorPlacement: function(error, element) {
                error.appendTo( element.parent().find(".error_msg") );
                $('span.error_form').remove();
            }
        });
    });
    $(document).on('submit', 'form#supplier_profile_update_form', function(e){
        e.preventDefault();
        
        var route = "{{ route('supplier_profile_module.send_update_profile') }}";

        var main_region                         = $('.rfo_approver_main_region').val();
        var main_supplier_id                    = $('.main_supplier_id').val();
        var main_supplier_group_id              = $('.main_supplier_group_id').val();
        var main_first_name                     = $('.main_first_name').val();
        var main_middle_name                    = $('.main_middle_name').val();
        var main_last_name                      = $('.main_last_name').val();
        var main_ext_name                       = $('.main_ext_name').val();
        var main_email                          = $('.main_email').val();
        var main_contact_no                     = $('.main_contact_no').val();
        var main_complete_address               = $('.main_complete_address').val();
        var select_main_owner_bank_long_name    = $('.select_main_owner_bank_name').find(":selected").text();
        var select_main_owner_bank_short_name   = $('.select_main_owner_bank_name').val();
        var main_bank_account_name              = $('.main_bank_account_name').val();
        var main_bank_account_no                = $('.main_bank_account_no').val();
        var main_phone_no                       = $('.main_phone_no').val();
        var main_supplier_group                 = $('.mainSupplierGroup').val();
        var main_supplier_name                  = $('.mainSupplierName').val();
        var main_select_supplier_region         = $('.mainSelectSupplierRegion').find(":selected").text();
        var main_select_province                = $('.mainSelectProvince').find(":selected").text();
        var main_select_city                    = $('.mainSelectCity').find(":selected").text();
        var main_select_supplier_barangay       = $('.mainSelectSupplierBarangay').find(":selected").text();
        var main_business_permit                = $('.main_business_permit').val();
        var main_reg_code                       = $('.mainSelectSupplierRegion').val();
        var main_prov_code                      = $('.mainSelectProvince').val();
        var main_mun_code                       = $('.mainSelectCity').val();
        var main_brgy_code                      = $('.mainSelectSupplierBarangay').val();
        var main_supplier_type                  = $('.rfo_approver_main_supplier_type').val();

        Swal.fire({
                position: 'center',
                title: 'Do you want to update the changes?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Update',
                denyButtonText: `Cancel Update`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    let timerInterval
                    Swal.fire({
                                title: 'Saving profile latest changes...',
                                // html: 'I will close in <b></b> milliseconds.',
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()

                                    $.ajax({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            method: 'POST',
                                            url: route,
                                            data: {
                                                main_region:main_region,
                                                main_supplier_id:main_supplier_id,
                                                main_supplier_group_id:main_supplier_group_id,
                                                main_first_name:main_first_name,
                                                main_middle_name:main_middle_name,
                                                main_last_name:main_last_name,
                                                main_ext_name:main_ext_name,
                                                main_email:main_email,
                                                main_contact_no:main_contact_no,
                                                main_complete_address:main_complete_address,
                                                select_main_owner_bank_long_name:select_main_owner_bank_long_name,
                                                select_main_owner_bank_short_name:select_main_owner_bank_short_name,
                                                main_bank_account_name:main_bank_account_name,
                                                main_bank_account_no:main_bank_account_no,
                                                main_phone_no:main_phone_no,
                                                main_supplier_group:main_supplier_group,
                                                main_supplier_name:main_supplier_name,
                                                main_select_supplier_region:main_select_supplier_region,
                                                main_select_province:main_select_province,
                                                main_select_city:main_select_city,
                                                main_select_supplier_barangay:main_select_supplier_barangay,
                                                main_business_permit:main_business_permit,
                                                main_reg_code:main_reg_code,
                                                main_prov_code:main_prov_code,
                                                main_mun_code:main_mun_code,
                                                main_brgy_code:main_brgy_code,
                                                main_supplier_type:main_supplier_type,
                                            },
                                            success: function(success_response){

                                            },
                                    });
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            Swal.fire({
                                                        title: 'Sending notification to RFO Approval about the profile updating...',
                                                        timer: 3000,
                                                        timerProgressBar: true,
                                                        didOpen: () => {
                                                            Swal.showLoading()
                                                        },
                                                        willClose: () => {
                                                            clearInterval(timerInterval)
                                                        }
                                            }).then((result) => {
                                                if(result.dismiss === Swal.DismissReason.timer){
                                                    $break = "<br />";
                                                    Swal.fire({
                                                        position: 'center',
                                                        icon: 'success',
                                                        title: 'Sending Complete!'+ $break +'Wait for the RFO Approval to notify you for the confirmation of the profile updating.',
                                                        showConfirmButton: true,
                                                    }).then(function(){ 
                                                        window.location.href = "{{ route('main.page')}} ";
                                                    }); 
                                                }
                                            })
                                        }
                    })
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info');
                }
        })
    });

    // Province dropdown
    $(document).ready(function () {
        $('#mainSelectProvince').append('<option value="">-- Select Province --</option>').prop('disabled', true);
        $('#mainSelectSupplierRegion').on('change', function () {
            var reg_code = $(this).val();
            if (reg_code) {
                var route = "{{route('rfo_approval_module.supplier_province',['reg_code'=>':id'])}}".replace(':id', reg_code);
               
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        // console.log(provinces);
                        if (provinces) {
                            $('select[name="mainSelectProvince"]').empty();
                            $('select[name="mainSelectProvince"]').focus;
                            $('select[name="mainSelectProvince"]').append('<option value="">-- Select Province --</option>');
                            $.each(provinces, function (key, province) {
                                $('select[name="mainSelectProvince"]').append('<option value="' + province.prov_code + '">' + province.prov_name + ' </option>').prop('disabled', false).prop('selected', true);
                            }); 
                        } else {
                            $('#mainSelectProvince').empty();
                        }
                    }
                });
            } else {
                $('#mainSelectProvince').empty();
            }
        });
    });
    
    // City dropdown
    $(document).ready(function () {
        $('#mainSelectCity').append('<option value="">-- Select Municipalties --</option>').prop('disabled', true);
        $('#mainSelectProvince').on('change', function () {
            var reg_code = $('select[name="mainSelectSupplierRegion"]').val();
            var prov_code = $(this).val();
            if (prov_code) {
                var route = "{{route('rfo_approval_module.supplier_city')}}"+"/"+reg_code+"/"+prov_code;
                
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (cities) {
                        // console.log(provinces);
                        if (cities) {
                            $('select[name="mainSelectCity"]').empty();
                            $('select[name="mainSelectCity"]').focus;
                            $('select[name="mainSelectCity"]').append('<option value="">-- Select Municipalties --</option>');
                            $.each(cities, function (key, city) {
                                $('select[name="mainSelectCity"]').append('<option value="' + city.mun_code + '">' + city.mun_name + ' </option>').prop('disabled', false).prop('selected', true);
                            }); 
                        } else {
                            $('#mainSelectCity').empty();
                        }
                    }
                });
            } else {
                $('#mainSelectCity').empty();
            }
        });
    });

    // Barangay dropdown
    $(document).ready(function () {
        $('#mainSelectSupplierBarangay').append('<option value="">-- Select Barangay --</option>').prop('disabled', true);
        $('#mainSelectCity').on('change', function () {
            var reg_code = $('select[name="mainSelectSupplierRegion"]').val();
            var prov_code = $('select[name="mainSelectProvince"]').val();
            var mun_code = $(this).val();
            if (mun_code) {
                var route = "{{route('rfo_approval_module.supplier_barangay')}}"+"/"+reg_code+"/"+prov_code+"/"+mun_code;
                console.log(route);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (barangay) {
                        // console.log(provinces);
                        if (barangay) {
                            $('select[name="mainSelectSupplierBarangay"]').empty();
                            $('select[name="mainSelectSupplierBarangay"]').focus;
                            $('select[name="mainSelectSupplierBarangay"]').append('<option value="">-- Select Barangay --</option>');
                            $.each(barangay, function (key, brgy) {
                                $('select[name="mainSelectSupplierBarangay"]').append('<option value="' + brgy.bgy_code + '">' + brgy.bgy_name + ' </option>').prop('disabled', false).prop('selected', true);
                            }); 
                        } else {
                            $('#mainSelectSupplierBarangay').empty();
                        }
                    }
                });
            } else {
                $('#mainSelectSupplierBarangay').empty();
            }
        });
    });
</script>