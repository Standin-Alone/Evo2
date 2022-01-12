<script type="text/javascript">
    $(function() {

        var table = $('#disbursement_table').DataTable({
                select: true,
                processing: true,
                serverSide: true,
                ajax: "{{route('fund_moni_and_disb')}}",
                columns: [
                    {data: 'title', name: 'title', orderable: false, searchable: true},
                    
                    {data: 'region_name', name: 'region_name', orderable: false, searchable: true},

                    // Total Amount of Fund Source
                    {data: 'total_amount', name: 'total_amount', 
                        // render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                        render: function(data, type, row, meta){
                            // if (type == 'display') {
                                return '&#8369; '+number_format(data, 2, '.', ',');
                            // }
                        }, orderable: false, searchable: true,
                    },

                    // Total Disbursement amount of Excel Export
                    {data: 'disbursement_amount', name: 'disbursement_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display, orderable: false, searchable: true},
                    
                    // Total Amount - Disbursement Amount
                    {data: 'remaining_amount', name: 'remaining_amount', 
                        // render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                        render: function(data, type, row, meta){
                            // if (type === 'display') {
                                return '&#8369; '+number_format(data, 2, '.', ',');
                            // }
                        },orderable: false, searchable: true
                    },
                    // {data: 'progress_bar', name: 'progress_bar'},

                    // {data: 'action', name: 'action', orderable: true, searchable: true},
                ],
                responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                            tableClass: 'table'
                    } )
                }
            },
        });
        $('.filter_select').on('change', function(){
            $('#disbursement_table').DataTable().search($(this).val()).draw();
        });
    });
</script>
<script>
        // $(document).on('click', '#rffa_fund_btn_data' function(){
        //   var fund_id = $(this).data('id');
        //   var title = $(this).data('title');
        //   voucher_amount_breakdown_result_datatable(fund_id, prog_desc);
        // })

        $(document).on('click', '#voucher_fund_btn_data', function () {
            var fund_id = $(this).data('id');
            var prog_desc = $(this).data('prog_desc');

            voucher_amount_breakdown_result_datatable(fund_id, prog_desc);
        });
</script>