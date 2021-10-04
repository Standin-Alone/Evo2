<script type="text/javascript">
    function interv_data(uuid){
        var route = "{{route('list-of-users.index')}}"+"/"+uuid;
        console.log(route);
        var table = $('#interv-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
                ajax: {
                    url: route,
                },
                columns: [
                    {data: 'description', name: 'description'},
                    {data: 'email', name: 'email'},
                    {data: 'contact_no', name: 'contact_no'},
                    {data: 'role', name: 'role'},
                ],
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                        tableClass: 'table'
                    } )
                }
            }
        });
        table.ajax.reload();
    }
</script>