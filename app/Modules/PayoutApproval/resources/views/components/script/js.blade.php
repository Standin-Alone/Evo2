<script type="text/javascript">
       $(document).ready(function (){
            PayoutApprovalList();

           function PayoutApprovalList(){
                $('#PayoutApprovalList-datatable').unbind('click');
                var table = $('#PayoutApprovalList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    paging: false,
                    ordering:false,
                    ajax: "{{ route('get.PayoutApprovalList') }}",
                    columns: [
                        {data: 'program', name: 'program'}, 
                        {data: 'checkbox', name: 'checkbox'},  
                        {data: 'transac_date', name: 'transac_date'},
                        {data: 'application_number', name: 'application_number'},
                        {data: 'description', name: 'description'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},    
                        {data: 'action', name: 'action', orderable: false, searchable: false}, 
                    ],
                    columnDefs: [
                        { "visible": false, "targets": 0,}
                    ]
                }).ajax.reload();
           }

           $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                $('.PayoutApproval_totalselectedamt').html("0.00"); 
                $('#PayoutAppval_selectedamt').val("0.00");
            });

            // Handle click on checkbox
            $('#PayoutApprovalList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });
            
            $('#PayoutApprovalList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var total_amount = $(this).data('selectedbatchamt');
                var $row = $(this).closest('tr');
                if(this.checked){
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#PayoutAppval_selectedamt').val()); 
                    var total = selectedamt + total_amount;
                    $('#PayoutAppval_selectedamt').val(total);
                    var totalamt = '₱ '+addCommas(total);
                    $('.PayoutApproval_totalselectedamt').html(totalamt);
                    $row.addClass("selected");
                }else{                 
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#PayoutAppval_selectedamt').val()); 
                    var total = selectedamt - total_amount;
                    $('#PayoutAppval_selectedamt').val(total);
                    var totalamt = '₱ '+addCommas(total);
                    $('.PayoutApproval_totalselectedamt').html(totalamt);    
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });

            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#PayoutApprovalList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#PayoutApprovalList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            function PayoutApprovalDetails(batch_id){ 
                $('#viewPayoutApprovalDetails-datatable').unbind('click');
                var table = $('#viewPayoutApprovalDetails-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.PayoutApprovalDetails') }}" + '?batch_id=' + batch_id,
                    columns: [                     
                        {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                        {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO'},
                        {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                        {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'option', name: 'option', orderable: false, searchable: false, title: 'OPTION'}, 
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                    ],
                    rowGroup: {
                        dataSrc: function (data) {
                                return "<span>{{ session('Default_Program_Desc') }}</span>";
                            },
                        starRender:null,
                        endRender: function(rows){
                                var total_amount_claim = rows
                                .data()
                                .pluck('total_amount')
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;
                                }, 0 );
                                return '<span>Page Total: '+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim )+'</span>';
                            },                    
                        }            
                }).ajax.reload();
                $('#ViewDetailsModal').modal('toggle');
                
            }
            
            $(document).on('click','.btnVoucherHold',function(){
                var batch_id = $(this).data('selectbatchid'),
                    voucher_id = $(this).data('selectvoucherid');
                SpinnerShow('btnVoucherHold',voucher_id);
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Hold this Voucher transaction?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hold',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $('#PayoutAppval_selectedbatchid').val(batch_id);
                        $('#PayoutAppval_selectedvoucherid').val(voucher_id);
                        $('.PayoutAppval_holdremarks').val('');
                        $('#AddRemarksModal').modal('toggle'); 
                        SpinnerHide('btnVoucherHold',voucher_id);             
                    }else{
                        SpinnerHide('btnVoucherHold',voucher_id);
                    }
                });
            });

            $(document).on('click','.PayoutApproval_submitholdremarks',function(){
                SpinnerShow('PayoutApproval_submitholdremarks','btnloadingIcon1');
                var remarks = $('.PayoutAppval_holdremarks').val(),
                _token = $("input[name=token]").val(),
                voucher_id = $('#PayoutAppval_selectedvoucherid').val(),
                batch_id = $('#PayoutAppval_selectedbatchid').val();
                if (remarks != "") {        
                        $.ajax({
                            type:'post',
                            url:"{{ route('hold.SelectedVoucher') }}",
                            data:{batch_id:batch_id,voucher_id:voucher_id,remarks:remarks,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Hold!',
                                    text:'Your Selected Voucher successfully Hold!',
                                    icon:'success'
                                });
                                $('#AddRemarksModal').modal('hide'); 
                                $('.errormsg_holdremarks').css('display','none');                       
                                PayoutApprovalDetails(batch_id);
                                SpinnerHide('PayoutApproval_submitholdremarks','btnloadingIcon1');
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('PayoutApproval_submitholdremarks','btnloadingIcon1');
                            }
                        });
                }else{                
                    $('.errormsg_holdremarks').css('display','block');
                    $(".errormsg_holdremarks").html("Please enter value in required field!");
                    SpinnerHide('PayoutApproval_submitholdremarks','btnloadingIcon1');
                }
            });

            $(document).on('click','.btnViewPayoutApprovalDetails',function(){                              
                var  batch_id = $(this).data('selectedbatchid');
                SpinnerShow('btnViewPayoutApprovalDetails',batch_id);  
                $('#PayoutAppval_selectedbatchid').val(batch_id);                
                PayoutApprovalDetails(batch_id);
                SpinnerHide('btnViewPayoutApprovalDetails',batch_id);
                
            });
            
            $(document).on('click','.btnApprovalBatchPayout',function(){
                SpinnerShow('btnApprovalBatchPayout','btnloadingIcon2');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Approve the selected Batch Payout?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Approve',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        batchid = $('.selectedbatch:checked').map(function(){
                        return $(this).data('selectedbatchid');}).get().join(',');
                        if(batchid != ""){
                            $('#PayoutAppval_selectedbatches').val(batchid);
                            $('.errormsg_approval').css('display','none');
                            $('.PayoutAppval_appdescription').val('');
                            $('#AddDescriptionModal').modal('toggle');
                            SpinnerHide('btnApprovalBatchPayout','btnloadingIcon2');
                        }else{
                            $('.errormsg_approval').css('display','block');
                            $(".errormsg_approval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select Batch for approval ! Please try again.");
                            SpinnerHide('btnApprovalBatchPayout','btnloadingIcon2');
                        }
                        
                    }else{
                        SpinnerHide('btnApprovalBatchPayout','btnloadingIcon2');
                    }
                });
            });

            $(document).on('click','.PayoutApproval_submitappdescription',function(){
                var _token = $("input[name=token]").val(),
                batchid = $('#PayoutAppval_selectedbatches').val(),
                description = $('.PayoutAppval_appdescription').val();
                SpinnerShow('PayoutApproval_submitappdescription','btnloadingIcon3');
                if(description != ""){
                    $.ajax({
                        type:'post',
                        url:"{{ route('approve1.SelectedBatch') }}",
                        data:{batchid:batchid,description:description,_token:_token},
                        success:function(data){ 
                            if(data == "Hold"){
                                $('.errormsg_approval').css('display','block');
                                $(".errormsg_approval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please settle the <b>Hold</b> voucher transaction to countinue the approval!");
                                $('#AddDescriptionModal').modal('hide');
                                $('.PayoutAppval_appdescription').val('');
                                SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                            }else{
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Approved!',
                                    text:'Your Batch Payout successfully Approved!',
                                    icon:'success'
                                });
                                $('.PayoutAppval_appdescription').val('');                                    
                                $('.errormsg_approval').css('display','none');
                                $('#AddDescriptionModal').modal('hide');
                                PayoutApprovalList();
                                SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                            }  
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                        }
                    });
                    $('.errormsg_appdescription').css('display','none');
                    SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                }else{
                    $('.errormsg_appdescription').css('display','block');
                    $(".errormsg_appdescription").html("Please enter value in required field!");
                    SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                }

            });

            $(document).on('click','.btnViewVoucherAttachments',function(){                
                var _token = $("input[name=token]").val(),
                    voucher_id = $(this).data('selectvoucherid');
                    SpinnerShow('btnViewVoucherAttachments',voucher_id);
                $.ajax({
                    type:'get',
                    url:"{{ route('get.VoucherAttachmentsImg') }}",
                    data:{voucher_id:voucher_id,_token:_token},
                    success:function(data){
                        $('.voucherattachmentsimg').html(data);
                        $('#ViewAttachmentsModal').modal('toggle');
                        SpinnerHide('btnViewVoucherAttachments',voucher_id);
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('btnViewVoucherAttachments',voucher_id);
                        }
                });  
                          
            });

           $(document).on('click','.linkApprovedPayoutHistory',function(){
                SpinnerShow('linkApprovedPayoutHistory','btnloadingIcon5');
                window.location.href = "{{ route('ApprovedPayoutHistory.index') }}";
                SpinnerHide('linkApprovedPayoutHistory','btnloadingIcon5');
            });

            $(document).on('click','.linkHoldVoucherHistory',function(){
                SpinnerShow('linkHoldVoucherHistory','btnloadingIcon6');
                window.location.href = "{{ route('HoldVoucherHistory.index') }}";
                SpinnerHide('linkHoldVoucherHistory','btnloadingIcon6');
            });

            $(document).on('keyup','.PayoutAppval_appdescription, .PayoutAppval_holdremarks',function(){
                var str = $(this).val();
                var res = str.toUpperCase();
                $(this).val(res);
            });           

        });
    </script>