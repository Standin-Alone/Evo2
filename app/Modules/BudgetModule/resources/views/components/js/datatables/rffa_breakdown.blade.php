{{-- <script type="text/javascript">
    $fund_id = {{!! json_encode($fund_id) !!}}
    $program_title = {{!! json_encode($title) !!}};

    $(function() {
        var table = $('#disbursement_table').DataTable({
            select: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{route('rffa_disburse_breakdown')}}",
            columns: [
                {data: 'title', name: 'title'},
                {data: 'region', name: 'region'},
                {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                    render: function(data, type, row, meta){
                        if (type === 'display') {
                            return '&#8369; '+number_format(data, 2, '.', ',');
                        }
                    }
                },

                {data: 'disbursement_amount', name: 'disbursement_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                    render: function(data, type, row, meta){
                        if (type === 'display') {
                            // data = '<a id="rffa_fund_btn" href="'.url('/budget/fund-monitoring-and-disbursement/view-fund-source-breakdown/'.fund_id.'/'.title).'" data-toggle="modal" data-target="#view_computation">&#8369;'+number_format(data, 2, '.', ',')+'</a>';
                            data = '&#8369; '+number_format(data, 2, '.', ',');
                        }
                        return data;
                        // return '&#8369; '+number_format(data, 2, '.', ',');
                    }
                },

                {data: 'remaining_amount', name: 'remaining_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                    render: function(data, type, row, meta){
                        if (type === 'display') {
                            return '&#8369; '+number_format(data, 2, '.', ',');
                        }
                    }
                },

                // {data: 'action', name: 'action', orderable: true, searchable: true},
            ],
        });
        // seach filter select
        $('#filter_program').on('change', function(){
            table.column($(this).data('column')).search($(this).val()).draw();
        });
    });
</script> --}}