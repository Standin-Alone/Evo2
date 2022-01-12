<script type="text/javascript">
        $(document).ready(function (){
            PayoutMonitoringContent();

            // DISPLAY DATATABLE
            function PayoutMonitoringContent(){
                $('#PayoutMonitoring-datatable').unbind('click');
                var TotalPayoutMoniatoring_pendingAmt = 0;
                var TotalPayoutMoniatoring_ApprovedAmt = 0; 
                var table = $('#PayoutMonitoring-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.PayoutMonitoringContent') }}",
                    dom: 'lBfrtip',
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        buttons: [
                            {
                                extend: 'collection',
                                text: 'Export Data <i class="fa fa-caret-down"></i>',
                                buttons: [
                                    {
                                        text: '<i class="fas fa-print"></i> PRINT',
                                        title: 'Report: Payout Monitoring',
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
                                        title: 'Payout Monitoring',
                                        extend: 'excelHtml5',
                                        footer: true,
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-excel"></i> CSV',
                                        title: 'Payout Monitoring',
                                        extend: 'csvHtml5',
                                        footer: true,
                                        fieldSeparator: ';',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-pdf"></i> PDF',
                                        title: 'Payout Monitoring',
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
                        {data: 'transac_date', name: 'transac_date', title:'TRANSACTION DATE'},
                        {data: 'supplier_name', name: 'reference_no', title:'SUPPLIER NAME'},
                        {data: 'application_number', name: 'last_name', title:'APPLICATION NUMBER'},
                        {data: 'description', name: 'first_name', title:'DESCRIPTION'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                        {data: 'payout_status', name: 'quantity', title:'STATUS'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, title:'ACTION'}, 
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
                            for (var i = 0; i < data.length; i++) {
                                var dataval_pending = data[i]['totalpendingamt'];
                                TotalPayoutMoniatoring_pendingAmt = parseInt(dataval_pending);
                            }
                            $('.totalpayoutMonitoringpendingamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalPayoutMoniatoring_pendingAmt ));

                            for (var i = 0; i < data.length; i++) {
                                var dataval_approved = data[i]['totalapprovedamt'];
                                TotalPayoutMoniatoring_ApprovedAmt = parseInt(dataval_approved);
                            }
                            $('.totalpayoutMonitoringapprovedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalPayoutMoniatoring_ApprovedAmt ));                    
                        }                        
                    }).ajax.reload();

                // DRAW DATATABLE BASED ON THE DEFAULT PROGRAM
                var SessionId = $('#selectedProgramDesc').val();
                table.column(0).search(SessionId).draw();
            }           
                

            // CALL VIEW ATTACHMENT ACTION UPON CLICK LINK PER ROWS OF THE DATATABLE
            $(document).on('click','.btnViewVoucherAttachments',function(){
                var _token = $("input[name=token]").val(),
                    voucher_id = $(this).data('selectvoucherid');
                $.ajax({
                    type:'get',
                    url:"{{ route('get.VoucherListAttachments') }}",
                    data:{voucher_id:voucher_id,_token:_token},
                    success:function(data){
                        $('.voucherattachmentsimg').html(data);
                        $('#ViewAttachmentsModal').modal('toggle');
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                        }
                }); 
            }); 

            $(document).on('click','.btnPayoutMonitoringDetails',function(){
                var  batch_id = $(this).data('selectedbatchid');
                $('#PayoutMonitoring_selectedbatchid').val(batch_id);
                PayoutMonitoringDetails(batch_id);
            });

            function PayoutMonitoringDetails(batch_id){                
                var totalPayoutMonitoringDetailsAmt = 0;   
                $('#PayoutMonitoringDetails-datatable').unbind('click');                           
                var table = $('#PayoutMonitoringDetails-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.PayoutMonitoringDetails') }}" + '?batch_id=' + batch_id,
                    columns: [                                            
                        {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                        {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO'},
                        {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                        {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                    ],
                    footerCallback: function (row, data, start, end, display) {  
                                        
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['total_amount'];
                            dataval = dataval.replace(',','');
                            totalPayoutMonitoringDetailsAmt += parseInt(dataval);
                        }
                        $('.payoutmonitoringdetailsamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( totalPayoutMonitoringDetailsAmt ));
                    }                
                }).ajax.reload();
            }

            $(document).on('click','.btnViewPayoutMonitoringattach',function(){
                var  voucher_id = $(this).data('selectvoucherid');
                PayoutMonitoringAttachments(voucher_id);
            });

            function PayoutMonitoringAttachments(voucher_id){
                var _token = $("input[name=token]").val();
                $.ajax({
                    type:'get',
                    url:"{{ route('get.PayoutMonitoringAttachImg') }}",
                    data:{voucher_id:voucher_id,_token:_token},
                    success:function(data){
                        $('.voucherattachmentsimg').html(data);
                        $('#ViewPayoutMonitoringAttachModal').modal('toggle');
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                        }
                });   
            }

        });
    </script>