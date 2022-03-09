<script type="text/javascript">
    $(function() {
        var table = $('#account_activation_datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('rfo_approval_module.account_activation')}}",
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columns: [
                {data: 'company_name', name: 'company_name', orderable: true, searchable: true},
                {data: 'company_address', name: 'company_address', orderable: true, searchable: true},
                {data: 'title', name: 'title', orderable: true, searchable: true},
                {data: 'email', name: 'email', orderable: true, searchable: true},
                {data: 'fullname_column', name: 'fullname_column', orderable: true, searchable: true},
                {data: 'contact_no', name: 'contact_no', orderable: true, searchable: true},
                {data: 'reg_name', name: 'reg_name', orderable: true, searchable: true},
                // {data: 'prov', name: 'prov'},
                // {data: 'approve_by_fullname', name: 'approve_by_fullname'}
                {data: 'status', name: 'status', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false},
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

    // Account Activation Checklist Modal
    $(document).on('click', '#updateBtn', function (e) {
        e.preventDefault();

        var user_id = $(this).data('uuid');
        var program_id = $(this).data('program_id');
        var program_title = $(this).data('program_title');
        var fullname = $(this).data('fullname');

        var append_fullname = "";

        // append the fullname to Update status modal
        append_fullname += '<span class="user_name_value">'+ fullname + '<span>';

        $("span.user_name").append(append_fullname);

        $checklists = {!! json_encode($checklists) !!}
        $checklist_details = {!! json_encode($checklist_details) !!}
        $users = {!! json_encode($users) !!}

        // $("div.srn_input_class").hide();

        var clist_tbl = $checklists;
        var clist_details_tbl = $checklist_details;

        var user_id = $(this).data('uuid');

        var html = "";

        var docs = [];

        var clist_details_user_id = [];
    
        clist_details_user_id.push(true);

        if(clist_details_tbl == false){
            
            $.each(clist_tbl, function(key, clist){
                html += '<div class="item">'
                html +=     '<input class="clist_id" name="checklistbox[]" type="checkbox" value="'+ clist.checklist_id +'">';
                html +=     '<p class="text_list"> <b> '+clist.list+' </b> </p>';
                html += '</div>';
            });
            
        }else{
            $.each(clist_details_tbl, function (key, cDetails) {

                if(user_id == cDetails.user_id){ 
                    clist_details_user_id.push(cDetails.user_id);
                }

            });

            if( $.inArray(user_id, clist_details_user_id) == true ){

                $.each(clist_details_tbl, function (key, cDetails) {
                    if(user_id == cDetails.user_id){  

                        var cons = cDetails.status == 1 ? "checked" : "";

                        html += '<div class="item">';
                        html +=     '<input class="clist_id" name="checklistbox[]" type="checkbox" value="'+ cDetails.checklist_id +'" '+cons+'>';
                        html +=     '<p class="text_list"> <b> '+cDetails.list+' </b> </p>';
                        html += '</div>';
                    }
                });

            }else{

                $.each(clist_tbl, function(key, clist){
                    html += '<div class="item">'
                    html +=     '<input class="clist_id" name="checklistbox[]" type="checkbox" value="'+ clist.checklist_id +'">';
                    html +=     '<p class="text_list"> <b> '+clist.list+' </b> </p>';
                    html += '</div>';
                });

            }
        }

        // append check list items UI
        $("div.inbox").append(html);

        var arr = [];
        var clistid = [];
        var clistStatus = [];

        $(document).on('change', '.clist_id', function(e){
            var val01 = $('input[type="checkbox"]:checked').each(function(){
                return $(this).val();
            }).get();

            // Show "ACTIVE BUTTON" when all checkbox are checked!
            if(val01.length == 8){
                $('#btnSaveChecklist').prop('hidden', true);

                $('#btnActiveApprove').prop('hidden', false);

            }
            // Show "SAVE BUTTON" when not all checkbox are checked!
            else if(val01.length <= 7){
                $('#btnSaveChecklist').prop('hidden', false);
                    
                $('#btnActiveApprove').prop('hidden', true);
            }

        });

        // SAVE BUTTON when triggered;
        $('#btnSaveChecklist').on('click', function(e){
            e.preventDefault();

            $('.clist_id').each(function(){
                if($(this).is(":checked")){
                    var x = $(this).val();
                    var y = '1';
                    clistid.push(x);
                    clistStatus.push(y);
                }else{
                    var x = $(this).val();
                    var y = '0';
                    clistid.push(x);
                    clistStatus.push(y);
                }
            });

            var user_status = "2";
            var account_approval_status_code = "2";
            var account_status_name = "FOR CHECKING";

            inactive_account_requirement_checklist_send_to_controller(user_id,  program_id, program_title, clistid, clistStatus, user_status, account_approval_status_code, account_status_name);

        });

        // ACTIVE BUTTON when triggered;
        $(document).on('submit', 'form#update_user_status_form', function(e){
		e.preventDefault();

            $('.clist_id').each(function(){
                if($(this).is(":checked")){
                    var x = $(this).val();
                    var y = '1';
                    clistid.push(x);
                    clistStatus.push(y);
                }else{
                    var x = $(this).val();
                    var y = '0';
                    clistid.push(x);
                    clistStatus.push(y);
                }
            });

            var user_status = "1";
            var account_approval_status_code = "1";
            var account_status_name = "APPROVE";

            active_account_requirement_checklist_send_to_controller(user_id,  program_id, program_title, clistid, clistStatus, user_status, account_approval_status_code, account_status_name);

        });

    });

    function inactive_account_requirement_checklist_send_to_controller(user_id,  program_id, program_title, clistid, clistStatus, user_status, account_approval_status_code, account_status_name){

        Swal.fire({
            position: 'center',
            title: 'Do you want to save the checked requirements?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Save',
            denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let timerInterval
                Swal.fire({
                            title: 'Saving checked requirements...',
                            // html: 'I will close in <b></b> milliseconds.',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()

                                $.ajax({
                                        headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        method: 'PATCH',
                                        url: "{{ route('rfo_approval_module.create_user_checklist_details') }}",
                                        data: {
                                                user_id:user_id,
                                                program_id:program_id,
                                                program_title:program_title,
                                                clistid:clistid,
                                                clistStatus:clistStatus,
                                                user_status:user_status,
                                                account_approval_status_code:account_approval_status_code,
                                                account_status_name:account_status_name,
                                            },
                                        success: function(success_response){

                                        }
                                });
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                }).then((result) => {
                                    /* Read more about handling dismissals below */
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        Swal.fire({
                                                    title: 'Sending account status notification to Head Supplier...',
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
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'The checked requirements have been save successfully!',
                                                    showConfirmButton: true,
                                                }).then(function(){ 
                                                    window.location.href = "{{ route('rfo_approval_module.account_activation')}} ";
                                                    // $("#account_activation_datatable").DataTable().ajax.reload();
                                                    // $('#update_user_status_modal').modal('hide');
                                                }); 
                                            }
                                        })
                                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
            }
        })

    } 

    function active_account_requirement_checklist_send_to_controller(user_id, program_id, program_title, clistid, clistStatus, user_status, account_approval_status_code, account_status_name){

        Swal.fire({
            position: 'center',
            title: 'Do you want to save the checked requirements?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Save',
            denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let timerInterval
                Swal.fire({
                            title: 'Saving checked requirements...',
                            // html: 'I will close in <b></b> milliseconds.',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()

                                $.ajax({
                                        headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        method: 'PATCH',
                                        url: "{{ route('rfo_approval_module.create_user_checklist_details') }}",
                                        data: {
                                                user_id:user_id,
                                                program_id:program_id,
                                                program_title:program_title,
                                                clistid:clistid,
                                                clistStatus:clistStatus,
                                                user_status:user_status,
                                                account_approval_status_code:account_approval_status_code,
                                                account_status_name:account_status_name,
                                            },
                                        success: function(success_response){

                                        }
                                });
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                })
                .then((result) => {
                                    /* Read more about handling dismissals below */
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        Swal.fire({
                                                    title: 'Generating Certificate of Accreditation and SRN...',
                                                    timer: 3000,
                                                    timerProgressBar: true,
                                                    didOpen: () => {
                                                        Swal.showLoading()
                                                    },
                                                    willClose: () => {
                                                        clearInterval(timerInterval)
                                                    }
                                        }).then((result) => {
                                            /* Read more about handling dismissals below */
                                            if (result.dismiss === Swal.DismissReason.timer) {
                                                Swal.fire({
                                                            title: 'Sending account activation status to Head Supplier...',
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
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'success',
                                                            title: 'Account Activated Successfully!',
                                                            showConfirmButton: true,
                                                        }).then(function(){ 
                                                            $("#account_activation_datatable").DataTable().ajax.reload();
                                                            $("#list_of_approved_accounts_datatable").DataTable().ajax.reload();                                                          
                                                            $('#update_account_activation_checklist_modal').modal('hide');                                                          
                                                        }); 
                                                    }
                                                })
                                            }
                                        })
                                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
            }
        })

    }

    // This function is actived when the modal was close
    $('#update_user_status_modal').on('hidden.bs.modal', function()
    {
        // remove the duplicate fullname
        $("span.user_name_value").remove();

        // remove the duplicate status badge
        $("span.badge_modal_view").remove();

        // remove the duplicate alert save button
        $("div.alert_temp").remove();

        // remove the duplicate textarea
        $('div.container-contact100').remove();

        $("form#update_user_status_form")[0].reset();

        // Hide the save button on account activation checklist
        $('#btnSaveChecklist').prop('hidden', true);
            
        // Hide the active button on account activation checklist
        $('#btnActiveApprove').prop('hidden', true);

        $('div.item').remove();
    });
</script>