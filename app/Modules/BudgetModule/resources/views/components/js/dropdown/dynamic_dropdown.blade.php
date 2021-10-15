<script>
    $(document).ready(function () {
        $('#select_province').append('<option value="">-- Select Province --</option>').prop('disabled', true);
        $('#select_region').on('change', function () {
            var reg_code = $(this).val();
            if (reg_code) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('fund_region',['reg_code'=>':id'])}}".replace(':id', reg_code),
                    type: "GET",
                    dataType: "json",
                    success: function (provinces) {
                        // console.log(provinces);
                        if (provinces) {
                            $('select[name="select_province"]').empty();
                            $('select[name="select_province"]').focus;
                            $('select[name="select_province"]').append('<option value="">-- Select Province --</option>');
                            $.each(provinces, function (key, province) {
                                $('select[name="select_province"]').append('<option value="' + province.prov_code + '">' + province.prov_name + ' </option>').prop('disabled', false).prop('selected', true);
                            }); 
                        } else {
                            $('#select_province').empty();
                        }
                    }
                });
            } else {
                $('#select_province').empty();
            }
        });
    }); 
</script>