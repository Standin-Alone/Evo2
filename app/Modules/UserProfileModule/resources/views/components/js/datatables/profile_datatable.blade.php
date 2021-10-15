<script>
    $(function() {
        var table = $('#profile-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            paginate: false,
            info: false,
            searching: false,
                ajax: {
                    url: "{{route('user.profile')}}"
                },
                columns: [
                    {data: 'title', name: 'title'},
                    // {data: 'description', name: 'description'},
                    {data: 'email', name: 'email'},
                    {data: 'contact_no', name: 'contact_no'},
                    {data: 'role', name: 'role'},
                ],
        });
    });
</script>