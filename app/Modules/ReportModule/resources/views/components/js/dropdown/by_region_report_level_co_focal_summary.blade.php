<script>
    function multi_select_region_table_04(){
        $('.table_04_filter_region').select2();

        $('#table_04_filter_region').on('change', function(){
            var reg_name = $(this).val();

            // reflect selected Region on datatable
            var types = $('select[name="filter_region_tbl_04"]').map(function() { 
                return '^' + reg_name + '\$';
            }).get().join('|');

            $('#co-program-focal-report_regional_approval').DataTable().columns(0).search(reg_name.join('|'), true, false, true).draw();
        });
    }

    function clear_filter_button_table_04(){
        $('#reset_reg_tbl_04').on('click', function(){
            $('select[name="filter_region_tbl_04"]').val(null).trigger('change');
        })
    }
</script>