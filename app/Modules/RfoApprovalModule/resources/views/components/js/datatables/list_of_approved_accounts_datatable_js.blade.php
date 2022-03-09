<script type="text/javascript">
    $(function() {
        var table = $('#list_of_approved_accounts_datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('rfo_approval_module.view_approved_checklists')}}",
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columns: [
                {data: 'company_name', name: 'company_name', orderable: true, searchable: true},
                {data: 'company_address', name: 'company_address', orderable: true, searchable: true},
                {data: 'title', name: 'title', orderable: true, searchable: true},
                {data: 'email', name: 'email', orderable: true, searchable: true},
                {data: 'fullname_column', name: 'fullname_column', orderable: true, searchable: true},
                {data: 'contact_no', name: 'contact_no', orderable: true, searchable: true},
                {data: 'reg_name', name: 'reg_name', orderable: true, searchable: true},
                {data: 'approved_by_fullname', name: 'approved_by_fullname', orderable: true, searchable: true},
                {data: 'approval_status', name: 'approval_status', orderable: true, searchable: true},
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