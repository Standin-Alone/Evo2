<script>
    // CO Focal Summary Region
    function multi_select_region_table_05() {
        $('select[name="filter_province_tbl_05"]').prop("disabled", true);

        $('.table_05_filter_region').select2();

        $('#table_05_filter_region').on('change', function () {
            $('select[name="filter_province_tbl_05"]').prop("disabled", false);

            var reg_name = $(this).val();

            // reflect selected Region on datatable
            var types = $('select[name="filter_region_tbl_05"]').map(function () {
                return '^' + reg_name + '\$';
            }).get().join('|');

            $('#co-program-focal-report_approval').DataTable().columns(0).search(reg_name.join('|'), true, false, true).draw();

            var route = "{{route('reports.by_approval.get_filter_province',['reg_name'=>':id'])}}".replace(':id', reg_name);

            if (($('select[name="filter_region_tbl_05"]').val() != '') && ($('select[name="filter_province_tbl_05"]').val() == '')) {
                console.log('region = has_value && province = no_value');

                // Show Province when Region are selected
                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province_tbl_05"]').empty();
                        $('select[name="filter_province_tbl_05"]').focus;
                        // $('select[name="filter_province_tbl_05"]').select2({ placeholder: "-- Select Province --" });  

                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province_tbl_05"]').append('<option value="' + province.prov_name + '">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                    }
                });
            } else if (($('select[name="filter_region_tbl_05"]').val() == '') && ($('select[name="filter_province_tbl_05"]').val() == '')) {
                $('select[name="filter_province_tbl_05"]').prop("disabled", true);

                console.log('region = no_value && province = no_value');

                // if no region selected. Province filter will be reset to default Province List. 
                $.ajax({
                    url: "{{route('reports.by_approval.get_prov_without_reg')}}",
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province_tbl_05"]').empty();
                        $('select[name="filter_province_tbl_05"]').focus;
                        // get all the province
                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province_tbl_05"]').append('<option value="' + province.prov_name + '">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                        // reset the value to empty
                        $('select[name="filter_province_tbl_05"]').val('');
                    }
                });
            }
        });
    }

    // CO Focal Summary Province
    function multi_select_province_table_05() {
        $('.table_05_filter_province').select2();
        $('#table_05_filter_province').on('change', function () {

            var prov_name = $(this).val();

            // reflect selected Province on datatable
            var types = $('select[name="filter_province_tbl_05"]').map(function () {
                return '^' + prov_name + '\$';
            }).get().join('|');

            $('#co-program-focal-report_approval').DataTable().columns(1).search(prov_name.join('|'), true, false, true).draw();
        });
    }

    // RFO Focal Summary Province 
    function rfo_multi_select_province_table_05(){
        $('.table_05_filter_province').select2();
        $('#table_05_filter_province').on('change', function () {

            var prov_name = $(this).val();

            // reflect selected Province on datatable
            var types = $('select[name="filter_province_tbl_05"]').map(function () {
                return '^' + prov_name + '\$';
            }).get().join('|');

            $('#rfo-program-focal-report_approval').DataTable().columns(0).search(prov_name.join('|'), true, false, true).draw();
        });
    }

    function clear_filter_button_table_05() {
        $('#reset_reg_tbl_05').on('click', function(){
            $('select[name="filter_region_tbl_05"]').val(null).trigger('change');
        })

        $('#reset_prov_tbl_05').on('click', function(){
            $('select[name="filter_province_tbl_05"]').val(null).trigger('change');
        })
    } 
</script>