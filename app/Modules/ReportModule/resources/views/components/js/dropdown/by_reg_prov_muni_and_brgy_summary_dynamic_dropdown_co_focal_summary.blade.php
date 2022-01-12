<script>
    // Multi-Select Filter on Region
    function multi_select_region_table_03() {
        $('select[name="filter_province_tbl_03"]').prop("disabled", true);

        $('select[name="filter_municipality_tbl_03"]').prop("disabled", true);

        $('.table_03_filter_region').select2();

        $('#table_03_filter_region').on('change', function () {
            $('select[name="filter_province_tbl_03"]').prop("disabled", false);

            var reg_name = $(this).val();

            // reflect selected Region on datatable
            var types = $('select[name="filter_region_tbl_03"]').map(function () {
                return '^' + reg_name + '\$';
            }).get().join('|');

            $('#co-program-focal-datatable-by-region-province-municipality-and-barangay').DataTable().columns(0).search(reg_name.join('|'), true, false, true).draw();

            var route = "{{route('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_filter_province',['reg_name'=>':id'])}}".replace(':id', reg_name);

            if (($('select[name="filter_region_tbl_03"]').val() != '') && ($('select[name="filter_province_tbl_03"]').val() == '')) {
                console.log('region = has_value && province = no_value');

                // if Region have a value. Show filtered Province: 1. Select Region => 2. "show Province" base on selected Region 
                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province_tbl_03"]').empty();
                        $('select[name="filter_province_tbl_03"]').focus;
                        // $('select[name="filter_province_tbl_03"]').select2({ placeholder: "-- Select Province --" });  

                        // Query: get province base on selected region
                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province_tbl_03"]').append('<option value="' + province.prov_name + '">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                    }
                });
            } else if (($('select[name="filter_region_tbl_03"]').val() == '') && ($('select[name="filter_province_tbl_03"]').val() == '')) {
                console.log('region = no_value && province = no_value');

                $('select[name="filter_province_tbl_03"]').prop("disabled", true);

                $('select[name="filter_municipality_tbl_03"]').prop("disabled", true);

                // if no region selected. Province filter will be reset to default to show all Province.
                $.ajax({
                    url: "{{route('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_prov_without_reg')}}",
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province_tbl_03"]').empty();
                        $('select[name="filter_province_tbl_03"]').focus;

                        // Query: get all the province
                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province_tbl_03"]').append('<option value="' + province.prov_name + '">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                    }
                });
            }
        });
    }

    function multi_select_province_table_03(){
        $('select[name="filter_municipality_tbl_03"]').prop("disabled", true);

        $('.table_03_filter_province').select2();
        $('#table_03_filter_province').on('change', function () {

            $('select[name="filter_municipality_tbl_03"]').prop("disabled", false);

            var reg_name = $('select[name="filter_region_tbl_03"]').val();
            var prov_name = $(this).val();

            // reflect selected Province on datatable
            var types = $('select[name="filter_province_tbl_03"]').map(function () {
                return '^' + prov_name + '\$';
            }).get().join('|');

            $('#co-program-focal-datatable-by-region-province-municipality-and-barangay').DataTable().columns(1).search(prov_name.join('|'), true, false, true).draw();

            var route = "{{route('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_filter_municipality')}}" + "/" + reg_name + "/" + prov_name;

            console.log(route);

            if (($('select[name="filter_region_tbl_03"]').val() != '') && ($('select[name="filter_province_tbl_03"]').val() != '') && ($('select[name="filter_municipality_tbl_03"]').val() == '')) {
                console.log("region = has_value && province = has_value && city = no_value");
                console.log("if city has no value: select city")

                // if Region and Province have a value. Show filtered Municipality: 1. Select Region => 2. "show Province" on selected Region => 3. "show Municipality" base on selected Region and Province
                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (municipalities) {
                        $('select[name="filter_municipality_tbl_03"]').empty();
                        $('select[name="filter_municipality_tbl_03"]').focus;

                        // Query: get municipalities base on region and province
                        $.each(municipalities, function (key, city) {
                            $('select[name="filter_municipality_tbl_03"]').append('<option value="' + city.mun_name + '">' + city.mun_name + ' </option>').prop('selected', true);
                        });
                    }
                });
            } else if (($('select[name="filter_region_tbl_03"]').val() != '') && ($('select[name="filter_province_tbl_03"]').val() == '') && ($('select[name="filter_municipality_tbl_03"]').val() == '')) {
                console.log("region = has_value && province = no_value && city = no_value");
                console.log("if Province has no value: select province")
                console.log("if city has no value: select city")

                $('select[name="filter_municipality_tbl_03"]').prop("disabled", true);

                // if no Province selected. Municipality filter will be reset to default to show all Municipalities.  
                $.ajax({
                    url: "{{route('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_mun_without_prov')}}",
                    type: "GET",
                    dataType: "json",
                    success: function (municipalities) {
                        $('select[name="filter_municipality_tbl_03"]').empty();
                        $('select[name="filter_municipality_tbl_03"]').focus;

                        // Query: get all the municipalities
                        $.each(municipalities, function (key, city) {
                            $('select[name="filter_municipality_tbl_03"]').append('<option value="' + city.mun_name + '">' + city.mun_name + ' </option>').prop('selected', true);
                        });
                    }
                });
            }
        });
    }

    // Multi-Select Filter on Municipality
    function multi_select_municipality_table_03(){
        $('.table_03_filter_municipality').select2();
        $('#table_03_filter_municipality').on('change', function () {

            var mun_name = $(this).val();

            // reflect selected Municipality on datatable
            var types = $('select[name="filter_municipality_tbl_03"]').map(function () {
                return '^' + mun_name + '\$';
            }).get().join('|');

            $('#co-program-focal-datatable-by-region-province-municipality-and-barangay').DataTable().columns(2).search(mun_name.join('|'), true, false, true).draw();
        });
    }

    function clear_filter_button_table_03(){
        // Clear Region Filter
        $('#reset_region_tbl_03').on('click', function () {
            $('select[name="filter_region_tbl_03"]').val(null).trigger('change');
        })

        //  Clear Province Filter
        $('#reset_province_tbl_03').on('click', function () {
            $('select[name="filter_province_tbl_03"]').val(null).trigger('change');
        })

        //  Clear Municipality Filter
        $('#reset_municipality_tbl_03').on('click', function () {
            $('select[name="filter_municipality_tbl_03"]').val(null).trigger('change');
        })
    }
</script>