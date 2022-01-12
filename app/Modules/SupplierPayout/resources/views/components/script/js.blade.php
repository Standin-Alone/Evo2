<script type="text/javascript">
    $(document).ready(function (){                   
        
        supplierpayoutlist();

        // DATATABE CONTENT
        function supplierpayoutlist(){
            $('#supplierpayout-datatable').unbind('click');
            var table = $('#supplierpayout-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.SupplierPayoutList') }}",
                dom: 'lBfrtip',
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        buttons: [
                            {
                                extend: 'collection',
                                text: 'Export Data <i class="fa fa-caret-down"></i>',
                                buttons: [
                                    {
                                        text: '<i class="fas fa-print"></i> PRINT',
                                        title: 'Report: Supplier Payout',
                                        extend: 'print',
                                        footer: true,
                                        exportOptions: {
                                            columns: ':visible'
                                        },
                                        customize: function ( doc ) {
                                            $(doc.document.body).find('h1').css('font-size', '15pt');
                                            $(doc.document.body)
                                                .prepend(
                                                    '<img src="{{url("assets/img/logo/DA-Logo.png")}}" width="10%" height="5%" style="display: inline-block"/>'
                                            );
                                        },
                                    }, 
                                    {
                                        text: '<i class="far fa-file-excel"></i> EXCEL',
                                        title: 'Supplier Payout',
                                        extend: 'excelHtml5',
                                        footer: true,
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-excel"></i> CSV',
                                        title: 'Supplier Payout',
                                        extend: 'csvHtml5',
                                        footer: true,
                                        fieldSeparator: ';',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-pdf"></i> PDF',
                                        title: 'Supplier Payout',
                                        extend: 'pdfHtml5',
                                        footer: true,
                                        message: '',
                                        exportOptions: {
                                            columns: ':visible'
                                        },
                                    }, 
                                ]
                            }
                        ],
                columns: [
                    {data: 'program', name: 'program', title: 'PROGRAM'},
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACITON DATE'},
                    {data: "application_number",
                        render: function(data) {
                            data = '<a href="javascript:void(0);" data-statuspayoutappnum="' + data + '" class="statuspayoutlink"><i class="fas fa-spinner fa-spin '+ data +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                            return data;
                        }, title: 'APPLICATION NO.'},
                    {data: 'description', name: 'description', title: 'DESCRIPTION'},
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}
                ],
                columnDefs: [
                            { "visible": false, "targets": 0,}
                        ],
                order: [[0, 'asc']],
                rowGroup: {
                    dataSrc: function (data) {
                            return '<span>'+data.program+'</span>';
                        },
                    starRender:null,
                    endRender: function(rows){
                            var total_amount_claim = rows
                            .data()
                            .pluck('amount')
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;
                            }, 0 );
                            return '<span>Page Total: '+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim )+'</span>';
                        },                    
                    },
                footerCallback: function (row, data, start, end, display) {
                    var TotalCreatedAmount = 0;                    
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['grandtotalamount'];
                        TotalCreatedAmount = parseInt(dataval);
                    }
                    $('.batchpayoutsum').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalCreatedAmount ));

                    var TotalPendingPayoutAmount = 0;                    
                    for (var i = 0; i < data.length; i++) {
                        var amount = data[i]['totalpending'];
                        TotalPendingPayoutAmount = parseInt(amount);                    
                    }                    
                    $('.BatchPayout_PendingSum').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalPendingPayoutAmount ));
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

        function datatablecheckbox(batch_id){ 
            $('#ClaimedVoucher-datatable').unbind('click');             
            var table = $('#ClaimedVoucher-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                scrollY: "300px",
                // responsive: true,
                paging: false,
                ordering: false,
                ajax: "{{ route('get.SupplierPayout_ClaimedVoucherDetails') }}" + '?batch_id=' + batch_id,
                columns: [
                    {data: 'checkbox', name: 'checkbox'},                        
                    {data: 'transac_date', name: 'transac_date'},
                    {data: 'reference_no', name: 'reference_no'},
                    {data: 'item_name', name: 'item_name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                ]                
            }).ajax.reload(); 
                        
            // RETURN INTO ZERO WHEN USE SEARCH BOX
            $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                $('.SupplierPayout_totalselectedamt').html("0.00"); 
                $('#selected_voucheramt').val("0.00");
            });
        }

        // Handle click on checkbox
        $('#ClaimedVoucher-Datatable').on('click', 'td:first-child', function(e){
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });                  

        // Handle click on checkbox
        $('#ClaimedVoucher-Datatable').on('click', 'input[type="checkbox"]', function(e){
            var total_amount = parseInt($(this).data('selectedvoucheramt'));  
            var selected_voucheramt = parseInt($('#selected_voucheramt').val());  
            var $row = $(this).closest('tr');
            if(this.checked){
                var total = selected_voucheramt + total_amount;
                $('#selected_voucheramt').val(total);
                var totalamt = addCommas(total);
                $('.SupplierPayout_totalselectedamt').html(totalamt); 
                $row.addClass('selected');
            }else{                    
                var total = selected_voucheramt - total_amount;
                $('#selected_voucheramt').val(total);
                var totalamt = addCommas(total);                
                $('.SupplierPayout_totalselectedamt').html(totalamt);  
                $row.removeClass('selected');
            }                    
            e.stopPropagation();
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
            confirmButtonText: 'Create',
            allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    selectedvoucherid = $('.selectedvoucher:checked').map(function(){
                    return $(this).data('selectedvoucherid');}).get().join(',');

                    selectedvoucheramt = $('.selectedvoucher:checked').map(function(){
                    return $(this).data('selectedvoucheramt');}).get().join(',');
                    
                    batch_id = $('.selectbatchpayout').val();

                    if (batch_id == '' || selectedvoucherid == '') {
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please Select value in both field!");
                            SpinnerHide('btnCreate','btnloadingIcon1');
                        }else{                            
                            var _token = $("input[name=token]").val();
                            $.ajax({
                                type:'post',
                                url:"{{ route('save.SupplierPayout') }}",
                                data:{selectedvoucherid:selectedvoucherid,selectedvoucheramt:selectedvoucheramt,batch_id:batch_id,_token:_token},
                                success:function(data){                         
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        title:'Created!',
                                        text:'Your Supplier Payout successfully Created!',
                                        icon:'success'
                                    });
                                    supplierpayoutlist();
                                    document.getElementById("default_BatchPayout").value = "";
                                    $('.selectedvoucher, .selectedvoucherall').prop("checked", false);
                                    $('.errormsg').css('display','none');
                                    $('.totalselected ').html('0.00');
                                    $('#CreatePayoutModal').modal('hide');
                                    $('.SupplierPayout_totalselectedamt').html("0.00"); 
                                    $('#selected_voucheramt').val("0.00");
                                    SpinnerHide('btnCreate','btnloadingIcon1');
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('btnCreate','btnloadingIcon1');
                                }
                            });  
                        }
                    
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
            confirmButtonText: 'Remove',
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
                                document.getElementById("default_BatchPayout").value = "";
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
                serverSide: true,
                responsive: true,
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
            confirmButtonText: 'Submit',
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
                            document.getElementById("default_BatchPayout").value = "";
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
                responsive: true,
                ajax: "{{ route('get.SupplierPayout_HoldTransDetails') }}" + '?batch_id=' + batch_id,
                columns: [                       
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                    {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                    {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                    {data: 'amount', name: 'amount', title: 'AMOUNT'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}, 
                ],
                footerCallback: function (row, data, start, end, display) {                          
                        var remarks = "";                                               
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                            remarks = data[i]['remarks'];
                        dataval = dataval.replace(',','');
                        TotalBatchPayoutAmount += parseInt(dataval);
                    }
                    TotalBatchPayoutAmount = addCommas(TotalBatchPayoutAmount);
                    $('.batchpayout_holdtrans_totalamt').html(TotalBatchPayoutAmount);
                    $('.HoldTransactionMsg').html(remarks);
                }                
            }).ajax.reload(); 
            $('#ViewHoldTransDetailsModal').modal('toggle');
            SpinnerHide('btnViewHoldtransDetails',batch_id);
        });
        
    });
</script>