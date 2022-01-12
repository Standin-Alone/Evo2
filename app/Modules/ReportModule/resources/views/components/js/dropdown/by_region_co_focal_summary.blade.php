<script>
    function multi_select_region_table_01() {
        $('.table_01_filter_region').select2();

        $('#table_01_filter_region').on('change', function () {
            var reg_name = $(this).val();

            // reflect selected Region on datatable
            var types = $('select[name="filter_region_tbl_01"]').map(function () {
                return '^' + reg_name + '\$';
            }).get().join('|');

            $('#co-program-focal-datatable-by-region').DataTable().columns(0).search(reg_name.join('|'), true, false, true).draw();
        });
    }

    function clear_filter_button_table_01() {
        $('#reset_reg_tbl_01').on('click', function () {
            $('select[name="filter_region_tbl_01"]').val(null).trigger('change');
        })
    } 
</script>