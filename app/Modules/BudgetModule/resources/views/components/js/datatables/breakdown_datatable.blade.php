{{-- <script type="text/javascript">
    $(function() {
        var table = $('#fund_source_breakdown_tbl').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            dom: 'Bfrtip',
            "buttons": [
					{
						extend: 'collection',
						text: 'Export',
						buttons: [
                            {
								text: '<i class="fas fa-print"></i> PRINT',
                                // title: 'Total of Claimed Vouchers',
                                title: 'Report: Fund Source Breakdown',
								extend: 'print',
								footer: true,
								exportOptions: {
									columns: ':visible'
								},
                                customize: function ( doc ) {
                                    $(doc.document.body).find('h1').css('font-size', '15pt');
                                    $(doc.document.body)
                                        .prepend(
                                            '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                    );
                                    $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                },
							}, 
                            {
								text: '<i class="far fa-file-excel"></i> EXCEL',
                                title: 'Fund Source Breakdown',
								extend: 'excelHtml5',
								footer: true,
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-excel"></i> CSV',
                                title: 'Fund Source Breakdown',
								extend: 'csvHtml5',
                                footer: true,
								fieldSeparator: ';',
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-pdf"></i> PDF',
                                title: 'Fund Source Breakdown',
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
            ajax:  {
                url: $(location).attr('href')
            },
            columns: [
                // {data: 'fund_name', name: 'fund_name'},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'supplier_name', name: 'supplier_name'},
                {data: 'title', name: 'title'},
                // {data: 'description', name: 'description'},
                {data: 'item_name', name: 'item_name'},
                {data: 'quantity', name: 'quantity'},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, orderable: true, searchable: true},
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\₱,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // quantity column[5]: get it's total sum
                total_quantity = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;}, 0 );
                        $( api.column( 4 ).footer() ).html("Total quantity:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.').display(total_quantity) );

                // amount column[6]: get it's total sum
                total_amount = api
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;}, 0 );
                        $( api.column( 5 ).footer() ).html("Total amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );

                // Total amount column[7]: get it's total sum
                total_amount_of_voucher = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;}, 0 );
                        $( api.column( 6 ).footer() ).html("Total amount of voucher:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount_of_voucher) );
            }
        });
    });
</script> --}}