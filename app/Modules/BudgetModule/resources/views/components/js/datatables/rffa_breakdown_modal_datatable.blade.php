<script type="text/javascript">
    function amount_breakdown_result_datatable(fund_id, title){
         var route = "{{route('rffa_fund_disburse_breakdown')}}"+"/"+fund_id;
        // console.log(route);
        var table = $('#rffa_disbursement_breakdown_link').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            dom: 'lBfrtip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "buttons": [
                        {
                            extend: 'collection',
                            text: 'Export',
                            buttons: [
                                {
                                    text: '<i class="fas fa-print"></i> PRINT',
                                    title: 'Budget: Disburse Breakdown',
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
                                    title: 'Budget: Disburse Breakdown',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Budget: Disburse Breakdown',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Budget: Disburse Breakdown',
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
                ajax: {
                    url: route,
                },
                columns: [
                    {data: 'rsbsa_no', name: 'rsbsa_no'},
                    {data: 'fullname_column', name: 'fullname_column'},
                    {data: 'account_number', name: 'account_number'},
                    {data: 'title', name: 'title'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
                ],
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                        tableClass: 'table'
                    } )
                }
            },
            footerCallback: function ( row, data, start, end, display ) {
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
                    .column( 1, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    // Grand Total
                    grandTotal = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );

                    pageTotal = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( pageTotal );
                    grandTotal = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( grandTotal );
                    
                    $( api.column( 4 ).footer() ).html(" Grand Total of Disbursed Amount:&nbsp;&nbsp;"+$.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display(grandTotal) );

            }
        });
    }
</script>