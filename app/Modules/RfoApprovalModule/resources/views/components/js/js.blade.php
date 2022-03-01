{{-- datatable responsive --}}
<script src="//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

{{-- datatable buttons --}}
{{-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script> --}}
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>

{{-- datatable row group --}}
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- Account Activation Checklist Modal --}}
{{-- <script>
    $(document).on('click', '#updateBtn', function (e) {
        e.preventDefault();

        var user_id = $(this).data('uuid');
        var program_id = $(this).data('program_id');
        var program_title = $(this).data('program_title');
        // var company_name = $(this).data('company_name');
        // var email = $(this).data('email');
        // var username = $(this).data('username');
        // var fullname = $(this).data('fullname');
        // var fname = $(this).data('fname');
        // var mname = $(this).data('mname');
        // var lname = $(this).data('lname');
        // var ename = $(this).data('ename');
        // var status = $(this).data('status');
        // var contact_no = $(this).data('contact_no');
        // var region = $(this).data('region');
        // var role = $(this).data('role');

        $checklists = {!! json_encode($checklists) !!}
        $checklist_details = {!! json_encode($checklist_details) !!}

        $("div.srn_input_class").hide();

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
        $("div.inbox").append(html);

        var arr = [];
        var clistid = [];
        var clistStatus = [];

        $(document).on('change', '.clist_id', function(e){
            var val01 = $('input[type="checkbox"]:checked').each(function(){
                return $(this).val();
            }).get();

            if(val01.length == 5){
                console.log('true all are checked');

                // $("div.srn_input_class").append(html3);

                $("div.srn_input_class").show();

                $('#btnSaveChecklist').prop('hidden', true);

                $('#btnActiveApprove').prop('hidden', false);

            }
            else if(val01.length <= 4){

                $("div.srn_input_class").hide();

                $('#btnSaveChecklist').prop('hidden', false);
                    
                $('#btnActiveApprove').prop('hidden', true);
            }

        });

        // Button is active when not all checkbox are checked!
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

            // console.log();

            var account_status_code = "2";
            var account_status_name = "INACTIVE";

            inactive_account_requirement_checklist_send_to_controller(user_id,  program_id, program_title, clistid, clistStatus, account_status_code, account_status_name);

        });

        $('#update_user_status_form').ready(function(){
        $('#update_user_status_form').validate({
                errorClass: "invalid",
                validClass: "valid",
                rules: {
                    srn: {
                        required: true,
                    },
                },
                messages: {
                    srn: '<div class="text-danger">*Please enter srn!</div>',
                }, 
                // Customize placement of created message error labels. 
                errorPlacement: function(error, element) {
                    error.appendTo( element.parent().find(".error_msg") );
                    $('span.error_form').remove();
                }
            });
        });

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

            // console.log(clistid);

            var srn =$('#srn').val();
            var account_status_code = "1";
            var account_status_name = "ACTIVE";

            active_account_requirement_checklist_send_to_controller(user_id,  program_id, program_title, clistid, clistStatus, srn, account_status_code, account_status_name);

        });

        // Button is active when all checkbox are checked!
        // $('#btnActiveApprove').on('click', function(e){
        //     e.preventDefault();

        //     $('.clist_id').each(function(){
        //         if($(this).is(":checked")){
        //             var x = $(this).val();
        //             var y = '1';
        //             clistid.push(x);
        //             clistStatus.push(y);
        //         }else{
        //             var x = $(this).val();
        //             var y = '0';
        //             clistid.push(x);
        //             clistStatus.push(y);
        //         }
        //     });

        //     // console.log(clistid);

        //     var account_status_code = "1";
        //     var account_status_name = "ACTIVE";

        //     active_account_requirement_checklist_send_to_controller(user_id, clistid, clistStatus, account_status_code, account_status_name);

        // });

    });

    function inactive_account_requirement_checklist_send_to_controller(user_id,  program_id, program_title, clistid, clistStatus, account_status_code, account_status_name){

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
                                                account_status_code:account_status_code,
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
                                                    window.location.href = "{{ route('rfo_approval_module.list_of_merchs_and_supps')}} ";
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

    function active_account_requirement_checklist_send_to_controller(user_id, program_id, program_title, clistid, clistStatus, srn, account_status_code, account_status_name){

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
                                                srn:srn,
                                                account_status_code:account_status_code,
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
</script> --}}