<script type="text/javascript">
    $(function() {
        $region = {!! json_encode(session()->get('region')) !!};

        if($region != 13){
            var table = $('#rfo-program-focal-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                // "scrollY": "100%",
                // "scrollCollapse": true,
                "paging": true,
                dom: 'lBfrtip',
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "buttons": [
                        {
                            extend: 'collection',
                            text: 'Export',
                            buttons: [
                                {
                                    text: '<i class="fas fa-print"></i> PRINT',
                                    title: 'Report: By Region, Province, Municipality and Barangay Summary',
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
                                    title: 'By Region, Province, Municipality and Barangay Summary',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'By Region, Province, Municipality and Barangay Summary',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'By Region, Province, Municipality and Barangay Summary',
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
                ajax: "{{route('reports.rcef_rffa')}}",
                columns: [
                    {data: 'province', name: 'province', orderable: false, searchable: true},
                    {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                    {data: 'no_of_disbursed', name: 'no_of_disbursed', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                    {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display, orderable: false, searchable: true},
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
        
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\₱,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total amount column[7]: get it's total sum
                    total_amount = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 3 ).footer() ).html("Total amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );                           
                }
            });
        }
        else{
            var table = $('#co-program-focal-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            "paging": true,
            dom: 'lBfrtip',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "buttons": [
                        {
                            extend: 'collection',
                            text: 'Export',
                            buttons: [
                                {
                                    text: '<i class="fas fa-print"></i> PRINT',
                                    title: 'Report: By Region, Province, Municipality and Barangay Summary',
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
                                    title: 'By Region, Province, Municipality and Barangay Summary',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'By Region, Province, Municipality and Barangay Summary',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'By Region, Province, Municipality and Barangay Summary',
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
            ajax: "{{route('reports.rcef_rffa')}}",
            columns: [
                {data: 'region', name: 'region', orderable: false, searchable: true},
                {data: 'province', name: 'province' , orderable: false, searchable: true},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                {data: 'no_of_disbursed', name: 'no_of_disbursed', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display, orderable: false, searchable: true},
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\₱,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // quantity column[5]: get it's total sum
                total_upload_kyc = api
                        .column( 2 )
                        .data()
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;}, 0 );
                        $( api.column( 2 ).footer() ).html("Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                // amount column[6]: get it's total sum
                total_no_disbursed = api
                        .column( 3 )
                        .data()
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;}, 0 );
                        $( api.column( 3 ).footer() ).html("Total no. of Disbursed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disbursed) );

                // Total amount column[7]: get it's total sum
                total_amount = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                                    return (a)*1 + (b)*1;}, 0 );
                        $( api.column( 4 ).footer() ).html("Total amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );
            },
        });
        }
    });
</script>