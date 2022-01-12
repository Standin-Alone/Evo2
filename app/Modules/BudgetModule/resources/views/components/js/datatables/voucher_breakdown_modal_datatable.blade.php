<script type="text/javascript">
    function voucher_amount_breakdown_result_datatable(fund_id, prog_desc){
        var route = "{{route('voucher_fund_disburse_breakdown')}}"+"/"+fund_id+"/"+prog_desc;
        console.log(route);
        var table = $('#voucher_disbursement_breakdown_link').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,      
            "scrollX": true, 
            dom: 'lBfrtip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "buttons": [
                        {
                            extend: 'collection',
                            text: 'Export',
                            buttons: [
                                {
                                    text: '<i class="fas fa-print"></i> PRINT',
                                    title: 'Budget: Disburse Breakdown',
                                    extend: 'print',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    },
                                    customize: function ( doc ) {
                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                        $(doc.document.body)
                                            .prepend(
                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                        );
                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                    },
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                    title: 'Budget: Disburse Breakdown',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Budget: Disburse Breakdown',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Budget: Disburse Breakdown',
                                    extend: 'pdfHtml5',
                                    footer: true,
                                    message: '',
                                    exportOptions: {
                                        columns: ':visible'
                                    },
                                }, 
                            ]
                            }
            ],   
            ajax: {
                url: route,
            },
            columns: [
                {data: 'reference_no', name: 'reference_no',title: "REFENRENCE_NO"},
                {data: 'fullname_column', name: 'fullname_column',title: "FULLNAME"},
                {data: 'title', name: 'title',title: "PROGRAM"},
                {data: 'item_name', name: 'item_name',title: "ITEM NAME"},
                {data: 'quantity', name: 'quantity',title: "QUANTITY"},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,title: "AMOUNT"},
                {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display,title: "TOTAL AMOUNT"}
            ],
            // responsive: {
            //     details: {
            //         renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
            //                 tableClass: 'table'
            //         } )
            //     }
            // },
        });
    }
</script>