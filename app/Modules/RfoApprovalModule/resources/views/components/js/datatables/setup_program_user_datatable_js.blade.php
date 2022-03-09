<script type="text/javascript">
    $(function() {
        var table = $('#setup_program_user_datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('rfo_approval_module.user_account_setup_view')}}",
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columns: [
                {data: 'company_name', name: 'company_name', orderable: true, searchable: true},
                {data: 'company_address', name: 'company_address', orderable: true, searchable: true},
                {data: 'email', name: 'email', orderable: true, searchable: true},
                {data: 'fullname_column', name: 'fullname_column', orderable: true, searchable: true},
                {data: 'contact_no', name: 'contact_no', orderable: true, searchable: true},
                {data: 'reg_name', name: 'reg_name', orderable: true, searchable: true},
                // {data: 'status', name: 'status', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false},
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