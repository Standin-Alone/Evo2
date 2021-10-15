<script>
    $(document).ready(function () {       
        var route = $(location).attr('href');

        // var template = Handlebars.compile($("#details-template").html());
        var table = $('#farmer-details-datatable').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            "paging": false,
            ajax: { url: route},
            dom: 'lBfrtip',
            buttons: [
                { extend: 'print', footer: true }
            ],
            columns: [
                {
                    "className":      'details-control',
                    "targets":         [ 1 ],
                    "orderable":      false,
                    "searchable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                {data: 'fullname_column', name: 'fullname_column'},
                {data: 'description', name: 'description'},
                {data: 'item_name', name: 'item_name'},
                {data: 'quantity', name: 'quantity'},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                {data: 'transac_by_fullname', name: 'transac_by_fullname'},
                {data: 'payout_date', name: 'payout_date', orderable: true, searchable: true},
            ],
            "columnDefs": [
                            { "visible": false, "targets": 2 }
                          ],
            "order": [[ 2, 'asc' ]],
            "displayLength": 25,
            "drawCallback": function ( settings) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="text-light" style="background-color:rgb(88, 148, 189)"><td colspan="8">'+group+'</td></tr>'
                        );
    
                        last = group;
                    }
                });
            },
            order: [[2, 'asc']],
            rowGroup: {
                startRender: null,
                endRender: function ( rows) {
                    var total_amount_claim = rows
                        .data()
                        .pluck('total_amount')
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;
                        }, 0 );
                    total_amount_claim = $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim );
    
                    return $('<tr/>')
                        // .append( '<td colspan="3" class="text-left">'+group+'</td>' )
                        .append( '<td colspan="8" class="text-left">Total amount claim:&nbsp;&nbsp;&nbsp;'+total_amount_claim+'</td>' )
                },
                dataSrc: function (data) {
                return data.description;}
            },
        });
        var detailRows = [];
        // Add event listener for opening and closing row details of datatable
        $('#farmer-details-datatable tbody').on('click', 'td.details-control', function () {
            // var user_refno = $(this).closest("tr").find("td:eq(1)").text();
            // console.log(user_refno);

            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                tr.removeClass('shown');
                row.child.hide();
            }
            else {
                // Open this row
                tr.addClass('shown');
                row.child(template(row.data())).show();
                // Add to the 'open' array
                if ( idx === -1 ) {
                    detailRows.push( tr.attr('id') );
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        table.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                    $('#'+id+' td.details-control').trigger( 'click' );
                } );
            } );
        });
</script>