<script type="text/javascript">
    $(document).ready(function(){
        var table = $('#farmer-rffa-datatable').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            responsive: true,
            paging: true,
            ajax: "{{route('farmer.index.rffa')}}",
            columns: [
                {data: 'rsbsa_no', name: 'rsbsa_no', orderable: true, searchable: true},
                {data: 'fullname_column', name: 'fullname_column', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        table.ajax.reload(); 
    });
</script>