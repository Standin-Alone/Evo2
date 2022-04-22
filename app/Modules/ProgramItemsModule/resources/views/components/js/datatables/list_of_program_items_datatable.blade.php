<script type="text/javascript">
    $(function() {
        var table = $('#list-of-program-item-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            paging: true,
            ajax: "{{route('program_item_module.index')}}",
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columns: [
                {data: "item_imgs", name: "item_imgs"},
                {data: 'item_name', name: 'item_name', orderable: true, searchable: true},
                {data: 'unit_measure', name: 'unit_measure', orderable: true, searchable: true},
                // {data: 'ceiling_amount', name: 'ceiling_amount', orderable: true, searchable: true},
                {data: 'region', name: 'region', orderable: true, searchable: true},
                // {data: 'prv', name: 'prv', orderable: true, searchable: true},
                {data: 'active', name: 'active', orderable: true, searchable: true},
                {data: 'date_created', name: 'date_created', orderable: true, searchable: true},
                {data: 'action', name: 'action'},
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

    
    $(document).on("click", "#deleteItemBtn", function(e){
        e.preventDefault();

        var item_id = $(this).data('item_id');

        var route = "{{ route('program_item_module.submit_delete_program_item') }}";

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'DELETE',
                    url: route,
                    data: {
                            item_id:item_id,
                    },
                    success: function(success_response){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: success_response.message,
                            showConfirmButton: true,
                        }).then(function(){ 
                            $("#list-of-program-item-datatable").DataTable().ajax.reload();                                                          
                        }); 
                    },
                    
                });
            }
        })
    })
</script>