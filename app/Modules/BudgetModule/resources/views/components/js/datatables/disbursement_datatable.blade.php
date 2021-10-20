<script type="text/javascript">
    $(function() {

        var table = $('#disbursement_table').DataTable({
                select: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{route('fund_moni_and_disb')}}",
                columns: [
                    {data: 'title', name: 'title'},
                    // {data: 'description', name: 'description'},
                    // {data: 'particulars', name: 'particulars'},
                    {data: 'region', name: 'region'},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                        render: function(data, type, row, meta){
                            if (type === 'display') {
                                return '&#8369; '+number_format(data, 2, '.', ',');
                            }
                        }
                    },

                    {data: 'disbursement_amount', name: 'disbursement_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,
                        // render: function(data, type, row, meta){
                        //     if (type === 'display') {
                        //         return '&#8369; '+number_format(data, 2, '.', ',');
                        //     }
                        // }
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

    function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $(document).on('click', '#rffa_fund_btn_data', function () {
        var fund_id = $(this).data('id');
        var title = $(this).data('title');

        amount_breakdown_result_datatable(fund_id, title);
    });
</script>