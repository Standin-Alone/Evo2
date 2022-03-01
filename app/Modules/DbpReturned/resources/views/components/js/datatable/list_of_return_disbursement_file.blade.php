<script type="text/javascript">
    $(function() {
        var table = $('#list_of_return_disbursement_files_datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('dbp-returned-module.index')}}",
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columns: [
                {data: 'rsbsa_no', name: 'rsbsa_no', orderable: true, searchable: true},
                {data: 'file_name', name: 'file_name', orderable: true, searchable: true},
                {data: 'account_number', name: 'account_number', orderable: true, searchable: true},
                {data: 'first_name', name: 'first_name', orderable: true, searchable: true},
                {data: 'middle_name', name: 'middle_name', orderable: true, searchable: true},
                {data: 'last_name', name: 'last_name', orderable: true, searchable: true},
                {data: 'province', name: 'province', orderable: true, searchable: true},
                {data: 'city_municipality', name: 'city_municipality', orderable: true, searchable: true},
                {data: 'dbp_status', name: 'dbp_status', orderable: true, searchable: true},
            ],
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                        tableClass: 'table'
                    } )
                }
            }
        });
    });
</script>