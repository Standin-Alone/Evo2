<script type="text/javascript">
    $(document).ready(function() {

        // CALL DATATABLE
        getTotalVoucherTrans();

        // DISPLAY DATATABLE
        function getTotalVoucherTrans(){
            $('#VoucherTrans-datatable').unbind('click');
            var table = $('#VoucherTrans-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.VoucherTransList') }}",
                dom: 'lBfrtip',
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                buttons: [
                    {
						extend: 'collection',
						text: 'Export Data <i class="fa fa-caret-down"></i>',
						buttons: [
                            {
								text: '<i class="fas fa-print"></i> PRINT',
                                title: 'Report: Voucher Transaction Monitoring',
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
                                title: 'Voucher Transaction Monitoring',
								extend: 'excelHtml5',
								footer: true,
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-excel"></i> CSV',
                                title: 'Voucher Transaction Monitoring',
								extend: 'csvHtml5',
                                footer: true,
								fieldSeparator: ';',
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-pdf"></i> PDF',
                                title: 'Voucher Transaction Monitoring',
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
                    {data: 'reference_no', name: 'reference_no', title:'REFERENCE NO.'},
                    {data: 'last_name', name: 'last_name', title:'LAST NAME'},
                    {data: 'first_name', name: 'first_name', title:'FIRST NAME'},
                    {data: 'middle_name', name: 'middle_name', title:'MIDDLE NAME'},
                    {data: 'quantity', name: 'quantity', title:'QUANTITY'},
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                    {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},    
                    {data: 'action',name: 'action', orderable: true,searchable: true, title:'ACTION'},                    
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
                            .pluck('total_amount')
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
                        $('.totalclaimedvoucheramt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                }
            }).ajax.reload();                         
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
        
        $(document).on('click','.linkVoucherPendingPayout',function(){
            SpinnerShow('linkVoucherPendingPayout','btnloadingIcon1');
            window.location.href = "{{ route('Voucherspendingpayout.index') }}";
            SpinnerHide('linkVoucherPendingPayout','btnloadingIcon1');
        });

        $(document).on('click','.linkVoucherApprovedPayout',function(){
            SpinnerShow('linkVoucherApprovedPayout','btnloadingIcon2');
            window.location.href = "{{ route('Vouchersapprovedpayout.index') }}";
            SpinnerHide('linkVoucherApprovedPayout','btnloadingIcon2');
        });

        $(document).on('click','.linkVoucherHoldTransaction',function(){
            SpinnerShow('linkVoucherHoldTransaction','btnloadingIcon3');
            window.location.href = "{{ route('Vouchersholdtransaction.index') }}";
            SpinnerHide('linkVoucherHoldTransaction','btnloadingIcon3');
        });

    });
</script>