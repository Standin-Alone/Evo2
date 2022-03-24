<script type="text/javascript">
    function otp_status(uuid){
        var route = "{{route('list-of-users.index')}}"+"/"+uuid+"/user-status";

        var table = $('#user-otp-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            lengthChange: false,
            searching: false,
                ajax: {
                    url: route,
                },
                columns: [
                    {data: 'otp', name: 'otp'},
                    {data: 'status', name: 'status'},
                    {data: 'login_status', name: 'login_status'},
                    {data: 'date_created', name: 'date_created'}
                ],
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                        tableClass: 'table'
                    } )
                }
            }
        });
    }
</script>