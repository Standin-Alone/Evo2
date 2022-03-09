<script type="text/javascript">
    $(function() {
        var table = $('#supps_and_merchs_datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            // dom: 'lBfrtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: "{{route('rfo_approval_module.list_of_merchs_and_supps')}}",
            columns: [
                {data: 'supplier_name', name: 'supplier_name', orderable: true, searchable: true},
                {data: 'address', name: 'address', orderable: true, searchable: true},
                {data: 'supplier_group', name: 'supplier_group', orderable: true, searchable: true},
                {data: 'bank_account_no', name: 'bank_account_no', orderable: true, searchable: true},
                {data: 'email', name: 'email', orderable: true, searchable: true},
                {data: 'contact', name: 'contact', orderable: true, searchable: true},
                {data: 'action', name: 'action'},
            ],
            responsive: {
                details: {
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                        tableClass: 'table'
                    } )
                }
            }
        });
    });

    $(document).on('click', '#update_profile_btn', function (e) {
        e.preventDefault();

        var supplier_id = $(this).data('supplier_id');
        var route = "{{ route('rfo_approval_module.view_temp_supplier_profile', ['supplier_id' => ':id']) }}".replace(':id', supplier_id);

        var role = $(this).data('role');

        var append_update_role = "";

        // append the fullname to Update status modal
        append_update_role += '<span class="update_role_value">'+ role + '<span>';

        $("span.update_role").append(append_update_role);

        $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'GET',
                url: route,
                data: {
                        supplier_id:supplier_id,
                },
                success: function(datas){
                    var temp_supp = datas[0];
                    var geo_map  = datas[1];
                    
                    var html  = "";

                    $.each(temp_supp, function (key, ts){    
                        $.each(geo_map, function (key, gm){  
                                    html += '<div id="update_supplier_profile" class="col-lg-12">';
                                    html += '<input class="form-control update_supplier_id" name="update_supplier_id" type="hidden" value="'+ ts.supplier_id +'" placeholder="" />';
                                    html += '<input class="form-control update_supplier_group_id" name="update_supplier_group_id" type="hidden" value="'+ ts.supplier_group_id +'" placeholder="">';
                                    // html += '<input class="form-control rfo_approver_update_region" name="rfo_approver_update_region" type="hidden" value="" placeholder="" />';
                                    html += '<input class="form-control rfo_approver_update_supplier_type" name="rfo_approver_update_supplier_type" type="hidden" value="'+ ts.supplier_type +'" placeholder="" />';
                                    html += '<input class="form-control rfo_approver_update_supplier_type" name="update_owner_bank_short_name" type="hidden" value="'+ ts.bank_short_name +'" placeholder="" />';
                                    html += '<input class="form-control rfo_approver_update_supplier_type" name="update_geo_code" type="hidden" value="'+ gm.geo_code +'" placeholder="" />';

                                    html +=        '<div class="mb-3">';
                                    html +=            '<label class="form-label">Supplier Group: <span class="text-danger">*</span></label>';
                                    html +=            '<input class="form-control updateSupplierGroup" name="updateSupplierGroup" value="'+ts.supplier_name+'" type="text" readonly />';
                                    html +=        '</div>';
                
                                    html +=         '<div class="mb-3">';
                                    html +=             '<label class="form-label">Supplier Name: <span class="text-danger">*</span></label>';
                                    html +=             '<input class="form-control updateSupplierName" name="updateSupplierName" value="'+ts.supplier_name+'" type="text" placeholder="Enter Supplier Name" readonly/>';
                                    html +=         '</div>';
                
                                    html +=           '<div class="mb-3">';
                                    html +=               '<label class="form-label">Complete Address: <span class="text-danger">*</span></label>';
                                    html +=               '<textarea class="form-control update_complete_address" name="update_complete_address" value="'+ ts.address +'" rows="3" placeholder="Enter Complete Address" required readonly>' + ts.address+ '</textarea>';
                                    html +=           '</div>'  ;
                
                                    html +=     '<div class="row mb-3">';
                                    html +=         '<div class="col-md-3 mb-3">';
                                    html +=             '<label class="form-label">Region: <span class="text-danger">*</span></label>';
                                    html +=             '<select id="updateSelectSupplierRegion" class="form-control updateSelectSupplierRegion" name="updateSelectSupplierRegion" readonly>';
                                    html +=                 '<option value="'+ ts.reg +'" selected>'+ gm.reg_name +'</option>';
                                    html +=             '</select>';
                                    html +=         '</div>';
                                    
                                    html +=         '<div class="col-md-3 mb-3">';
                                    html +=             '<label class="form-label">Province: <span class="text-danger">*</span></label>';
                                    html +=             '<select id="updateSelectProvince" class="form-control updateSelectProvince" name="updateSelectProvince" readonly>';
                                    html +=                 '<option value="'+ ts.prv +'" selected>'+ gm.prov_name +'</option>';
                                    html +=             '</select>';
                                    html +=         '</div>';
                
                                    html +=               '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">Municipality/City: <span class="text-danger">*</span></label>';
                                    html +=                    '<select id="updateSelectCity" class="form-control updateSelectCity" name="updateSelectCity" readonly>';
                                    html +=                        '<option value="'+ ts.mun +'" selected>'+ gm.mun_name +'</option>';
                                    html +=                    '</select>';
                                    html +=                '</div>';
                
                                    html +=                '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">Barangay: <span class="text-danger">*</span></label>';
                                    html +=                    '<select id="updateSelectSupplierBarangay" class="form-control updateSelectSupplierBarangay" name="updateSelectSupplierBarangay" readonly>';
                                    html +=                        '<option value="'+ ts.brgy +'" selected>'+ gm.bgy_name +'</option>';
                                    html +=                    '</select> '
                                    html +=                '</div>';
                                    html +=           '</div>' ;  
                                                
                                    html +=            '<div class="row mb-3">';
                                    html +=                '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">Business Permit: <span class="text-danger">*</span></label>'
                                    html +=                    '<input class="form-control update_business_permit" name="update_business_permit" type="text" value="'+ ts.business_permit +'" placeholder="Enter Business Permit" readonly/>'
                                    html +=                '</div>';
                
                                    html +=                '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">Email: <span class="text-danger">*</span></label>';
                                    html +=                    '<input class="form-control update_email" name="update_email" type="email" value="'+ ts.email +'" placeholder="Enter Email" readonly/>';
                                    html +=                '</div>'; 
                
                                    html +=                '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">Contact No: <span class="text-danger">*</span></label>';
                                    html +=                    '<input class="form-control update_contact_no" name="update_contact_no" type="number" value="'+ ts.contact +'" placeholder="Enter Contact No." readonly/>';
                                    html +=                '</div>';  
                                    html +=            '</div>'; 
                
                                    html +=            '<div class="row mb-3">'; 
                                    html +=                '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">First Name: <span class="text-danger">*</span></label>';
                                    html +=                    '<input class="form-control update_first_name" name="update_first_name" type="text" value="'+ ts.owner_first_name +'" placeholder="Enter First Name" readonly/>';
                                    html +=                    '<span class="error_msg"></span>';
                                    html +=                '</div>';
                
                                    if(ts.owner_middle_name != undefined || ts.owner_middle_name != null){
                                        html +=                '<div class="col-md-3 mb-3">';
                                        html +=                    '<label class="form-label">Middle Name: </label>';
                                        html +=                    '<input class="form-control update_middle_name" name="update_middle_name" type="text" value="'+ ts.owner_middle_name +'" placeholder="Enter Middle Name" readonly/>';
                                        html +=                '</div>';
                                    }else{
                                        html +=                '<div class="col-md-3 mb-3">';
                                        html +=                    '<label class="form-label">Middle Name: </label>';
                                        html +=                    '<input class="form-control update_middle_name" name="middle_name" type="text" value="" placeholder="N/A" readonly/>';
                                        html +=                '</div>';
                                        html += '<input class="form-control rfo_approver_update_supplier_type" name="update_middle_name" type="hidden" value="'+ ts.owner_middle_name +'" />';
                                    }

                
                                    html +=                '<div class="col-md-3 mb-3">';
                                    html +=                    '<label class="form-label">Last Name: <span class="text-danger">*</span></label>';
                                    html +=                    '<input class="form-control update_last_name" name="update_last_name" type="text" value="'+ ts.owner_last_name +'" placeholder="Enter Last Name" readonly/>';
                                    html +=                    '<span class="error_msg"></span>';
                                    html +=               '</div>';
                
                                    if(ts.owner_ext_name != undefined || ts.owner_ext_name != null ){
                                        html +=                '<div class="col-md-3 mb-3">';
                                        html +=                    '<label class="form-label">Extention Name: </label>';
                                        html +=                    '<input class="form-control update_ext_name" name="update_ext_name" type="text" value="'+ ts.owner_ext_name +'" placeholder="Enter Extention Name" readonly/>';
                                        html +=                '</div>';                                             
                                        html +=            '</div>';
                                    }else{
                                        html +=                '<div class="col-md-3 mb-3">';
                                        html +=                    '<label class="form-label">Extention Name: </label>';
                                        html +=                    '<input class="form-control update_ext_name" name="ext_name" type="text" value="" placeholder="N/A" readonly/>';
                                        html +=                '</div>';                                             
                                        html +=            '</div>';
                                        html += '<input class="form-control rfo_approver_update_supplier_type" name="update_ext_name" type="hidden" value="'+ ts.owner_ext_name +'" />';
                                    }

                                    html +=            '<div class="row mb-3"> ';                                               
                                    html +=                '<div class="col-md-4 mb-3">';
                                    html +=                    '<label class="form-label">Bank: <span class="text-danger">*</span></label>';
                                    html +=                    '<select class="form-control update_owner_bank_name" name="update_owner_bank_name" readonly>';
                                    html +=                        '<option value="'+ ts.bank_long_name +'" selected>'+ ts.bank_long_name +'</option>';
                                    html +=                    '</select>' ;
                                    html +=                '</div>';
                                    html +=                '<div class="col-md-4 mb-3">';
                                    html +=                    '<label class="form-label">Account Name: <span class="text-danger">*</span></label>';
                                    html +=                    '<input class="form-control update_bank_account_name" name="update_bank_account_name" type="text" value="'+ ts.bank_account_name +'" placeholder="Enter Bank Account Name" readonly/>';
                                    html +=                '</div>';
                                    html +=                '<div class="col-md-4 mb-3">';
                                    html +=                    '<label class="form-label">Account No.: <span class="text-danger">*</span></label>';
                                    html +=                    '<input class="form-control update_bank_account_no" name="update_bank_account_no" type="number" value="'+ ts.bank_account_no +'" placeholder="Enter Bank Account Number" readonly/>';
                                    html +=                '</div>';                                             
                                    html +=            '</div>';   
                    
                                    html +=           '<div class="mb-3">';
                                    html +=                '<label class="form-label">Phone No.: <span class="text-danger">*</span></label>';
                                    html +=                '<input class="form-control update_phone_no" name="update_phone_no" type="number" value="'+  ts.owner_phone +'" placeholder="Enter Phone No." readonly/>';
                                    html +=            '</div>';
                                    html += '</div>';

                                    $("#update_profile_modal #update_supplier_profile_modal_body").append(html);

                                    $(document).on('submit', '#update_profile_form', function (e) {
                                        e.preventDefault();

                                        var form_data = $(this);

                                        redirect_route = "{{ route('rfo_approval_module.list_of_merchs_and_supps') }}";

                                        Swal.fire({
                                                title: 'Do you want to update the changes?',
                                                showDenyButton: true,
                                                showCancelButton: true,
                                                confirmButtonText: 'Update',
                                                denyButtonText: `Don't Update`,
                                        }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                if (result.isConfirmed) {
                                                    // Swal.fire('Saved!', '', 'success')

                                                    $.ajax({
                                                            headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                            },
                                                            method: 'POST',
                                                            url: "{{ route('supplier_profile_module.update_supplier_profile') }}",
                                                            data: form_data.serialize(),
                                                            success: function(success_response){
                                                                Swal.fire({
                                                                position: 'center',
                                                                icon: 'success',
                                                                title: success_response.message,
                                                                showConfirmButton: true,
                                                                }).then(function(){ 
                                                                    // window.location.href = redirect_route;
                                                                    $("#supps_and_merchs_datatable").DataTable().ajax.reload();
                                                                    $('#update_profile_modal').modal('hide');
                                                                });
                                                            },
                                                            error: function(error_response){
                                                                Swal.fire({
                                                                position: 'center',
                                                                icon: 'error',
                                                                title: 'Oops...',
                                                                text: error_response.message,
                                                                showConfirmButton: true,
                                                                }).then(function(){ 
                                                                    // window.location.href = redirect_route;
                                                                    $("#supps_and_merchs_datatable").DataTable().ajax.reload();
                                                                    $('#update_profile_modal').modal('hide');
                                                                });
                                                            }
                                                    });

                                                } else if (result.isDenied) {
                                                    Swal.fire('Changes are not saved', '', 'info')
                                                }
                                        })
                                    });
                        });
                    });

                },
        });
    });

    // Show current supplier profile
    $(document).on('click', '#view_profile_btn', function (e) {
        e.preventDefault();

        var supplier_id = $(this).data('supplier_id');
        var route = "{{ route('rfo_approval_module.show_current_supplier_profile', ['supplier_id' => ':id']) }}".replace(':id', supplier_id);

        var role = $(this).data('role');

        var append_view_role = "";

        // append the fullname to Update status modal
        append_view_role += '<span class="view_role_value">'+ role + '<span>';

        $("span.view_role").append(append_view_role);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: route,
            data: {
                    supplier_id:supplier_id,
            },
            success: function(data){
                var current_supp = data[0];
                var geo_map  = data[1];
                    
                var html1  = "";
                
                var na = "N/A";
                
                $.each(current_supp, function (key, cs){
                    $.each(geo_map, function (key, gm){

                        if(cs.supplier_group != undefined || cs.supplier_group != null){
                            html1 += '<div id="view_supplier_profile" class="col-lg-12">';
                            html1 +=    '<div class="mb-3">';
                            html1 +=         '<label class="form-label">Supplier Group:</label>';
                            html1 +=         '<input class="form-control view_current_SupplierGroup" name="view_current_SupplierGroup" value="'+ cs.supplier_group +'" type="text" placeholder="N/A" readonly />';
                            html1 +=     '</div>'; 
                        }else{
                            html1 += '<div id="view_supplier_profile" class="col-lg-12">';
                            html1 +=    '<div class="mb-3">';
                            html1 +=         '<label class="form-label">Supplier Group:</label>';
                            html1 +=         '<input class="form-control view_current_SupplierGroup" name="view_current_SupplierGroup" value="" type="text" placeholder="N/A" readonly />';
                            html1 +=     '</div>'; 
                        }

                        if(cs.supplier_name != undefined || cs.supplier_name != null){
                            html1 +=     '<div class="mb-3">';
                            html1 +=         '<label class="form-label">Supplier Name:</label>';
                            html1 +=         '<input class="form-control view_current_SupplierName" name="view_current_SupplierName" value="'+ cs.supplier_name +'" type="text" placeholder="N/A" readonly />';
                            html1 +=     '</div>';
                        }else{
                            html1 +=     '<div class="mb-3">';
                            html1 +=         '<label class="form-label">Supplier Name:</label>';
                            html1 +=         '<input class="form-control view_current_SupplierName" name="view_current_SupplierName" value="" type="text" placeholder="N/A" readonly />';
                            html1 +=     '</div>';
                        }

                        if(cs.address != undefined || cs.address != null){
                            html1 +=     '<div class="mb-3">';
                            html1 +=         '<label class="form-label">Complete Address:</label>';
                            html1 +=         '<input class="form-control view_current_complete_address" name="view_current_complete_address" value="'+cs.address+'" placeholder="" readonly>';
                            html1 +=     '</div>'; 
                        }else{
                            html1 +=     '<div class="mb-3">';
                            html1 +=         '<label class="form-label">Complete Address:</label>';
                            html1 +=         '<textarea class="form-control view_current_complete_address" name="view_current_complete_address" value="" rows="3" placeholder="N/A" readonly>';
                            html1 +=         '</textarea>';
                            html1 +=     '</div>'; 
                        }

                        if(gm.reg_name != undefined || gm.reg_name != null){
                            html1 +=     '<div class="row mb-3">';
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Region:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_region" value="'+ gm.reg_name +'"placeholder="N/A" readonly>';
                            html1 +=         '</div>';
                        }else{
                            html1 +=     '<div class="row mb-3">';
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Region:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_region" value=""placeholder="N/A" readonly>';
                            html1 +=         '</div>';
                        }

                        if(gm.prov_name != undefined || gm.prov_name != null){
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Province:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_province" value="'+ gm.prov_name +'" placeholder="N/A" readonly>';
                            html1 +=         '</div>';
                        }else{
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Province:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_province" value="" placeholder="N/A" readonly>';
                            html1 +=         '</div>';
                        }

                        if(gm.prov_name != undefined || gm.prov_name != null){
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Municipality/City:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_municipality" value="'+ gm.mun_name +'" placeholder="N/A" readonly>';
                            html1 +=         '</div>'; 
                        }else{
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Municipality/City:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_municipality" value="" placeholder="N/A" readonly>';
                            html1 +=         '</div>'; 
                        }

                        if(gm.bgy_name != undefined || gm.bgy_name != null){
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Barangay:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_barangay" value="'+ gm.bgy_name +'" placeholder="N/A" readonly>';
                            html1 +=         '</div>';
                            html1 +=     '</div>';  
                        }else{
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Barangay:</label>';
                            html1 +=             '<input type="text"  class="form-control view_current_barangay" value="" placeholder="N/A" readonly>';
                            html1 +=         '</div>';
                            html1 +=     '</div>';  
                        } 
                    
                        if(cs.business_permit != undefined || cs.business_permit != null){
                            html1 +=     '<div class="row mb-3">';
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Business Permit:</label>';
                            html1 +=             '<input class="form-control view_current_business_permit" name="view_current_business_permit" type="text" value="'+ cs.business_permit +'" placeholder="N/A" readonly/>';
                            html1 +=         '</div>';
                        }else{
                            html1 +=     '<div class="row mb-3">';
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Business Permit:</label>';
                            html1 +=             '<input class="form-control view_current_business_permit" name="view_current_business_permit" type="text" value="" placeholder="N/A" readonly/>';
                            html1 +=         '</div>';
                        }

                        if(cs.email != undefined || cs.email != null){
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Email:</label>';
                            html1 +=             '<input class="form-control view_current_email" name="view_current_email" type="email" value="'+ cs.email +'" placeholder="N/A" readonly/>';
                            html1 +=         '</div>';
                        }else{
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Email:</label>';
                            html1 +=             '<input class="form-control view_current_email" name="view_current_email" type="email" value="" placeholder="N/A" readonly/>';
                            html1 +=         '</div>';
                        }

                        if(cs.contact != undefined || cs.contact != null){
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Contact No:</label>';
                            html1 +=             '<input class="form-control view_current_contact_no" name="view_current_contact_no" type="number" value="'+ cs.contact +'" placeholder="N/A" readonly/>';
                            html1 +=         '</div>'; 
                            html1 +=     '</div>';
                        }else{
                            html1 +=         '<div class="col-md-3 mb-3">';
                            html1 +=             '<label class="form-label">Contact No:</label>';
                            html1 +=             '<input class="form-control view_current_contact_no" name="view_current_contact_no" type="number" value="" placeholder="N/A" readonly/>';
                            html1 +=         '</div>'; 
                            html1 +=     '</div>';
                        }

                        if(cs.owner_first_name != undefined || cs.owner_first_name != null){
                            html1 +=      '<div class="row mb-3">';
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">First Name:</label>';
                            html1 +=                 '<input class="form-control view_current_first_name" name="view_current_first_name" type="text" value="'+ cs.owner_first_name +'" placeholder="N/A" readonly/>';
                            html1 +=             '</div>';
                        }else{
                            html1 +=      '<div class="row mb-3">';
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">First Name:</label>';
                            html1 +=                 '<input class="form-control view_current_first_name" name="view_current_first_name" type="text" value="" placeholder="N/A" readonly/>';
                            html1 +=             '</div>';
                        }

                        if(cs.owner_middle_name != undefined || cs.owner_middle_name != null){
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">Middle Name: </label>';
                            html1 +=                 '<input class="form-control view_current_middle_name" name="view_current_middle_name" type="text" value="'+ cs.owner_middle_name +'" placeholder="N/A" readonly/>';
                            html1 +=             '</div>';
                        }else{
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">Middle Name: </label>';
                            html1 +=                 '<input class="form-control view_current_middle_name" name="view_current_middle_name" type="text" value="" placeholder="N/A" readonly/>';
                            html1 +=             '</div>';
                        }

                        if(cs.owner_last_name != undefined || cs.owner_last_name != null){
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">Last Name:</label>';
                            html1 +=                 '<input class="form-control view_current_last_name" name="view_current_last_name" type="text" value="'+ cs.owner_last_name +'" placeholder="N/A" readonly/>';
                            html1 +=             '</div>';
                        }else{
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">Last Name:</label>';
                            html1 +=                 '<input class="form-control view_current_last_name" name="view_current_last_name" type="text" value="" placeholder="N/A" readonly/>';
                            html1 +=             '</div>';
                        }

                        if(cs.owner_ext_name != undefined || cs.owner_ext_name != null){
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">Extention Name: </label>';
                            html1 +=                 '<input class="form-control view_current_ext_name" name="view_current_ext_name" type="text" value="'+ cs.owner_ext_name +'" placeholder="N/A" readonly/>';
                            html1 +=             '</div>'; 
                            html1 +=      '</div>';
                        }else{
                            html1 +=             '<div class="col-md-3 mb-3">';
                            html1 +=                 '<label class="form-label">Extention Name: </label>';
                            html1 +=                 '<input class="form-control view_current_ext_name" name="view_current_ext_name" type="text" value="" placeholder="N/A" readonly/>';
                            html1 +=             '</div>'; 
                            html1 +=      '</div>';
                        }

                        if(cs.bank_long_name != undefined || cs.bank_long_name != null){
                            html1 +=      '<div class="row mb-3">';        
                            html1 +=         '<div class="col-md-4 mb-3">';
                            html1 +=             '<label class="form-label">Bank:</label>';
                            html1 +=             '<input class="form-control view_current_bank_name" name="view_current_bank_name" type="text" value="'+ cs.bank_long_name +'" placeholder="N/A" readonly/>';              
                            html1 +=         '</div>';
                        }else{
                            html1 +=      '<div class="row mb-3">';        
                            html1 +=         '<div class="col-md-4 mb-3">';
                            html1 +=             '<label class="form-label">Bank:</label>';
                            html1 +=             '<input class="form-control view_current_bank_name" name="view_current_bank_name" type="text" value="" placeholder="N/A" readonly/>';              
                            html1 +=         '</div>';
                        }

                        if(cs.bank_account_name != undefined || cs.bank_account_name != null){
                            html1 +=         '<div class="col-md-4 mb-3">';
                            html1 +=             '<label class="form-label">Account Name:</label>';
                            html1 +=             '<input class="form-control view_current_bank_account_name" name="view_current_bank_account_name" type="text" value="'+ cs.bank_account_name  +'" placeholder="N/A" readonly/>';
                            html1 +=         '</div>';
                        }else{
                            html1 +=         '<div class="col-md-4 mb-3">';
                            html1 +=             '<label class="form-label">Account Name:</label>';
                            html1 +=             '<input class="form-control view_current_bank_account_name" name="view_current_bank_account_name" type="text" value="" placeholder="N/A" readonly/>';
                            html1 +=         '</div>';
                        }
                        
                        if(cs.bank_account_no != undefined || cs.bank_account_no != null){
                            html1 +=         '<div class="col-md-4 mb-3">';
                            html1 +=             '<label class="form-label">Account No.:</label>';
                            html1 +=             '<input class="form-control view_current_bank_account_no" name="view_current_bank_account_no" type="number" value="'+ cs.bank_account_no +'" placeholder="N/A" readonly/>';            
                            html1 +=         '</div>'; 
                            html1 +=       '</div>'; 
                        }else{
                            html1 +=         '<div class="col-md-4 mb-3">';
                            html1 +=             '<label class="form-label">Account No.:</label>';
                            html1 +=             '<input class="form-control view_current_bank_account_no" name="view_current_bank_account_no" type="number" value="" placeholder="N/A" readonly/>';            
                            html1 +=         '</div>'; 
                            html1 +=       '</div>'; 
                        }
            
                        if(cs.owner_phone != undefined || cs.owner_phone != null){
                            html1 +=       '<div class="mb-3">';
                            html1 +=             '<label class="form-label">Phone No.:</label>';
                            html1 +=             '<input class="form-control view_current_phone_no" name="view_current_phone_no" type="number" value="'+ cs.owner_phone +'" placeholder="N/A" readonly/>';
                            html1 +=        '</div>';
                            html1 += '</div>';
                        }else{
                            html1 +=       '<div class="mb-3">';
                            html1 +=             '<label class="form-label">Phone No.:</label>';
                            html1 +=             '<input class="form-control view_current_phone_no" name="view_current_phone_no" type="number" value="" placeholder="N/A" readonly/>';
                            html1 +=        '</div>';
                            html1 += '</div>';
                        }

                        $("#view_current_profile_modal #current_profile_modal_body").append(html1);
                    })
                })  
            }
        });
    });

    // $('#update_profile_modal').on('click', function () {
    //     // $('$update_role');
    //     console.log('nasa update modal');
    // });

    // $('#view_current_profile_modal').on('click', function () {
    //     $('$view_role');
    // });

        // $('.modal-title').text('Show building');
        // $('.view_current_SupplierGroup').val($(this).data('supplier_group'));
        // $('.view_current_SupplierName').val($(this).data('supplier_name'));
        // $('.view_current_complete_address').val($(this).data('address'));
        // $('.view_current_region').val($(this).data('reg_name'));
        // $('.view_current_province').val($(this).data('prov_name'));
        // $('.view_current_municipality').val($(this).data('mun_name'));
        // $('.view_current_barangay').val($(this).data('bgy_name'));
        // $('.view_current_business_permit').val($(this).data('business_permit'));
        // $('.view_current_email').val($(this).data('email'));
        // $('.view_current_contact_no').val($(this).data('contact'));
        // $('.view_current_first_name').val($(this).data('owner_first_name'));
        // $('.view_current_middle_name').val($(this).data('owner_middle_name'));
        // $('.view_current_last_name').val($(this).data('owner_last_name'));
        // $('.view_current_ext_name').val($(this).data('owner_ext_name'));
        // $('.view_current_bank_name').val($(this).data('bank_long_name'));
        // $('.view_current_bank_account_name').val($(this).data('bank_account_name'));
        // $('.view_current_bank_account_no').val($(this).data('bank_account_no'));
        // $('.view_current_phone_no').val($(this).data('owner_phone'));

    $('#update_profile_modal').on('hidden.bs.modal', function(e)
    {
        $('#update_supplier_profile').remove();

        // remove the duplicate role
        $("span.update_role_value").remove();
    });

    $('#view_current_profile_modal').on('hidden.bs.modal', function(e)
    {
        $('#view_supplier_profile').remove();

        // remove the duplicate role
        $("span.view_role_value").remove();
    });
</script>