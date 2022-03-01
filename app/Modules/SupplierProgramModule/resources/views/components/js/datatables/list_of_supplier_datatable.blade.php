<script type="text/javascript">
    $(function() {
        var table = $('#list-of-supplier-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('supplier_program_module.list_of_suppliers')}}",
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columns: [
                {data: 'supplier_name', name: 'supplier_name', orderable: true, searchable: true},
                {data: 'address', name: 'address', orderable: true, searchable: true},
                {data: 'supplier_group', name: 'supplier_group', orderable: true, searchable: true},
                {data: 'bank_account_no', name: 'bank_account_no', orderable: true, searchable: true},
                {data: 'email', name: 'email', orderable: true, searchable: true},
                {data: 'contact', name: 'contact', orderable: true, searchable: true},
                {data: 'action', name: 'action'},
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