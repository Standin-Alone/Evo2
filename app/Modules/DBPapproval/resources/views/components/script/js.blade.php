<script type="text/javascript">
        $(document).ready(function (){
            
            DBPapprovalList();

            function DBPapprovalList(){
                    var table = $('#DBPapprovalList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    "paging": false,
                    ordering: false,
                    ajax: "{{ route('get.DBPapprovalList') }}",
                    columns: [ 
                        {data: 'checkbox', name: 'checkbox'},  
                        {data: 'created_at', name: 'created_at'},
                        {data: 'name', name: 'name'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                }).ajax.reload();
                    // RETURN INTO ZERO WHEN USE SEARCH BOX
                    $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                        $('.SubmitPayouts_totalselectedamt').html("0.00"); 
                        $('#SubmitPayouts_selectedamt').val("0.00");
                    });
                
            }
            
            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#DBPapprovalList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#DBPapprovalList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            // Handle click on checkbox
            $('#DBPapprovalList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });

            $('#DBPapprovalList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');
                if(this.checked){
                    $row.addClass("selected");
                }else{ 
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });


            $(document).on('click','.btnDBPapproval',function(){
                SpinnerShow('btnDownloadExcelFile','btnloadingIcon');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Approve all selected DBP Batch?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Approve',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        batchid = $('.selecteddbpbatch:checked').map(function(){
                        return $(this).data('selecteddbpbatchid');}).get().join(',');
                        if(batchid != ""){  
                            $.ajax({
                            type:'post',
                            url:"{{ route('approve.DBPapproval') }}",
                            data:{batchid:batchid,_token:_token},
                                success:function(data){
                                    Swal.fire(
                                        'Approved!',
                                        'Your DBP Batch successfully Approved!',
                                        'success'
                                    )
                                    DBPapprovalList();
                                    $('.errormsg_dbpapproval').css('display','none');
                                    SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                },
                                error: function (textStatus, errorThrown) {
                                        console.log('Err');
                                        SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                        $('.errormsg_dbpapproval').css('display','none');
                                    }
                            });
                        }else{                            
                                $('.errormsg_dbpapproval').css('display','block');
                                $(".errormsg_dbpapproval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select DBP Batch for Approval! Please try again.");     
                                SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                            }
                        
                        
                        }else{
                            SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                        }
                    });
            });

            $(document).on('click','.btnApprovedHistoryList',function(){
                SpinnerShow('btnDownloadExcelFile','btnloadingIcon1');
                var table = $('#ApprovedHistoryList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.DBPapproveddHistoryList') }}",
                    columns: [ 
                            {data: 'created_at', name: 'created_at', title:'DATE APPROVED'},
                            {data: 'file_name', name: 'file_name', title:'DBP BATCH CODE'},
                            {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                            {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    }).ajax.reload();
                $('#ApprovedHistoryModal').modal('toggle');
                SpinnerHide('btnDownloadExcelFile','btnloadingIcon1');
           });

           $(document).on('click','.btnDownloadTextFile',function(){
                var filename = $(this).data('textfilename');
                $('#download_filename').val(filename);
                SpinnerShow('btnDownloadTextFile',filename);
                $('#dbpapprovalPincodeModal').modal('toggle');
                SpinnerHide('btnDownloadTextFile',filename);   
           });

           $(document).on('click','.dbpapproval_validate',function(){
            var _token = $("input[name=token]").val(),
                filename = $('#download_filename').val(),
                email = $('.dbpapproval_email').val(),
                password = $('.dbpapproval_password').val();
            SpinnerShow('dbpapproval_validate','btnloadingIcon4');
            $.ajax({
                type:'post',
                url:"{{ route('validate.DBPApprovalPin') }}",
                data:{filename:filename,email:email,password:password,_token:_token},
                    success:function(data){
                        if(data == "INVALID"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4'); 
                        }else if(data == "NO_EXIST"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4'); 
                        }else{   
                            $('#dbpapprovalPincodeModal').modal('hide');                            
                            $('.errormsg').css('display','none');
                            // $('.dbpapproval_downloadfile').click();
                            window.location = $('.dbpapproval_downloadfile').attr('href');
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4');                             
                        }                        
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4'); 
                        }
                });
           });
            
        });
        
</script>