<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#ready-voucher-datatable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            "paging": true,
            dom: 'Bfrtip',
            "buttons": [
					{
						extend: 'collection',
						text: 'Export',
						buttons: [
                            {
								text: '<i class="fas fa-print"></i> PRINT',
                                // title: 'Total of Claimed Vouchers',
                                title: 'Report: Total of Ready Vouchers',
								extend: 'print',
								footer: true,
								exportOptions: {
									columns: ':visible'
								},
                                customize: function ( doc ) {
                                    $(doc.document.body).find('h1').css('font-size', '15pt');
                                    $(doc.document.body)
                                        .prepend(
                                            '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block"/>'
                                    );
                                },
							}, 
                            {
								text: '<i class="far fa-file-excel"></i> EXCEL',
                                title: 'Report: Total of Ready Vouchers',
								extend: 'excelHtml5',
								footer: true,
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-excel"></i> CSV',
                                title: 'Report: Total of Ready Vouchers',
								extend: 'csvHtml5',
                                footer: true,
								fieldSeparator: ';',
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-pdf"></i> PDF',
                                title: 'Report: Total of Ready Vouchers',
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
            ajax: "{{route('report.total_ready_vouchers')}}",
            
            columns: [
                {data: 'reg_name', name: 'reg_name'},
                {data: 'prov_name', name: 'prov_name'},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, orderable: true, searchable: true},
            ],
            "order": [[ 0, "asc" ], [ 1, "asc" ]],
            rowGroup: {
                startRender: null,
                endRender: function ( rows, group ) {
                    var total_amount_claim = rows
                        .data()
                        .pluck('amount')
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;
                        }, 0 );
                    total_amount_claim = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim );
    
                    return $('<tr/>')
                        .append( '<td colspan="3" class="text-left">Total amount claim:&nbsp;&nbsp;&nbsp;'+total_amount_claim+'</td>' )
                },
                dataSrc: function (data) {
                return data.reg;}
            },
            // Insert Footer callback()
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\₱,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Sub Total
                pageTotal = api
                    .column( 2, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Grand Total
                grandTotal = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 ); 

                pageTotal = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( pageTotal );
                grandTotal = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( grandTotal );

                // Update footer
                $( api.column( 2 ).footer() ).html("Grand Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + grandTotal);
            }
        });
        // seach filter select
        $('.filter-select').on('change', function(){
            table.column($(this).data('column')).search($(this).val()).draw();
        });
    });
</script>