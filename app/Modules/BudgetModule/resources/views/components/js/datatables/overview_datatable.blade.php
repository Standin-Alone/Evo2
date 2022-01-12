<script type="text/javascript">
    $(function() {
        var table = $('#overview_table').DataTable({
            select: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{route('fund_overview')}}",
            columns: [
                {data: 'title', name: 'title', orderable: false, searchable: true},
                // {data: 'description', name: 'description'},
                {data: 'uacs', name: 'uacs', orderable: false, searchable: true},
                {data: 'gfi', name: 'gfi', orderable: false, searchable: true},
                {data: 'region', name: 'region', orderable: false, searchable: true},
                {data: 'particulars', name: 'particulars', orderable: false, searchable: true},
                {data: 'target_of_benefeciaries', name: 'target_of_benefeciaries', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,  orderable: false, searchable: true, 
                    render: function(data, type, row, meta){
                        if (type === 'display') {
                            return '&#8369; '+number_format(data, 2, '.', ',');
                        }
                    }},
                {data: 'action', name: 'action', orderable: false, searchable: true},
            ],
        });
    });


    $(document).on('click', '#btn_overview_edit', function () {
        var fund_id = $(this).data('id');
        var uacs = $(this).data('uacs');
        var gfi = $(this).data('gfi');
        var no_of_farmers = $(this).data('no_of_farmers');
        var region = $(this).data('region');
        var region_name = $(this).data('region_name');
        var amount = $(this).data('amount');
        var particulars = $(this).data('particulars');
        
        $(".modal-body #fund_id").val( fund_id );
        $(".modal-body #uacs").val( uacs );
        $(".modal-body #select_gfi").val( gfi );
        $(".modal-body #no_of_farmers").val( no_of_farmers );
        $("#select_region_hidden").val(region);
        $("#select_region :selected").html(region_name);
        $(".modal-body #amount").val( amount );
        $(".modal-body #particulars").val( particulars );
    });

    $(document).on('change', 'select#select_region', function(){
        var selected_region = $(this).val();
        $("#select_region_hidden").val(selected_region);
    });
</script>