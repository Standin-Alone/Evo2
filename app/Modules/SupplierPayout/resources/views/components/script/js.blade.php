<script type="text/javascript">
    
    $(document).ready(function (){                   
        
        supplierpayoutlist();
        getVoucherTotals_amt()
        // $('.search-batch-app').search();

        // DATATABE CONTENT
        function supplierpayoutlist(){
            $('#supplierpayout-datatable').unbind('click');
            var table = $('#supplierpayout-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                ajax: "{{ route('get.SupplierPayoutList') }}",
                columns: [
                    {data: 'application_number', name: 'application_number',
                        render: function(data, type, row) {
                            submitted_tag = "";
                            if(row.issubmitted == 1){
                                submitted_tag = '<img class="result-image" src="assets/img/product/submitted_1.png" height="auto"/>';
                            }else{                                
                                submitted_tag = '<img class="result-image" src="assets/img/product/batch_3.png" height="auto"/>';
                            }
                            return  submitted_tag;
                        }
                    },
                    {data: 'application_number', name: 'application_number',
                            render: function(data, type, row) {
                                submitted_tag = "";
                                remove_button = "";
                                submit_button = "";
                                addvoucher_button = "";
                                if(row.issubmitted == 1){
                                    remove_button = "";
                                    submit_button = "";
                                    addvoucher_button = "";
                                }else{
                                    remove_button = "btnRemoveSupplierPayout";
                                    submit_button = "btnSubmitSupplierPayout";
                                    addvoucher_button = "SupplierPayout_btn_AddVoucher";
                                }
                                return  '<div class="result-info">'+
                                            '<br><h4 class="title"><a href="javascript:void(0);" data-statuspayoutappnum="' + row.application_number + '" class="statuspayoutlink text-black"><i class="fas fa-spinner fa-spin '+ row.application_number +' pull-left m-r-10" style="display: none;"></i>'+ row.application_number +'</a></h4>'+
                                            '<p class="location">Program: <strong>'+ row.program +'</strong></p>'+                                            
                                            '<p class="location">Total Vouchers: <strong> '+ $.fn.dataTable.render.number(',', '.', 0, '').display( row.cnt_vouchers ) +'</strong></p>'+
                                            '<p class="location">Transaction Date: <strong> '+ row.transac_date +'</strong></p>'+
                                            '<div class="btn-row">'+                                         
                                                '<a href="javascript:void(0)" data-removesupplierbatchid="'+row.batch_id+'" data-toggle="tooltip" data-placement="top" title="Remove Payout Batch" class="'+remove_button+' text-black m-t-5"><span class="fa fa-trash-alt"></span><i class="fas fa-spinner fa-spin '+row.batch_id+' pull-left m-r-10" style="display: none;"></i> Remove Batch</a> | '+                    
                                                '<a href="javascript:void(0)" data-submitsupplierbatchid="'+row.batch_id+'" data-toggle="tooltip" data-placement="top" title="Submit Payout Batch" class="'+submit_button+' text-black m-t-5"><span class="fa fa-check-circle"></span><i class="fas fa-spinner fa-spin '+row.batch_id+' pull-left m-r-10" style="display: none;"></i> Submit Batch</a> | '+
                                                '<a href="javascript:void(0)" data-holtransactionbatchid="'+row.batch_id+'" data-toggle="tooltip" data-placement="top" title="View Return Vouchers" class="btnViewHoldtransDetails text-black"><span class="fa fa-exclamation-triangle"></span><i class="fas fa-spinner fa-spin '+row.batch_id+' pull-left m-r-10" style="display: none;"></i> View Return Voucher <span class="badge badge-danger m-b-10">0</span></a>'+
                                            '</div>'+
                                        '</div>';
                            }
                        },
                    {data: 'application_number', name: 'application_number',
                        render: function(data, type, row) {
                            return  '<div class="result-price">'+
                                        '<h3 class="d-flex flex-row justify-content-center mt-auto">'+ $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( row.amount ) +'</h3><small class="d-flex flex-row justify-content-center mt-auto">TOTAL AMOUNT</small>'+
                                        '<br><br><a href="javascript:void(0)" data-submitsupplierbatchid="'+row.batch_id+'" class="btn btn-success btn-block btnViewSupplierPayoutDetails"><i class="fas fa-spinner fa-spin '+row.batch_id+' pull-left m-r-10" style="display: none;"></i> View Details</a>'+
                                        '<a href="javascript:void(0)" data-submitsupplierbatchid="'+row.batch_id+'" class="btn btn-yellow btn-block '+addvoucher_button+'"><i class="fas fa-spinner fa-spin '+row.batch_id+' pull-left m-r-10" style="display: none;"></i> Add Voucher</a>'+
                                    '</div>';
                        }
                    },
                ],
                'rowsGroup': [1],
                
                columnDefs: [
                            { width: '20%', targets: 0 },
                            { width: '15%', targets: 2 }
                        ],
                order: [[1, 'desc']],
                "language": {
                            "emptyTable": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "zeroRecords": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "infoEmpty": 'No records available'
                        }
            }).ajax.reload(); 

        }

        $(document).on('click','.SupplierPayout_btn_Create',function(){
            SpinnerShow('SupplierPayout_btn_Create','btnloadingIcon');
            $.ajax({
                type:'get',
                url:"{{ route('get.BatchPayoutDropList') }}",
                success:function(data){                    
                    $('.option_batch_id').remove();
                    for(var i=0;i<data.length;i++){                   
                            $('.selectbatchpayout').append($('<option>', {class:'option_batch_id',value:data[i].batch_id, text:data[i].application_number}));
                        }                    
                    $('.selectedvoucher, .selectedvoucherall').prop("checked", false);
                    $('.SupplierPayout_totalselectedamt').html('0.00');
                    $('#selected_voucheramt').val("0.00");
                    $('.errormsg').css('display','none');
                    $('#selected_batchid').val('');
                    $('#CreatePayoutModal').modal('toggle');                    
                    datatablecheckbox("");    
                    SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');                
            },
            error: function (textStatus, errorThrown) {
                    console.log('Err');
                    SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');
                }
            });  
            SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');          
        });

        $(document).on('click','.SupplierPayout_btn_AddVoucher',function(){
            SpinnerShow('SupplierPayout_btn_Create','btnloadingIcon');
            $.ajax({
                type:'get',
                url:"{{ route('get.BatchPayoutDropList') }}",
                success:function(data){                    
                    $('.option_batch_id').remove();
                    for(var i=0;i<data.length;i++){                   
                            $('.selectbatchpayout').append($('<option>', {class:'option_batch_id',value:data[i].batch_id, text:data[i].application_number}));
                        }                    
                    $('.selectedvoucher, .selectedvoucherall').prop("checked", false);
                    $('.SupplierPayout_totalselectedamt').html('0.00');
                    $('#selected_voucheramt').val("0.00");
                    $('.errormsg').css('display','none');
                    $('#selected_batchid').val('');
                    $('#CreatePayout_AddVoucherModal').modal('toggle');                    
                    ClaimedVoucherDetails_AddVoucher();    
                    SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');                
            },
            error: function (textStatus, errorThrown) {
                    console.log('Err');
                    SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');
                }
            });  
            SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');          
        });

        function datatablecheckbox(){ 
            $('#ClaimedVoucher-datatable').unbind('click');       
            var TotalCreatedAmount = 0;      
            var table = $('#ClaimedVoucher-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                scrollY: "300px",
                // responsive: true,
                paging: false,
                // ordering: false,
                ajax: "{{ route('get.SupplierPayout_ClaimedVoucherDetails') }}",
                columns: [                     
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                    {data: 'farmer_fullname', name: 'farmer_fullname', title: 'FARMER FULLNAME'},
                    {data: 'item_name', name: 'item_name', title: 'COMMODITY'},
                    {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                    {data: 'amount', name: 'amount', title: 'AMOUNT', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'total_amount', name: 'total_amount', title: 'TOTAL AMOUNT', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'voucher_details_id', name: 'voucher_details_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return  '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-success btnViewSupplierPayoutVoucherAttachments" data-toggle="tooltip" data-placement="top" title="View Images"><span class="fa fa-paperclip"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-danger Remove_SupplierPayout_Button" data-toggle="tooltip" data-placement="top" title="Remove Voucher"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>';
                            }
                        }
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalCreatedAmount += parseInt(dataval);
                    }
                    $('.SupplierPayout_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalCreatedAmount));
                }                
            }).ajax.reload(); 

        }

        function ClaimedVoucherDetails_AddVoucher(){ 
            $('#ClaimedVoucher_AddVoucher-datatable').unbind('click');       
            var TotalCreatedAmount = 0;      
            var table = $('#ClaimedVoucher_AddVoucher-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                scrollY: "300px",
                // responsive: true,
                paging: false,
                // ordering: false,
                ajax: "{{ route('get.SupplierPayout_ClaimedVoucherDetails') }}",
                columns: [                     
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                    {data: 'farmer_fullname', name: 'farmer_fullname', title: 'FARMER FULLNAME'},
                    {data: 'item_name', name: 'item_name', title: 'COMMODITY'},
                    {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                    {data: 'amount', name: 'amount', title: 'AMOUNT', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'total_amount', name: 'total_amount', title: 'TOTAL AMOUNT', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'voucher_details_id', name: 'voucher_details_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return  '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-success btnViewSupplierPayoutVoucherAttachments" data-toggle="tooltip" data-placement="top" title="View Images"><span class="fa fa-paperclip"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-danger Remove_SupplierPayout_Button" data-toggle="tooltip" data-placement="top" title="Remove Voucher"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>';
                            }
                        }
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalCreatedAmount += parseInt(dataval);
                    }
                    $('.SupplierPayout_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalCreatedAmount));
                }                
            }).ajax.reload(); 

        }

        function getVoucherTotals_amt(){
            $.ajax({
                type:'get',
                url:"{{ route('get.SupplierPayout_TotalClaimedVoucher') }}",
                success:function(data){   
                    for(var i=0;i<data.length;i++){             
                            $('.amt_total_claimed_voucher').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(+data[i].amount));
                        }                        
                    },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                });  

            $.ajax({
                type:'get',
                url:"{{ route('get.SupplierPayout_TotalPendingPayouts') }}",
                success:function(data){   
                    for(var i=0;i<data.length;i++){             
                            $('.amt_total_pending_payouts').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(+data[i].amount));
                        }                        
                    },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                }); 

            $.ajax({
                type:'get',
                url:"{{ route('get.SupplierPayout_TotalApprovedPayouts') }}",
                success:function(data){   
                    for(var i=0;i<data.length;i++){             
                            $('.amt_total_approved_payouts').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(+data[i].amount));
                        }                        
                    },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                }); 

            $.ajax({
                type:'get',
                url:"{{ route('get.SupplierPayout_TotalHoldVoucher') }}",
                success:function(data){   
                    for(var i=0;i<data.length;i++){             
                            $('.amt_total_hold_vouchers').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(+data[i].amount));
                        }                        
                    },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                }); 
        }



        function ViewTotalDetails(TotalDetails_id){ 
            $('#ViewTotalDetails-datatable').unbind('click');       
            var TotalCreatedAmount = 0;      
            var _token = $("input[name=token]").val();
            var table = $('#ViewTotalDetails-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                scrollY: "300px",
                // responsive: true,
                paging: false,
                // ordering: false,
                ajax: {
                    type: "get",
                    url: "{{ route('get.SupplierPayout_ViewTotalDetails') }}",
                    data:{TotalDetails_id:TotalDetails_id,_token:_token}
                },
                columns: [                     
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                    {data: 'farmer_fullname', name: 'farmer_fullname', title: 'FARMER FULLNAME'},
                    {data: 'item_name', name: 'item_name', title: 'COMMODITY'},
                    {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                    {data: 'amount', name: 'amount', title: 'AMOUNT', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'total_amount', name: 'total_amount', title: 'TOTAL AMOUNT', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'voucher_details_id', name: 'voucher_details_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return  '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-success btnViewSupplierPayoutVoucherAttachments" data-toggle="tooltip" data-placement="top" title="View Images"><span class="fa fa-paperclip"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-danger Remove_SupplierPayout_Button" data-toggle="tooltip" data-placement="top" title="Remove Voucher"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>';
                            }
                        }
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalCreatedAmount += parseInt(dataval);
                    }
                    $('.ViewTotalDetails_totalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalCreatedAmount));
                }                
            }).ajax.reload(); 

        }

        $(document).on('click','.btn_ViewTotalClaimedVoucherDetails',function(){
            var TotalDetails_id = "TotalClaimedVoucher";
            ViewTotalDetails(TotalDetails_id);
            $('#ViewTotalDetailsModal').modal('toggle');
        });

        $(document).on('click','.btn_ViewTotalPendingPayoutsDetails',function(){
            var TotalDetails_id = "TotalPendingPayouts";
            ViewTotalDetails(TotalDetails_id);
            $('#ViewTotalDetailsModal').modal('toggle');
        });

        $(document).on('click','.btn_ViewTotalApprovedPayoutsDetails',function(){
            var TotalDetails_id = "TotalApprovedPayouts";
            ViewTotalDetails(TotalDetails_id);
            $('#ViewTotalDetailsModal').modal('toggle');
        });

        $(document).on('click','.btn_ViewTotalHoldVoucherDetails',function(){
            var TotalDetails_id = "TotalHoldVoucher";
            ViewTotalDetails(TotalDetails_id);
            $('#ViewTotalDetailsModal').modal('toggle');
        });

        // Handle click on "Select all" control
        $('.selectedvoucherall').on('click', function(e){
            if(this.checked){
                $('#ClaimedVoucher-Datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#ClaimedVoucher-Datatable tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        }); 

        $(document).on('click','.btnCreate',function(){
            SpinnerShow('btnCreate','btnloadingIcon1');
            Swal.fire({
            title: 'Are you sure',
            text: "You want to Create Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save it!',
            allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                        var _token = $("input[name=token]").val();
                            $.ajax({
                                type:'post',
                                url:"{{ route('save.SupplierPayout') }}",
                                data:{_token:_token},
                                success:function(data){                         
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        title:'Created!',
                                        text:'Your Supplier Payout successfully Created!',
                                        icon:'success'
                                    });                                    
                                    supplierpayoutlist();
                                    datatablecheckbox();
                                    $('#CreatePayoutModal').modal('hide');
                                    // //document.getElementById("default_BatchPayout").value = "";
                                    $('.selectedvoucher, .selectedvoucherall').prop("checked", false);
                                    $('.errormsg').css('display','none');
                                    $('.totalselected ').html('0.00');
                                    SpinnerHide('btnCreate','btnloadingIcon1');
                                    $('.SupplierPayout_totalselectedamt').html("0.00"); 
                                    $('#selected_voucheramt').val("0.00");
                                    
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('btnCreate','btnloadingIcon1');
                                }
                            }); 
                            SpinnerHide('btnCreate','btnloadingIcon1'); 
                    }
                    else{
                        SpinnerHide('btnCreate','btnloadingIcon1');
                    }
                }); 
        });

        $(document).on('click','.btnRemoveSupplierPayout',function(){
            var _token = $("input[name=token]").val(),
                batch_id = $(this).data('removesupplierbatchid');
            SpinnerShow('btnRemoveSupplierPayout',batch_id);
            Swal.fire({
            title: 'Are you sure',
            text: "You want to Remove the Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Remove it!',
            allowOutsideClick: false
            }).then((result) => {
            if (result.isConfirmed) {                
                    $.ajax({
                            type:'post',
                            url:"{{ route('remove.SupplierPayout') }}",
                            data:{batch_id:batch_id,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Your Supplier Payout successfully Removed!',
                                    icon:'success'
                                });
                                supplierpayoutlist();                                   
                                //document.getElementById("default_BatchPayout").value = "";
                                SpinnerHide('btnRemoveSupplierPayout',batch_id);
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                supplierpayoutlist();
                                SpinnerHide('btnRemoveSupplierPayout',batch_id);
                            }
                        });
                    }else{
                        SpinnerHide('btnRemoveSupplierPayout',batch_id);
                    }
                });
        });
            

        $(document).on('click','.statuspayoutlink',function(){            
        var app_num = $(this).data('statuspayoutappnum'),
            _token = $("input[name=token]").val();
            SpinnerShow('statuspayoutlink',app_num);
                $.ajax({
                    url:"{{ route('get.SupplierPayout_Status') }}",
                    data:{app_num:app_num,_token:_token},
                    success:function(data){
                        var status_val = jQuery.parseJSON(data);
                        $('.status_application_number').html(status_val[0].application_number); 
                        $('.status_transac_date').html(status_val[0].transac_date);   
                        $('.status_description').html(status_val[0].description);   
                        $('.status_amount').html(' ₱ '+addCommas(status_val[0].amount));
                        $('.createdpayout, .submittedpayout, .approvalprocess, .payoutcomplete').removeClass("active");
                        if(status_val[0].issubmitted == 1){                           
                            $('.createdpayout, .submittedpayout').addClass("active");
                        }else{
                            $('.createdpayout').addClass("active");
                        }
                        
                        if(status_val[0].payout_endorse_approve == 1){                           
                            $('.createdpayout, .submittedpayout, .approvalprocess').addClass("active");
                        }else{
                            $('.createdpayout').addClass("active");
                        }
                        if(status_val[0].dbp_batch_id != null){                           
                            $('.createdpayout, .submittedpayout, .approvalprocess, .payoutcomplete').addClass("active");
                        }else{
                            $('.createdpayout').addClass("active");
                        }
                        
                            $('#CheckStatusModal').modal('toggle');
                            SpinnerHide('statuspayoutlink',app_num);
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('statuspayoutlink',app_num);
                        }
                });
        });                

        $(document).on('click','.btnViewSupplierPayoutDetails',function(){
            var  batch_id = $(this).data('submitsupplierbatchid'); 
            SpinnerShow('btnViewSupplierPayoutDetails',batch_id);
            $('#viewbatchpayoutdetails-Datatable').unbind('click');
            var TotalCreatedAmount = 0;
            var table = $('#viewbatchpayoutdetails-Datatable').DataTable({
                destroy: true,
                processing: true,
                // serverSide: true,
                // responsive: true,
                ajax: "{{ route('get.SupplierBatchPayoutDetails') }}" + '?batch_id=' + batch_id,
                columns: [                       
                    {data: 'transac_date', name: 'transac_date', title:'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title:'REFERENCE NO.'},
                    {data: 'item_name', name: 'item_name', title:'ITEM NAME'},
                    {data: 'quantity', name: 'quantity', title:'QUANTITY'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['grandtotalamount'];
                        TotalCreatedAmount = parseInt(dataval);
                    }
                    $('.batchpayouttotalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalCreatedAmount));
                }                          
                
            }).ajax.reload();
            $('#ViewDetailsModal').modal('toggle');
            SpinnerHide('btnViewSupplierPayoutDetails',batch_id);
        });

        $(document).on('click','.btnSubmitSupplierPayout',function(){
            var _token = $("input[name=token]").val(),
                batch_id = $(this).data('submitsupplierbatchid');
            SpinnerShow('btnSubmitSupplierPayout',batch_id);
            Swal.fire({
            title: 'Are you sure',
            text: "You want to Submit the Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Submit it!',
            allowOutsideClick: false
            }).then((result) => {
            if (result.isConfirmed) {                
                    $.ajax({
                        type:'post',
                        url:"{{ route('submit.SupplierPayout') }}",
                        data:{batch_id:batch_id,_token:_token},
                        success:function(data){                         
                            Swal.fire({
                                allowOutsideClick: false,
                                title:'Submitted!',
                                text:'Your Supplier Payout successfully Submitted!',
                                icon:'success'
                            });
                            $('#supplierpayout-datatable').DataTable().ajax.reload();
                            //document.getElementById("default_BatchPayout").value = "";
                            SpinnerHide('btnSubmitSupplierPayout',batch_id);
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('btnSubmitSupplierPayout',batch_id);
                        }
                    });
                }else{
                    SpinnerHide('btnSubmitSupplierPayout',batch_id);
                }
            });
        });

        $(document).on('click','.btnViewSupplierPayoutVoucherAttachments',function(){
            SpinnerShow('btnViewSupplierPayoutVoucherAttachments',voucher_id);
            var _token = $("input[name=token]").val(),
                voucher_id = $(this).data('selectvoucherid');
            $.ajax({
                type:'get',
                url:"{{ route('get.SupplierPayoutAttachmentsImg') }}",
                data:{voucher_id:voucher_id,_token:_token},
                success:function(data){
                    $('.holdtransattachmentsimgcontent').html(data);
                    $('#ViewHoldTransAttachmentsModal').modal('toggle');
                    SpinnerHide('btnViewSupplierPayoutVoucherAttachments',voucher_id);
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                        SpinnerHide('btnViewSupplierPayoutVoucherAttachments',voucher_id);
                    }
            });           
        });
        
        $(document).on('click','.Remove_SupplierPayout_Button',function(){
            var _token = $("input[name=token]").val(),
                voucher_id = $(this).data('selectvoucherid');
                SpinnerShow('Remove_SupplierPayout_Button',voucher_id);
                Swal.fire({
                title: 'Are you sure',
                text: "You want to Remove this Voucher?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Remove it!',
                allowOutsideClick: false
                }).then((result) => {
                if (result.isConfirmed) {                
                        $.ajax({
                            type:'post',
                            url:"{{ route('remove.ClaimedVoucher') }}",
                            data:{voucher_id:voucher_id,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Voucher successfully removed!',
                                    icon:'success'
                                });
                                datatablecheckbox();
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('Remove_SupplierPayout_Button',voucher_id);
                            }
                        });
                    }else{
                        SpinnerHide('Remove_SupplierPayout_Button',voucher_id);
                    }
                });
        });

        $(document).on('change','.selectbatchpayout',function(){
            var  batch_id = $(this).val();
            $('.SupplierPayout_totalselectedamt').html("0.00"); 
            $('#selected_voucheramt').val("0.00");
            datatablecheckbox(batch_id);
        });

        $(document).on('click','.btnViewHoldtransDetails',function(){            
            var TotalBatchPayoutAmount = 0,
                batch_id = $(this).data('holtransactionbatchid');
                $('#viewHoldTransactionDetails-Datatable').unbind('click'); 
                SpinnerShow('btnViewHoldtransDetails',batch_id);
            var table = $('#viewHoldTransactionDetails-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                scrollY: "300px",
                responsive: true,
                // paging: false,
                // ordering: false,
                ajax: "{{ route('get.SupplierPayout_HoldTransDetails') }}" + '?batch_id=' + batch_id,
                columns: [                       
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                    {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                    {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                    {data: 'amount', name: 'amount', title: 'AMOUNT'},
                    {data: 'remarks', name: 'remarks', title: 'REMARKS/REASON'},
                    // {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}, 
                    {data: 'voucher_details_id', name: 'voucher_details_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return  '<a href="javascript:void(0)" data-selectvoucherid="'+row.voucher_details_id+'" class="btn btn-success btnViewSupplierPayoutVoucherAttachments" data-toggle="tooltip" data-placement="top" title="View Images"><i class="fas fa-spinner fa-spin '+row.voucher_details_id+' pull-left m-r-10" style="display: none;"></i>View Attachment</a>';
                            }
                        }
                ],
                footerCallback: function (row, data, start, end, display) {                          
                        var remarks = "";                                               
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                            remarks = data[i]['remarks'];
                        dataval = dataval.replace(',','');
                        TotalBatchPayoutAmount += parseInt(dataval);
                    }
                    // TotalBatchPayoutAmount = addCommas(TotalBatchPayoutAmount);
                    $('.batchpayout_holdtrans_totalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;'  ).display( TotalBatchPayoutAmount ));
                    $('.HoldTransactionMsg').html(remarks);
                }                
            }).ajax.reload(); 
            $('#ViewHoldTransDetailsModal').modal('toggle');
            SpinnerHide('btnViewHoldtransDetails',batch_id);
        });
        
    });
</script>