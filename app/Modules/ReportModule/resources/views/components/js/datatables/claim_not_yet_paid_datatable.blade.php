<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#not-yet-claimed-datatable').DataTable({
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
                                title: 'Report: Total of Claimed, Not Yet Paid',
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
                                title: 'Report: Total of Claimed, Not Yet Paid',
								extend: 'excelHtml5',
								footer: true,
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-excel"></i> CSV',
                                title: 'Report: Total of Claimed, Not Yet Paid',
								extend: 'csvHtml5',
                                footer: true,
								fieldSeparator: ';',
								exportOptions: {
									columns: ':visible'
								}
							}, 
                            {
								text: '<i class="far fa-file-pdf"></i> PDF',
                                title: 'Report: Total of Claimed, Not Yet Paid',
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
            ajax: "{{route('report.claimed_not_yet_paid')}}",
            
            columns: [
                {data: 'supplier_name', name: 'supplier_name'},
                {data: 'description', name: 'description'},
                {data: 'reg_name', name: 'reg_name'},
                {data: 'prov_name', name: 'prov_name'},
                {data: 'quantity', name: 'quantity'},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display},
                {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display, orderable: true, searchable: true},
                {data: 'payout_status', name: 'payout_status'},
            ],
            "columnDefs": [
                            { "visible": false, "targets": 0,}
                        ],
            "order": [ 0, "asc" ],
            "displayLength": 25,
            "drawCallback": function ( settings) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="text-light" style="background-color:rgb(88, 148, 189)"><td colspan="7">'+group+'</td></tr>'
                        );
                        last = group;
                    }
                });
            },
            "order": [ 0, "asc" ],
            rowGroup: {
                startRender: null,
                endRender: function ( rows, group ) {
                    var total_amount_claim = rows
                        .data()
                        .pluck('total_amount')
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;
                        }, 0 );
                    total_amount_claim = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim );
    
                    return $('<tr/>')
                        .append( '<td colspan="8" class="text-left">Total amount claim:&nbsp;&nbsp;&nbsp;'+total_amount_claim+'</td>' )
                },
                dataSrc: function (data) {
                    return data.supplier_name;
                }
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
    
                // Total over this page
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                // Grand Total
                grandTotal = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 ); 
    
                pageTotal = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( pageTotal );
                grandTotal = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( grandTotal );

                // Update footer
                $( api.column( 6 ).footer() ).html("Grand Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + grandTotal);
            }
        });
        // seach filter select
        $('.filter-select').on('change', function(){
            table.column($(this).data('column')).search($(this).val()).draw();
        });
    });
</script>