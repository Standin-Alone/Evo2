<script type="text/javascript">
        $(document).ready(function (){
            
            SubmitPayoutList();
            SubmitPayoutGeneratedList();

            function SubmitPayoutList(){
                var table = $('#SubmitPayoutsList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    paging: false,
                    ordering:false,
                    srcollY: "300px",
                    ajax: "{{ route('get.SubmitPayoutsList') }}",
                    columns: [
                        {data: 'checkbox', name: 'checkbox'},  
                        {data: 'transac_date', name: 'transac_date'},
                        {data: 'application_number', name: 'application_number'},
                        {data: 'description', name: 'description'},
                        {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    ]
                }).ajax.reload();
                // RETURN INTO ZERO WHEN USE SEARCH BOX
                $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                    $('.SubmitPayouts_totalselectedamt').html("0.00"); 
                    $('#SubmitPayouts_selectedamt').val("0.00");
                });                
            }

            function SubmitPayoutGeneratedList(){
                var table = $('#SubmitPayoutGeneratedList-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,                
                ajax: "{{ route('get.SubmitPayoutGeneratedList') }}",
                columns: [ 
                    {data: 'created_at', name: 'created_at', title:'TRANSACTION DATE'},
                    {data: 'folder_file_name', name: 'folder_file_name', title: 'FILE NAME'},
                    {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title: 'TOTAL RECORDS'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                ]
                }).ajax.reload();
            }

            $(document).on('click','.btnViewVoucherAttachments',function(){
                var _token = $("input[name=token]").val(),
                voucher_id = $(this).data('selectvoucherid');
                SpinnerShow('btnViewVoucherAttachments',voucher_id);
                $.ajax({
                    type:'get',
                    url:"{{ route('get.VoucherListAttachments') }}",
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

            $(document).on('click','.SubmitPayout_btnGenerateEXCEL',function(){
                SpinnerShow('SubmitPayout_btnGenerateEXCEL','btnloadingIcon');
                $('.errormsg_generateexcel').css('display','none');
                $('#SubmitPayouts_selectedamt').val("0.00");  
                $('.SubmitPayouts_totalselectedamt').html("0.00");
                $('#selectPayoutExportModal').modal('toggle');  
                SubmitPayoutList();
                SpinnerHide('SubmitPayout_btnGenerateEXCEL','btnloadingIcon');
            });

            $(document).on('click','.SubmitPayout_btnSubmitSelectedBatch',function(){
                SpinnerShow('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Generate Payout in Excel?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Generate',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        batchid = $('.selectedbatch:checked').map(function(){
                        return $(this).data('selectedbatchid');}).get().join(',');
                        if(batchid != ""){  
                            $.ajax({
                                type:'post',
                                url:"{{ route('generate.SupplierPayoutExcel') }}",
                                data:{batchid:batchid,_token:_token},
                                    success:function(data){
                                        if(data == "failed"){
                                            Swal.fire({
                                                allowOutsideClick: false,
                                                title:'Failed!',
                                                text:'You are already generate DBP Batch!',
                                                icon:'danger'
                                            });
                                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                        }else{
                                            Swal.fire({
                                                allowOutsideClick: false,
                                                title:'Generated!',
                                                text:'Your excel file successfully Generated!',
                                                icon:'success'
                                            });
                                            SubmitPayoutGeneratedList();
                                            $('.errormsg_generateexcel').css('display','none');
                                            $('#selectPayoutExportModal').modal('hide');
                                            $('#SubmitPayouts_selectedamt').val("0.00");  
                                            $('.SubmitPayouts_totalselectedamt').html("0.00");
                                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                        }
                                        
                                    },
                                    error: function (textStatus, errorThrown) {
                                            console.log('Err');
                                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                        }
                                });
                                }else{
                                    $('.errormsg_generateexcel').css('display','block');
                                    $(".errormsg_generateexcel").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select Batch Payout for Generation of excel! Please try again.");
                                    SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                }
                        }else{
                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                        }
                    });
            });
            
            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#SubmitPayoutsList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#SubmitPayoutsList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            // Handle click on checkbox
            $('#SubmitPayoutsList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });

            $('#SubmitPayoutsList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var total_amount = $(this).data('selectedbatchamt');
                var $row = $(this).closest('tr');
                if(this.checked){
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#SubmitPayouts_selectedamt').val()); 
                    var total = selectedamt + total_amount;
                    $('#SubmitPayouts_selectedamt').val(total);
                    $('.SubmitPayouts_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total));
                    $row.addClass("selected");
                }else{                 
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#SubmitPayouts_selectedamt').val()); 
                    var total = selectedamt - total_amount;
                    $('#SubmitPayouts_selectedamt').val(total);
                    $('.SubmitPayouts_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total));    
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });

            function ApprovedHistoryList(){
                    var table = $('#ApprovedHistoryList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.SubmitteddHistoryList') }}",
                    columns: [ 
                            {data: 'transac_date', name: 'transac_date', title:'DATE SUBMITTED'},
                            {data: 'file_name', name: 'file_name', title:'DBP BATCH CODE'},
                            {data: 'application_number', name: 'application_number', title:'APPLICATION NUMBER'},
                            {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        ]
                    }).ajax.reload();               
           }

            $(document).on('click','.btnApprovedHistoryList',function(){
                SpinnerShow('btnApprovedHistoryList','btnloadingIcon3');
                ApprovedHistoryList();
                $('#ApprovedHistoryModal').modal('toggle');
                SpinnerHide('btnApprovedHistoryList','btnloadingIcon3');
           });

           $(document).on('click','.btnDownloadExcelFile',function(){
                var filename = $(this).data('excelfilename');
                $('#download_filename').val(filename);
                SpinnerShow('btnDownloadExcelFile',filename);
                $('#SubmitPayoutPincodeModal').modal('toggle');
                SpinnerHide('btnDownloadExcelFile',filename);                
           });

           $(document).on('click','.SubmitPayout_validate',function(){
            var _token = $("input[name=token]").val(),
                filename = $('#download_filename').val(),
                email = $('.SubmitPayout_email').val(),
                password = $('.SubmitPayout_password').val();
            SpinnerShow('SubmitPayout_validate','btnloadingIcon4');
            $.ajax({
                type:'post',
                url:"{{ route('validate.SubmitPayoutPin') }}",
                data:{filename:filename,email:email,password:password,_token:_token},
                    success:function(data){
                        if(data == "INVALID"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                        }else if(data == "NO_EXIST"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                        }else{   
                            $('#SubmitPayoutPincodeModal').modal('hide');                            
                            $('.errormsg').css('display','none');
                            // $('.SubmitPayout_downloadfile').click();
                            window.location = $('.SubmitPayout_downloadfile').attr('href');
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                            
                        }
                        
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                        }
                });
           });
            
        });
        
</script>