<script type="text/javascript">        
    $(document).ready(function (){   
        
        // CALL DATATABLE CONTENT
        BatchPayoutList();

        // DATATABE CONTENT
        function BatchPayoutList(){
            $('#BatchPayout-datatable').unbind('click');
            var table = $('#BatchPayout-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.BatchPayoutList') }}",
                dom: 'lBfrtip',
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        buttons: [
                            {
                                extend: 'collection',
                                text: 'Export Data <i class="fa fa-caret-down"></i>',
                                buttons: [
                                    {
                                        text: '<i class="fas fa-print"></i> PRINT',
                                        title: 'Report: Batch Payout',
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
                                        title: 'Batch Payout',
                                        extend: 'excelHtml5',
                                        footer: true,
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-excel"></i> CSV',
                                        title: 'Batch Payout',
                                        extend: 'csvHtml5',
                                        footer: true,
                                        fieldSeparator: ';',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-pdf"></i> PDF',
                                        title: 'Batch Payout',
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
                    {data: 'program', name: 'program', title:'PROGRAM'},
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'application_number', name: 'application_number', title: 'APPLICATION NO.'},
                    {data: 'description', name: 'description', title: 'DESCRIPTION'},
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    {data: 'payout_status', name: 'payout_status', title: 'APPROVAL STATUS'},                  
                    {data: 'action', name: 'action', orderable: true, searchable: true, title: 'ACTION'},
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
                    var TotalAmount = 0;                                        
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['grandtotalamount'];
                            TotalAmount = parseInt(dataval);
                        }
                        // DISPLAY GRAND TOTAL
                        $('.BatchPayout_totalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                }
            }).ajax.reload(); 

            // DRAW DATATABLE BASED ON THE DEFAULT PROGRAM
            var SessionId = $('#selectedProgramDesc').val();
            table.column(0).search(SessionId).draw();
        }       

        // CLICK FUNCTION TO CREATE BATCH WITH APPLICATION_NUMBER AND DESCRIPTION
        $(document).on('click','.btnCreateBatch', function(){
            SpinnerShow('btnCreateBatch','btnloadingIcon');
            $('.errormsg').css('display','none');
            $('.txtdescription').val('');
            $('#CreateBatchModal').modal('toggle');     
            SpinnerHide('btnCreateBatch','btnloadingIcon');    
        });

        // CLICK FUNCTION TO SAVE INPUTED BATCH DATA
        $(document).on('click','.btnSubmitBatch', function(){
            SpinnerShow('btnSubmitBatch','btnloadingIcon1');                        
            Swal.fire({
            title: 'Are you sure',
            text: "You want to create Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Create',
            allowOutsideClick: false,
            }).then((result) => {
            if (result.isConfirmed) {
                var _token = $("input[name=token]").val(),
                    program_id = $('#selectedProgramId').val(), 
                    desc = $('.txtdescription').val();                    
                if (desc == '') {
                        $('.errormsg').css('display','block');
                        $(".errormsg").html("Please enter value in both field!");
                        SpinnerHide('btnSubmitBatch','btnloadingIcon1'); 
                    }else{                   
                        $.ajax({
                            type:'post',
                            url:"{{ route('create.batchpayout') }}",
                            data:{program_id:program_id,desc:desc,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Created!',
                                    text:'Your Batch Payout successfully created!',
                                    icon:'success'
                                });
                                $('#BatchPayout-datatable').DataTable().ajax.reload();
                                $('.errormsg').css('display','none');
                                $('.txtdescription').val('');
                                $('#CreateBatchModal').modal('hide');
                                BatchPayoutList();
                                SpinnerHide('btnSubmitBatch','btnloadingIcon1'); 
                            },
                            error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnSubmitBatch','btnloadingIcon1'); 
                            }
                        }); 
                    }          
                }else{
                    SpinnerHide('btnSubmitBatch','btnloadingIcon1');   
                }
            });
        });

        // CLICK FUNCTION TO EDIT/MODIFY THE EXISTING BATCH
        $(document).on('click','.btneditbatchpayout', function(){                   
            var batch_id = $(this).data('editbatchid'),
            desc = $(this).data('editdescription'),
            amount = $(this).data('editamount');
            SpinnerShow('btneditbatchpayout',batch_id);
            if(amount > 0){                    
                Swal.fire(
                    'Failed!',
                    'Failed to process! Batch is already used by another transaction.',
                    'warning'
                )
                $('#EditBatchPayoutModal').modal('hide');
                SpinnerHide('btneditbatchpayout',batch_id);
            }else{
                $('.edit_txtbatch_id').val(batch_id);
                $('.edit_txtdescription').val(desc);
                $('#EditBatchPayoutModal').modal('toggle'); 
                SpinnerHide('btneditbatchpayout',batch_id);                   
            }                 
        });

        // CLICK FUNCTION TO UPDATE BATCH DATA
        $(document).on('click','.btnUpdateBatch', function(){
            SpinnerShow('btnUpdateBatch','btnloadingIcon2');

            Swal.fire({
            title: 'Are you sure',
            text: "You want to update Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Update',
            allowOutsideClick: false
            }).then((result) => {
            if (result.isConfirmed) {
                var _token = $("input[name=token]").val(),
                    batch_id = $('.edit_txtbatch_id').val(),
                    desc = $('.edit_txtdescription').val();
                if (batch_id == '' || desc == '') {
                        $('.errormsgedit').css('display','block');
                        $(".errormsgedit").html("Please enter value in both field!");
                        SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                    }else{                     
                        $.ajax({
                            type:'post',
                            url:"{{ route('update.batchpayout') }}",
                            data:{batch_id:batch_id,desc:desc,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Updated!',
                                    text:'Your Batch Payout successfully Updated!',
                                    icon:'success'
                                });
                                $('#BatchPayout-datatable').DataTable().ajax.reload();
                                $('.errormsgedit').css('display','none');
                                $('.edit_txtbatch_id, .edit_txtdescription').val('');
                                $('#EditBatchPayoutModal').modal('hide');
                                BatchPayoutList();
                                SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                            },
                            error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                            }
                            });
                    }                    
                }else{
                    SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                }
            });
        });

        // CLICK FUNCTION TO REMOVE SELECTED BATCH
        $(document).on('click','.btnremovebatchpayout', function(){            
            var _token = $("input[name=token]").val(),
                batch_id = $(this).data('selectedbatchid');
                desc = $('.txtdescription').val();
            SpinnerShow('btnremovebatchpayout',batch_id);
            Swal.fire({
            title: 'Are you sure',
            text: "You want to delete Batch Payout?",
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
                        url:"{{ route('remove.batchpayout') }}",
                        data:{batch_id:batch_id,_token:_token},
                        success:function(data){
                            Swal.fire({
                                allowOutsideClick: false,
                                title:'Removed!',
                                text:'Your Batch Payout successfully Removed!',
                                icon:'success'
                            });
                            $('#BatchPayout-datatable').DataTable().ajax.reload();
                            $('.errormsg').css('display','none');
                            $('.txtdescription').val('');
                            $('#CreateBatchModal').modal('hide');                           
                            BatchPayoutList();
                            SpinnerHide('btnremovebatchpayout',batch_id);
                        },
                        error: function (textStatus, errorThrown) {
                            console.log('Err');
                            BatchPayoutList();
                            SpinnerHide('btnremovebatchpayout',batch_id);
                        }
                    });                 
                }else{
                    SpinnerHide('btnremovebatchpayout',batch_id);
                }
            });        
        });

        $(document).on('keyup','.txtdescription',function(){
            var str = $(this).val();
            var res = str.toUpperCase();
            $(this).val(res);
        });


    });        
</script>