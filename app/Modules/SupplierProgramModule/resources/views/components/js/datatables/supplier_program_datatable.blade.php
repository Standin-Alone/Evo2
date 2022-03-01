<script type="text/javascript">
    $(function() {
        var table = $('#supplier-program-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('supplier_program_module.list_of_supplier_programs')}}",
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columns: [
                {data: 'supplier_name', name: 'supplier_name', orderable: true, searchable: true},
                {data: 'title', name: 'title', orderable: true, searchable: true},
                {data: 'item_name', name: 'item_name', orderable: true, searchable: true},
                {data: 'unit_measure', name: 'unit_measure', orderable: true, searchable: true},
                {data: 'ceiling_amount', name: 'ceiling_amount', orderable: true, searchable: true},
                {data: 'date_created', name: 'date_created', orderable: true},
                {data: 'active', name: 'active', orderable: false, searchable: true},
            ],
            order: [[5, 'desc']],
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