<script type="text/javascript">
    function amount_breakdown_result_datatable(fund_id, title){
         var route = "{{route('rffa_fund_disburse_breakdown')}}"+"/"+fund_id;
        // console.log(url);
        var table = $('#rffa_disbursement_breakdown_link').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ajax: {
                    url: route,
                },
                columns: [
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
                    total_amount = api
                            .column( 1 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 1 ).footer() ).html("Total Disbursed Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display(total_amount) );

                }

        });
        table.ajax.reload();
    }
</script>