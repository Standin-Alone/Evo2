{{-- When region is selected filter province --}}
<script>
    $(document).ready(function () {
        $('#filter_region').on('change', function () {
            var reg_name = $(this).val();
            var route = "{{route('report.not_paid.get_filter_province',['reg_name'=>':id'])}}".replace(':id', reg_name);

            if((reg_name !== '') && $('select[name="filter_province"]').val() === ''){
                console.log('may region pero wala province');
                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        $('select[name="filter_province"]').empty();
                        $('select[name="filter_province"]').focus;
                        $('select[name="filter_province"]').append('<option value=""> -- Select Province -- </option>');
                        $.each(provinces, function (key, province) {
                            $('select[name="filter_province"]').append('<option value="' + province.prov_name +'">' + province.prov_name + ' </option>').prop('selected', true);
                        });
                        $
                    }
                });
            }
        });

        $('#filter_province').on('change', function () {
            var prov_name = $(this).val();
            var route = "{{route('report.not_paid.get_filter_region',['prov_name'=>':id'])}}".replace(':id', prov_name);
            if (prov_name !== '') {
                console.log('province value meron region value meron');
                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function (regions) {
                        $('select[name="filter_region"]').empty();
                        $('select[name="filter_region"]').focus;
                        $.each(regions, function (key, region) {
                            $('select[name="filter_region"]').append('<option value="' + region.reg_name + '">' + region.reg_name + ' </option>').prop('selected', true);
                        });
                        $('select[name="filter_region"]').append('<option value=""> -- All Region -- </option>');

                        console.log($('select[name="filter_region"]').val());
                        
                        // When user click ALL Region -> it will trigger on change
                        $('#filter_region').on('change', function(){
                            if(($('select[name="filter_region"]').val() === '' && $('select[name="filter_province"]').val() !== '')){
                                // Region Filter and Province Filter will be reset to "ALL"
                                $.ajax({
                                    url: "{{route('report.not_paid.get_reg_without_prov')}}",
                                    type: "GET",
                                    dataType: "json",
                                    success: function (regions) {
                                        $('select[name="filter_region"]').empty();
                                        $('select[name="filter_region"]').focus;
                                        $('select[name="filter_region"]').append('<option value=""> -- All Region -- </option>');
                                        $.each(regions, function (key, region) {
                                                $('select[name="filter_region"]').append('<option value="' + region.reg_name + '">' + region.reg_name + ' </option>').prop('selected', true);
                                        });
                                        $.ajax({
                                            url: "{{route('report.not_paid.get_prov_without_reg')}}",
                                            type: "GET",
                                            dataType: "json",
                                            success: function (provinces) {
                                                $('select[name="filter_province"]').empty();
                                                $('select[name="filter_province"]').focus;
                                                $('select[name="filter_province"]').append('<option value=""> -- All Province -- </option>');
                                                $.each(provinces, function (key, province) {
                                                    $('select[name="filter_province"]').append('<option value="' + province.prov_name +'">' + province.prov_name + ' </option>').prop('selected', true);
                                                });
                                                 $('select[name="filter_province"]').val('');
                                            }
                                        });
                                        $('#not-yet-claimed-datatable').DataTable().columns('').search('').draw();
                                    }
                                });
                            }
                            else if(($('select[name="filter_region"]').val() === '') && ($('select[name="filter_province"]').val() === '')){
                                console.log('region value null and province value null');
                                // Region Filter and Province Filter will be reset to "ALL"
                                $.ajax({
                                url: "{{route('report.not_paid.get_reg_without_prov')}}",
                                type: "GET",
                                dataType: "json",
                                    success: function (regions) {
                                        $('select[name="filter_region"]').empty();
                                        $('select[name="filter_region"]').focus;
                                        $('select[name="filter_region"]').append('<option value=""> -- All Region -- </option>');
                                        $.each(regions, function (key, region) {
                                            $('select[name="filter_region"]').append('<option value="' + region.reg_name + '">' + region.reg_name + ' </option>').prop('selected', true);
                                        });
                                        $.ajax({
                                            url: "{{route('report.not_paid.get_prov_without_reg')}}",
                                            type: "GET",
                                            dataType: "json",
                                            success: function (provinces) {
                                                $('select[name="filter_province"]').empty();
                                                $('select[name="filter_province"]').focus;
                                                $('select[name="filter_province"]').append('<option value=""> -- All Province -- </option>');
                                                $.each(provinces, function (key, province) {
                                                    $('select[name="filter_province"]').append('<option value="' + province.prov_name +'">' + province.prov_name + ' </option>').prop('selected', true);
                                                });
                                                $('select[name="filter_region"]').val('');
                                                $('select[name="filter_province"]').val('');
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    });
</script>