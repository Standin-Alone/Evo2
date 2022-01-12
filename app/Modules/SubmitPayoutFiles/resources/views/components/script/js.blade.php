<script type="text/javascript">
        $(document).ready(function (){

            SubmitPayoutFileList();

            function SubmitPayoutFileList(){
                var table = $('#SubmitPayoutFileList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.SubmitPayoutFileList') }}",
                    columns: [
                        {data: 'created_at', name: 'created_at', title: 'TRANSACTION DATE'},
                        {data: 'name', name: 'name', title: 'FILE NAME'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                    ],
                });
            }

            $(document).on('click','.btnGenerateKey',function(){
                var _token = $("input[name=token]").val(),
                    dbp_batch_id = $(this).data('selecteddbpfileid'),
                    file_name = $(this).data('selectedfilename');
                    SpinnerShow('btnGenerateKey',file_name);

                Swal.fire({
                title: 'Are you sure',
                text: "You want to Generate Textfile with Private Key?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Submit',
                allowOutsideClick: false
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'get',
                        url:"{{ route('generate.PrivateKey') }}",
                        data:{dbp_batch_id:dbp_batch_id,file_name:file_name,_token:_token},
                        success:function(data){  
                            if(data == "failed"){
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Failed!',
                                    text:'File directory does not exist!',
                                    icon:'error'
                                });
                                SubmitPayoutFileList();
                                SpinnerHide('btnGenerateKey',file_name);
                            }else{
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Generated!',
                                    text:'Your DBP textfile successfully Generated!',
                                    icon:'success'
                                });
                                SubmitPayoutFileList();
                                SpinnerHide('btnGenerateKey',file_name);
                            }                       
                            
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('btnGenerateKey',file_name);
                                }
                            });
                        }else{
                            SpinnerHide('btnGenerateKey',file_name);
                        }
                    });
            });

            $(document).on('click','.btngeneratedtextfilehistory',function(){
                SpinnerShow('btngeneratedtextfilehistory','btnloadingIcon');
                var table = $('#GeneratedtextfileHistorylist-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.GeneratedTextfileHistory') }}",
                    columns: [ 
                        {data: 'created_at', name: 'created_at', title: 'TRANSACTION DATE'},
                        {data: 'name', name: 'name', title: 'FILE NAME'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    }).ajax.reload();
                $('#GeneratedtextfileHistoryModal').modal('toggle');
                SpinnerHide('btngeneratedtextfilehistory','btnloadingIcon');
           });
            

        });
        
</script>