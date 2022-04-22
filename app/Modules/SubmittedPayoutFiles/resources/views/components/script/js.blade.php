<script type="text/javascript">
        $(document).ready(function (){

            SubmittedPayoutFilesList();
            Total_Completed_Payout_Textfiles();

            function SubmittedPayoutFilesList(){
                var table = $('#SubmittedPayoutFilesList-datatable').DataTable({
                    destroy: true,
                    processing: true, 
                    serverside: true,                 
                    responsive: true,
                    ordering: false,
                    ajax: "{{ route('get.SubmittedPayoutFilesList') }}",
                    columns: [
                        {data: 'application_number', name: 'application_number',
                            render: function(data, type, row) {
                                return  '<img class="result-image" src="assets/img/product/submittedfiles_1.png" height="auto"/>';
                            }
                        },
                        {data: 'application_number', name: 'application_number',
                                render: function(data, type, row) {
                                        return  '<div class="result-info">'+
                                                    '<br><h4 class="title">' + row.payout_trans_code + '</h4>'+
                                                        '<p class="location">Program: <strong>'+ row.program +'</strong></p>'+                                                                                                    
                                                        '<p class="location" style="margin-top:-10px;">No. of Merchants: <strong> '+ $.fn.dataTable.render.number(',', '.', 0, '').display( row.merchant_count ) +'</strong></p>'+
                                                        '<p class="location" style="margin-top:-10px;">Total Vouchers: <strong> '+ $.fn.dataTable.render.number(',', '.', 0, '').display( row.cnt_vouchers ) +'</strong></p>'+
                                                        '<p class="location" style="margin-top:-10px;">Date File Created: <strong> '+ row.textfile_gen_date +'</strong></p>'+                                                        
                                                    '</div><hr class="bg-primary">';
                                }
                            },
                        {data: 'application_number', name: 'application_number',
                            render: function(data, type, row) {
                                return  '<div class="result-price">'+
                                            '<br><h3 class="d-flex flex-row justify-content-center mt-auto">'+ $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( row.amount ) +'</h3><small class="d-flex flex-row justify-content-center mt-auto">TOTAL AMOUNT</small>'+
                                            '<br><a href="javascript:void(0)" data-selectedid="'+row.payout_trans_code+'" data-selecteddownloadid="'+row.payout_file_stat+'" class="btn btn-outline-primary btn-block btn_SubmittedPayoutFiles_Complete"><i class="fas fa-spinner fa-spin '+row.payout_trans_code+' pull-left m-r-10" style="display: none;"></i> Complete</a>'+
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
                            "infoEmpty": ''
                        }
                    });
            }

            $(document).on('click','.btn_SubmittedPayoutFiles_Complete',function(){
                var _token = $("input[name=token]").val(),
                    selecteddownloadid = $(this).data('selecteddownloadid'),
                    selectedid = $(this).data('selectedid');
                    SpinnerShow('btn_SubmittedPayoutFiles_files',selecteddownloadid);
                    Swal.fire({
                        title: 'Are you sure',
                        text: "You want mark as complete?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Mark it!',
                        allowOutsideClick: false
                        }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type:'post',
                                url:"{{route('complete.SubmittedPayoutFiles')}}",
                                data:{selecteddownloadid:selecteddownloadid,selectedid:selectedid,_token:_token},
                                success:function(data){  
                                    Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Completed!',
                                            text:'Your Batch Payout File successfully Completed!',
                                            icon:'success'
                                        });
                                        SubmittedPayoutFilesList();
                                        Total_Completed_Payout_Textfiles();
                                        SpinnerHide('btn_SubmittedPayoutFiles_files',selecteddownloadid);                    
                                    
                                    },
                                    error: function (textStatus, errorThrown) {
                                            console.log('Err');
                                            SpinnerHide('btn_SubmittedPayoutFiles_files',selecteddownloadid);
                                        }
                                    });
                                }else{
                                    SpinnerHide('btn_SubmittedPayoutFiles_files',selecteddownloadid);
                                }
                            });
            });

            $(document).on('click','.btn_CompletedPayoutTextfiles',function(){
                SpinnerShow('btn_CompletedPayoutTextfiles','btnloadingIcon');
                var table = $('#CompletedPayoutTextfilesHistory-Datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.CompletedPayoutTextfilesHistory') }}",
                    columns: [ 
                        {data: 'textfile_gen_date', name: 'textfile_gen_date', title: 'TRANSACTION DATE'},
                        {data: 'payout_trans_code', name: 'payout_trans_code', title: 'FILE NAME'},
                        {data: 'merchant_count', name: 'merchant_count',render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title: 'NO. OF MERCHANTS'},
                        {data: 'cnt_vouchers', name: 'cnt_vouchers',render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title: 'TOTAL VOUCHERS'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        ]
                    });
                $('#CompletedPayoutTextfilesHistoryModal').modal('toggle');
                SpinnerHide('btn_CompletedPayoutTextfiles','btnloadingIcon');
            });   
            
            function Total_Completed_Payout_Textfiles(){
                $.ajax({
                    type:'get',
                    url:"{{ route('get.Total_Completed_Payout_Textfiles') }}",
                    success:function(data){   

                            $('.amt_Total_Completed_Payout_Textfiles').html('0.00');
                            $('.cnt_Total_Completed_Payout_Textfiles').html('0');                      

                        for(var i=0;i<data.length;i++){           
                                if(data[i].total_types == "total_approved_amount"){
                                    $('.amt_Total_Completed_Payout_Textfiles').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(+data[i].total_amount));
                                    $('.cnt_Total_Completed_Payout_Textfiles').html($.fn.dataTable.render.number(',', '.', 0, '').display(+data[i].total_count));
                                }                           
                            }                        
                        },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                        }
                    });  
        }

        });
        
</script>