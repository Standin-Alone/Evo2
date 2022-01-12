<script>
    // CO Focal Summary Region
    function multi_select_region_table_02() {
        $('select[name="filter_province_tbl_02"]').prop("disabled", true);

        $('.table_02_filter_region').select2();

        $('#table_02_filter_region').on('change', function () {
            $('select[name="filter_province_tbl_02"]').prop("disabled", false);
            
            var reg_name = $(this).val();

            // reflect selected Region on datatable
            var types = $('select[name="filter_region_tbl_02"]').map(function () {
                return '^' + reg_name + '\$';
            }).get().join('|');

            $('#co-program-focal-datatable').DataTable().columns(0).search(reg_name.join('|'), true, false, true).draw();

            var route = "{{route('reports.by_region_co_program_focal.get_filter_province',['reg_name'=>':id'])}}".replace(':id', reg_name);

            if (($('select[name="filter_region_tbl_02"]').val() != '') && ($('select[name="filter_province_tbl_02"]').val() == '')) {
                console.log('region = has_value && province = no_value');

                // Show Province when Region are selected
                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province_tbl_02"]').empty();
                        $('select[name="filter_province_tbl_02"]').focus;
                        // $('select[name="filter_province_tbl_02"]').select2({ placeholder: "-- Select Province --" });  

                        //
                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province_tbl_02"]').append('<option value="' + province.prov_name + '">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                    }
                });
            } else if (($('select[name="filter_region_tbl_02"]').val() == '') && ($('select[name="filter_province_tbl_02"]').val() == '')) {
                $('select[name="filter_province_tbl_02"]').prop("disabled", true);
                console.log('region = no_value && province = no_value');

                // if no region selected. Province filter will be reset to default Province List. 
                $.ajax({
                    url: "{{route('report.by_region_co_program_focal.get_prov_without_reg')}}",
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province_tbl_02"]').empty();
                        $('select[name="filter_province_tbl_02"]').focus;
                        // get all the province
                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province_tbl_02"]').append('<option value="' + province.prov_name + '">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                        // reset the value to empty
                        $('select[name="filter_province_tbl_02"]').val('');
                    }
                });
            }
        });
    }

    // CO Focal Summary Province
    function multi_select_province_table_02() {
        $('.table_02_filter_province').select2();
        $('#table_02_filter_province').on('change', function () {

            var prov_name = $(this).val();

            // reflect selected Province on datatable
            var types = $('select[name="filter_province_tbl_02"]').map(function () {
                return '^' + prov_name + '\$';
            }).get().join('|');

            $('#co-program-focal-datatable').DataTable().columns(1).search(prov_name.join('|'), true, false, true).draw();
        });
    };

    // RFO Focal Summary Province
    function rfo_multi_select_province_table_02(){
        $('.table_02_filter_province').select2();
        $('#table_02_filter_province').on('change', function () {

            var prov_name = $(this).val();

            // reflect selected Province on datatable
            var types = $('select[name="filter_province_tbl_02"]').map(function () {
                return '^' + prov_name + '\$';
            }).get().join('|');

            $('#rfo-program-focal-datatable').DataTable().columns(0).search(prov_name.join('|'), true, false, true).draw();
        });
    }

    function clear_filter_button_table_02() {
        $('#reset_reg_tbl_02').on('click', function () {
            $('select[name="filter_region_tbl_02"]').val(null).trigger('change');
        })

        $('#reset_prov_tbl_02').on('click', function () {
            $('select[name="filter_province_tbl_02"]').val(null).trigger('change');
        })
    }; 
</script>