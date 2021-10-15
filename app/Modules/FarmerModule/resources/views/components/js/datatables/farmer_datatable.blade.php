<script type="text/javascript">
    $(document).ready(function(){
        var table = $('#farmer-datatable').DataTable({
            processing: true,
            serverSide: true,
            // responsive: true,
            responsivePriority: 1,
            ajax: "{{route('farmer.index')}}",
            columns: [
                {data: 'reference_no', name: 'reference_no'},
                {data: 'fullname_column', name: 'fullname_column'},
                {data: 'action', name: 'action', orderable: true, searchable: true},
            ]
        }); 
    });
</script>