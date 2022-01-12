<script type="text/javascript">
    $(document).ready(function (){
        
        SupplierProgramList();

        function SupplierProgramList(){
            var table = $('#SupplierProgramList-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.SupplierProgramList') }}",
                columns: [
                    {data: 'srn', name: 'srn', title: 'SRN'},
                    {data: 'title', name: 'title', title: 'PROGRAM TITLE'},
                    {data: 'shortname', name: 'shortname', title: 'ALIAS'},
                    {data: 'description', name: 'description', title: 'DESCRIPTION'},
                    {data: 'duration_start_date', name: 'duration_start_date', title: 'START DATE'},
                    {data: 'duration_end_date', name: 'duration_end_date', title: 'END DATE'},
                    {data: 'status', name: 'status', title: 'STATUS'},
                ],
            });
        }
        
    });
        
</script>