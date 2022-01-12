<script>
/*
 * Note: 1. processing fee: If the amount are 5070 True 70.00(fee) False 0.00
 *       2. data: batch date
 *       3. status: Output should be static = "Claimed"
 *       4. total aount: Output should be static = "5000.00"
 */
    $(document).ready(function () {       
        var route = $(location).attr('href');

        var table = $('#farmer-rrfa-details-datatable').DataTable({
            destroy:true,
            processing: true,
            serverSide: true,
            responsive: true,
            paging: true,
            ajax: { url: route},
            dom: 'lBfrtip',
            buttons: [
                { extend: 'print', footer: true }
            ],
            columns: [
                {data: 'fullname_column', name: 'fullname_column', orderable: false, searchable: true},
                {data: 'title', name: 'title', orderable: false, searchable: true}, // sample out: static claimed
                {data: 'processing_fee', name: 'processing_fee', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, orderable: false, searchable: true},
                {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, orderable: false, searchable: true},
                {data: 'date', name: 'date', orderable: false, searchable: true}, 
                {data: 'status', name: 'status', orderable: false, searchable: true}
            ],
        });
    });
</script>