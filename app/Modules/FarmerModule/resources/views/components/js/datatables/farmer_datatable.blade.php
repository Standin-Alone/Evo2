<script type="text/javascript">
    $(document).ready(function(){
        var table = $('#farmer-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,
            responsivePriority: 1,
            ajax: "{{route('farmer.index')}}",
            columns: [
                {data: 'reference_no', name: 'reference_no', orderable: true, searchable: true},
                {data: 'fullname_column', name: 'fullname_column', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        }); 
        table.ajax.reload();
    });
</script>