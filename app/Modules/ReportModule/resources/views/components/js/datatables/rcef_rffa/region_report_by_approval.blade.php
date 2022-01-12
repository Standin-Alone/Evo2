<script type="text/javascript">
    $(function() {
        $region = {!! json_encode(session()->get('region')) !!};

        var table = $('#co-program-focal-report_regional_approval').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            "paging": true,
            dom: 'lBfrtip',
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "buttons": [
                        {
                            extend: 'collection',
                            text: 'Export',
                            buttons: [
                                {
                                    text: '<i class="fas fa-print"></i> PRINT',
                                    title: 'Report: Region report by approval',
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
                                    title: 'Report: Region report by approval',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Report: Region report by approval',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Report: Region report by approval',
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
            ajax: "{{route('reports.by_regional_approval')}}",
            columns: [
                    {data: 'region', name: 'region', orderable: false, searchable: true},
                    {data: 'fintech_provider', name: 'fintech_provider', orderable: false, searchable: true},
                    {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                    {data: 'generate_beneficiaries' ,name: 'generate_beneficiaries', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                    {data: 'approve_by_budget', name: 'approve_by_budget', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                    {data: 'approve_by_disburse', name: 'approve_by_disburse', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                ],
            "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
        
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\₱,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    total_upload_kyc = api
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 2 ).footer() ).html("Grand Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                    generate_beneficiaries = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 3 ).footer() ).html("Grand Total no. of Endorsed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(generate_beneficiaries) );

                    approve_by_budget = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 4 ).footer() ).html("Grand Total no. of Reviewed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(approve_by_budget) );
                            
                    approve_by_disburse = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 5 ).footer() ).html("Grand Total no. of Approved:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(approve_by_disburse) );
            },
        });

        /**
         * Mutli-Select Filter
         * Location: ReportModule::components.js.dropdown.by_region_co_focal_summary
        */
        multi_select_region_table_04();

        /** 
         * Clear Filter Buttons
         * Location: Location: ReportModule::components.js.dropdown.by_region_co_focal_summary
        */
        clear_filter_button_table_04();
    });
</script>