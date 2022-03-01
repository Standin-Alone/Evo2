<script type="text/javascript">
    $(function() {
        var table = $('#main_branch_and_supplier_branch_datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('rfo_approval_module.main_branch_approval')}}",
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columns: [
                {data: 'group_name', name: 'group_name'},
                {data: 'address', name: 'address'},
                {data: 'created_agency', name: 'created_agency'},
                {data: 'created_by_fullname', name: 'created_by_fullname'},
                {data: 'approved_by_fullname', name: 'approved_by_fullname'},
                {data: 'approval_status', name: 'approval_status'},
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

    $user = {!! json_encode(session()->get('user_fullname')) !!};

    // This function activated when the user click the Update Status Button
    $(document).on('click', '#update_approval_btn', function () {
        var group_name = $(this).data('group_name');
        var supplier_group_id = $(this).data('supplier_group_id');
        var approval_status = $(this).data('approval_status');
        
        $('input#main_branch_approve_and_disapprove').attr('data-supplier_group_id', ''+supplier_group_id+'');

        $('input#main_branch_onhold_and_unhold').attr('data-supplier_group_id', ''+supplier_group_id+'');

        var html = "";

        // append the fullname to Update status modal
        html += '<span class="user_group_name_value">'+ group_name + '<span>';

        $("span.group_name").append(html);

        var html2 = "";

        // Check if the user current status are [0]HOLD, [1]APPROVE, [2]DISAPPROVE
        if(approval_status == 0){
            html2 += '<h4><span class="badge badge_modal_view" style="background-color: rgba(91, 176, 255, 0.17); color: #39acda!important;"> BLOCK </span></h4>'
            
            //
            $("input#main_branch_approve_and_disapprove").attr('checked', false);
            
            //
            $("input#main_branch_onhold_and_unhold").attr('checked', true);
            
            $("h4.approval_status_badge").append(html2);
        }
        else if(approval_status == 1){
            html2 += '<span class="badge badge_modal_view mt-2" style="background-color: rgba(57,218,138,.17); color: #39DA8A!important;"> ACTIVE </span>';
            
            //
            $("input#main_branch_approve_and_disapprove").attr('checked', true);
            
            //
            $("input#main_branch_onhold_and_unhold").attr('checked', false);
            
            $("h4.approval_status_badge").append(html2);
        }
        else if(approval_status == 2){
            html2 += '<h4><span class="badge badge_modal_view" style="background-color: rgba(255,91,92,.17); color: #FF5B5C!important;"> INACTIVE </span></h4>'
            
            //
            $("input#main_branch_approve_and_disapprove").removeAttr('checked', false);
            
            $("h4.approval_status_badge").append(html2);
        }

        $(document).on('change', 'input#main_branch_approve_and_disapprove', function () {
            $("input#main_branch_onhold_and_unhold").removeAttr('checked', false);
            var supplier_group_id = $(this).data('supplier_group_id');
        
            var approved_by_fullname = $user;
            
            var html = "";

            // remove duplicate alert save button when modal is re-open
            $("div.alert_temp").remove();

            html += '<div class="alert_temp">';
            html += '<div class="note note-warning">';
            html += '<div class="note-icon"><i class="fas fa-lightbulb"></i></div>';
            html +=        '<div class="note-content">';
            html +=          '<span style="font-size:16px;"><b>*Note : </b></span>';
            html +=          '<span style="margin-bottom: 0px; font-size: 15px;">Any changes of user account activation status and want to apply.</span>';
            html +=          '<span style="font-size: 15px;"> Click the "SAVE CHANGES" BELOW. </span>';
            html +=        '</br>';
            html +=        '<button type="submit" class="btn btn-success mt-2"> SAVE ANY CHANGES </button>';
            html +=        '</div>';
            html += '</div>';
            html += '</div>';      

            $("div#update_main_branch_modal_body .show_alert_with_apply_button").append(html);

            // this function is activated when checking the status of toggle button whether its true or false
            var check = $('input#main_branch_approve_and_disapprove').is(':checked');

            console.log(check);

            if(check == true){
                var approval_status_code = "1";
                var approval_status_name = "APPROVE";

                $("input#main_branch_approve_and_disapprove").attr('checked', true);
                $("input#main_branch_onhold_and_unhold").removeAttr('checked', false);

                $(document).on('submit', "form#update_main_branch_approval_form", function(e){
                    e.preventDefault();

                    update_approval_status_send_to_controller(supplier_group_id, approved_by_fullname, approval_status_name, approval_status_code);

                });
            }
            else{
                var approval_status_code = "2";
                var approval_status_name = "DISAPPROVE";

                $("input#main_branch_approve_and_disapprove").attr('checked', false);
                $("input#main_branch_onhold_and_unhold").removeAttr('checked', false);

                $(document).on('submit', "form#update_main_branch_approval_form", function(e){
                    e.preventDefault();

                    update_approval_status_send_to_controller(supplier_group_id, approved_by_fullname, approval_status_name, approval_status_code);

                });
            }
        });

        $(document).on('change', 'input#main_branch_onhold_and_unhold', function () {
            var supplier_group_id = $(this).data('supplier_group_id');
            
            console.log(supplier_group_id);

            var approved_by_fullname = $user;
            
            var html = "";

            // remove duplicate alert save button when modal is re-open
            $("div.alert_temp").remove();

            html += '<div class="alert_temp">';
            html += '<div class="note note-warning">';
            html += '<div class="note-icon"><i class="fas fa-lightbulb"></i></div>';
            html +=        '<div class="note-content">';
            html +=          '<span style="font-size:16px;"><b>*Note : </b></span>';
            html +=          '<span style="margin-bottom: 0px; font-size: 15px;">Any changes of user account activation status and want to apply.</span>';
            html +=          '<span style="font-size: 15px;"> Click the "SAVE CHANGES" BELOW. </span>';
            html +=        '</br>';
            html +=        '<button type="submit" class="btn btn-success mt-2"> SAVE ANY CHANGES </button>';
            html +=        '</div>';
            html += '</div>';
            html += '</div>';      

            $("div#update_main_branch_modal_body .show_alert_with_apply_button").append(html);

            // this function is activated when checking the status of toggle button whether its true or false
            var check = $('input#main_branch_onhold_and_unhold').is(':checked');

            console.log(check);

            if(check == true){
                var approval_status_name = "HOLD";
                var approval_status_code = "0";

                $("input#main_branch_approve_and_disapprove").attr('checked', false);
                $("input#main_branch_onhold_and_unhold").removeAttr('checked', true);

                // $("input#account_active_and_inactive").attr('checked', true);

                $(document).on('submit', "form#update_main_branch_approval_form", function(e){
                    e.preventDefault();

                    update_approval_status_send_to_controller(supplier_group_id, approved_by_fullname, approval_status_name, approval_status_code);

                });
            }
            else{
                var approval_status_code = "1";
                var approval_status_name = "APPROVE";

                $("input#main_branch_approve_and_disapprove").attr('checked', true);
                $("input#main_branch_onhold_and_unhold").removeAttr('checked', false);

                $(document).on('submit', "form#update_main_branch_approval_form", function(e){
                    e.preventDefault();

                    update_approval_status_send_to_controller(supplier_group_id, approved_by_fullname, approval_status_name, approval_status_code);

                });
            }
        });
    })

    // This function is actived when the modal was close
    $('#update_main_branch_approval_status_modal').on('hidden.bs.modal', function()
    {
        // remove the duplicate fullname
        $("span.user_group_name_value").remove();

        // remove the duplicate status badge
        $("span.badge_modal_view").remove();

        // remove the duplicate alert save button
        $("div.alert_temp").remove();

        $("form#update_main_branch_approval_form")[0].reset();
    });


    function update_approval_status_send_to_controller(supplier_group_id, approved_by_fullname, approval_status_name, approval_status_code){

        Swal.fire({
                    position: 'center',
                    title: 'Do you want to save the changes?',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Save',
                    denyButtonText: `Don't save`,
        }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                        let timerInterval
                        Swal.fire({
                                    title: 'Updating Changes...',
                                    // html: 'I will close in <b></b> milliseconds.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()

                                        $.ajax({
                                                headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                method: 'PATCH',
                                                url: "{{ route('rfo_approval_module.update_supplier_main_branch_approval_status') }}",
                                                data: {
                                                                supplier_group_id:supplier_group_id,
                                                                approved_by_fullname:approved_by_fullname,
                                                                approval_status_name:approval_status_name,
                                                                approval_status_code:approval_status_code
                                                    },
                                                success: function(success_response){
                                                    // Swal.fire({
                                                    //     position: 'center',
                                                    //     icon: 'success',
                                                    //     title: success_response.message,
                                                    //     showConfirmButton: true,
                                                    // }).then(function(){ 
                                                    //     window.location.href = "{{ route('rfo_approval_module.list_of_merchs_and_supps')}} ";
                                                    // });
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
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'The supplier main branch have update successfully!',
                                                    showConfirmButton: true,
                                                }).then(function(){ 
                                                    window.location.href = "{{ route('rfo_approval_module.list_of_merchs_and_supps')}} ";
                                                 });
                                            }
                        })
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info');
                }
        })
    }  
</script>
