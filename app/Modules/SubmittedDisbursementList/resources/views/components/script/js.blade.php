<script type="text/javascript">
       $(document).ready(function() {

            // CALL DATATABLE
            getSubmittedDisbursementList();

            // DISPLAY DATATABLE
            function getSubmittedDisbursementList(){
            var table = $('#SubmittedDisbursementList-datatable').DataTable({ 
                destroy: true, processing: true, serverside:true , responsive: true, 
                ajax: "{{ route('get.SubmittedDisbursementList') }}",
                columns: [ 
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'file_name', name: 'file_name', title:'UPLOADED FILE NAME'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq', title:'BATCH NUMBER'},
                        {data: 'name', name: 'name', title:'GENERATED FILE NAME'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0,  ).display, title: 'TOTAL RECORDS'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'dbp_batch_id', name: 'dbp_batch_id',  title:'ACTION',
                        render: function(data, type, row) {
                            if(row.issubmitted == 1){
                                return '<a href="javascript:;" class="text-danger"> POSTED</a>';
                            }else{
                                return '<a href="javascript:;" data-dbpbatchid="'+row.dbp_batch_id+'" class="btn btn-xs btn-outline-info btnPostDisbursedDBP" data-toggle="tooltip" data-placement="top" title="Posting Disbursement File"><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-thumbtack"></span> POST</a>';
                            }
                            
                        }
                    }
                    ],
                footerCallback: function (row, data, start, end, display) { 
                    var TotalAmount = 0,   
                        TotalCount = 0;                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'],
                            dataval2 = data[i]['total_records'];
                            TotalAmount += parseInt(dataval);
                            TotalCount += parseInt(dataval2);
                    }
                    $('.total_amt_approved_bene').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));       
                    $('.total_cnt_approved_bene').html($.fn.dataTable.render.number(',', '.', 0, '').display( TotalCount ));
                }
            });
        }

        $(document).on('click','.btnPostDisbursedDBP',function(){
            var _token = $("input[name=token]").val(),
                dbpbatchid = $(this).data('dbpbatchid');
            SpinnerShow('btnPostDisbursedDBP',dbpbatchid);
            Swal.fire({
                title: 'Are you sure',
                text: "You want to Post this submitted disbursement file?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Post',
                allowOutsideClick: false
                }).then((result) => {
                if (result.isConfirmed) {
                    OverlayPanel_in();
                    $.ajax({
                        type:'post',
                        url:"{{ route('post.Disbursementfile') }}",
                        data:{dbpbatchid:dbpbatchid,_token:_token},
                        success:function(data){ 
                            Swal.fire({
                                allowOutsideClick: false,
                                title:'Posted!',
                                text:'Your Disbursement file successfully Posted!',
                                icon:'success'
                            });                                  
                        getSubmittedDisbursementList();
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('btnPostDisbursedDBP',dbpbatchid);
                        }
                    });                       
                }else{
                    SpinnerHide('btnPostDisbursedDBP',dbpbatchid);           
                }
            });
        });
            
        });        
    </script>