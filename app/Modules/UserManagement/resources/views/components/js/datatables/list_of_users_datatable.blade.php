<script type="text/javascript">
    $(function() {
        var table = $('#user-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            paging: true,
            ajax: "{{route('list-of-users.index')}}",
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columns: [
                {data: 'fullname_column', name: 'fullname_column'},
                {data: 'agency_shortname', name: 'agency_shortname'},
                {data: 'region', name: 'region'},
                // {data: 'prov', name: 'prov'},
                {data: 'action', name: 'action', orderable: true, searchable: true},
            ]
        });
        // seach filter select
        $('.filter-select').on('change', function(){
            table.column($(this).data('column')).search($(this).val()).draw();
        });
    });

    $(document).on('click', '#btn_data', function () {
        var uuid = $(this).data('id');
        interv_data(uuid);
    });
</script>