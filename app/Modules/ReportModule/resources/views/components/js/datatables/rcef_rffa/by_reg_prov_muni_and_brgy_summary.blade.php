<script type="text/javascript">
    $(document).ready(function() {
        $region = {!! json_encode(session()->get('region')) !!};

        if($region == 13){
            var table = $('#co-program-focal-datatable-by-region-province-municipality-and-barangay').DataTable({
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
                                    title: 'Report: By Region, Province, Municipality and Barangay Summary',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Report: By Region, Province, Municipality and Barangay Summary',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Report: By Region, Province, Municipality and Barangay Summary',
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
                ajax: "{{route('reports.co_program_focal_summary_by_region_province_municipality_and_barangay')}}",
                columns: [
                    {data: 'region', name: 'region', orderable: false, searchable: true},
                    {data: 'province', name: 'province', orderable: false, searchable: true},
                    {data: 'municipality', name: 'municipality', orderable: false, searchable: true},
                    // {data: 'barangay', name: 'barangay', orderable: false, searchable: true},
                    {data: 'fintech_provider', name: 'fintech_provider', orderable: false, searchable: true},
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

                    total_upload_kyc = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 4 ).footer() ).html("Grand Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                    total_no_disbursed = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 5 ).footer() ).html("Grand Total no. of Disbursed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disbursed) );

                    total_amount = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 6 ).footer() ).html("Grand Total Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );
                },
            });
            
            /**
             * Mutli-Select Filter
             * Location: ReportModule::components.js.dropdown.by_reg_prov_muni_and_brgy_summary_dynamic_dropdown
            */
            multi_select_region_table_03();

            /**
             * Mutli-Select Filter
             * Location: ReportModule::components.js.dropdown.by_reg_prov_muni_and_brgy_summary_dynamic_dropdown
            */
            multi_select_province_table_03();

            /**
             * Mutli-Select Filter
             * Location: ReportModule::components.js.dropdown.by_reg_prov_muni_and_brgy_summary_dynamic_dropdown
            */
            multi_select_municipality_table_03();

            /** 
             * Clear Filter Buttons
             * Location: Location: ReportModule::components.js.dropdown.by_reg_prov_muni_and_brgy_summary_dynamic_dropdown
            */
            clear_filter_button_table_03();
        }
    });
</script>