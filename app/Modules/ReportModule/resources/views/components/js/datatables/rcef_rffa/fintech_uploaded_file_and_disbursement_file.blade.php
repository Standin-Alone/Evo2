<script type="text/javascript">
    $(function() {
        $region = {!! json_encode(session()->get('region')) !!};

        if($region == 13){
            // Deafult selected Filter by Date
            $('#fintech-files').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                responsive: true,
                paging: true,

                ajax:{
                    url: "{{route('reports.rffa_fintech_files_this_month_pt02')}}",
                },
                columns: [
                            {data: 'fintech', name: 'fintech', orderable: false, searchable: true},
                            {data: 'no_of_uploaded_file', name: 'no_of_uploaded_file', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                            {data: 'no_of_disbursement_file', name: 'no_of_disbursement_file', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
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

                            total_no_upload_file = api
                                .column( 1 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 1 ).footer() ).html("Grand Total No. of Upload File:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_upload_file) );

                            total_no_disburesed_file = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 2 ).footer() ).html("Grand Total No of Disbursement File:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disburesed_file) );
                        }

            });

            $('#fintech_file_daterangepicker').on("apply.daterangepicker", function(ev, picker) {
                // console.log(ev);
                // console.log(picker);
                
                var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                var fin_end_date = picker.endDate.format('YYYY-MM-DD');
                var route = "";
                var col = "";
                var col_name = "";
                var header = "";
                var chosen_label = "";
                var print_text_title = "";

                if(picker.chosenLabel == "Custom Range"){
                    // $('table#fintech-files th').eq(3).append(fin_start_date+' - '+fin_end_date).empty();

                    // $('table#fintech-files th').eq(3).append(fin_start_date+' - '+fin_end_date);

                    // $('#fintech-files').DataTable().destroy();

                    fetch_start_date_and_end_date_on_fintech_files(fin_start_date, fin_end_date);
                }else{
                    if(picker.chosenLabel == "Today"){
                        route= "{{route('reports.rffa_fintech_files_today')}}"

                        var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                        
                        var fin_end_date = picker.endDate.format('YYYY-MM-DD');

                        console.log("Today");
                        console.log("Start:" + fin_start_date + "-" + "End:" + fin_end_date);

                        col = "today";

                        col_name = "today";

                        print_text_title = "Today";
                    }
                    if(picker.chosenLabel == "Yesterday"){
                        route= "{{route('reports.rffa_fintech_files_yesterday')}}"

                        var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                        
                        var fin_end_date = picker.endDate.format('YYYY-MM-DD');

                        console.log("Yesterday");
                        console.log("Start:" + fin_start_date + "-" + "End:" + fin_end_date);

                        col = "yesterday";

                        col_name = "yesterday";

                        print_text_title = "Yesterday";
                    }
                    if(picker.chosenLabel == "Last 7 Days"){
                        route= "{{route('reports.rffa_fintech_files_last_7_days')}}"

                        var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                        
                        var fin_end_date = picker.endDate.format('YYYY-MM-DD');

                        console.log("Last 7 Days");
                        console.log("Start:" + fin_start_date + "-" + "End:" + fin_end_date);

                        col = "last_7_days";

                        col_name = "last_7_days";

                        print_text_title = "Last 7 Days";
                    }
                    if(picker.chosenLabel == "Last 30 Days"){
                        route= "{{route('reports.rffa_fintech_files_last_30_days')}}"

                        var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                        
                        var fin_end_date = picker.endDate.format('YYYY-MM-DD');

                        console.log("Last 30 Days");
                        console.log("Start:" + fin_start_date + "-" + "End:" + fin_end_date);

                        col = "last_30_days";

                        col_name = "last_30_days";

                        print_text_title = "Last 30 Days";
                    }
                    if(picker.chosenLabel == "Last Month"){
                        route= "{{route('reports.rffa_fintech_files_last_month')}}"

                        var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                        
                        var fin_end_date = picker.endDate.format('YYYY-MM-DD');

                        console.log("Last Month");
                        console.log("Start:" + fin_start_date + "-" + "End:" + fin_end_date);

                        col = "last_month";

                        col_name = "last_month";

                        print_text_title = "Last Month";
                    }
                    if(picker.chosenLabel == "This Month"){
                        route= "{{route('reports.rffa_fintech_files_this_month')}}"

                        var fin_start_date = picker.startDate.format('YYYY-MM-DD');
                        
                        var fin_end_date = picker.endDate.format('YYYY-MM-DD');

                        console.log("This Month");
                        console.log("Start:" + fin_start_date + "-" + "End:" + fin_end_date);

                        col = "this_month";

                        col_name = "this_month";

                        print_text_title = "This Month";
                    }

                    $('#fintech-files').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: false,
                        responsive: true,
                        paging: true,
                        dom: 'lBfrtip',
                        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                        "buttons": [
                                    {
                                        extend: 'collection',
                                        text: 'Export',
                                        buttons: [
                                            {
                                                text: '<i class="fas fa-print"></i> PRINT',
                                                title: 'Report: By '+ print_text_title,
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
                                                title: 'Report: By '+ print_text_title,
                                                extend: 'excelHtml5',
                                                footer: true,
                                                exportOptions: {
                                                    columns: ':visible'
                                                }
                                            }, 
                                            {
                                                text: '<i class="far fa-file-excel"></i> CSV',
                                                title: 'Report: By '+ print_text_title,
                                                extend: 'csvHtml5',
                                                footer: true,
                                                fieldSeparator: ';',
                                                exportOptions: {
                                                    columns: ':visible'
                                                }
                                            }, 
                                            {
                                                text: '<i class="far fa-file-pdf"></i> PDF',
                                                title: 'Report: By '+ print_text_title,
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
                                data:{fin_start_date: fin_start_date, fin_end_date: fin_end_date},
                        },
                        columns: [
                            {data: 'fintech', name: 'fintech', orderable: false, searchable: true},
                            {data: 'no_of_uploaded_file', name: 'no_of_uploaded_file', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                            {data: 'no_of_disbursement_file', name: 'no_of_disbursement_file', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                            // {data: col, name: col_name, orderable: false, searchable: true},
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

                            total_no_upload_file = api
                                .column( 1 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 1 ).footer() ).html("Grand Total No. of Upload File:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_upload_file) );

                            total_no_disburesed_file = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 2 ).footer() ).html("Grand Total No of Disbursement File:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disburesed_file) );
                        }
                    });
                }
            });

            // custom range
            function fetch_start_date_and_end_date_on_fintech_files(fin_start_date, fin_end_date){
                $('#fintech-files').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    paging: true,
                    dom: 'lBfrtip',
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    "buttons": [
                                {
                                    extend: 'collection',
                                    text: 'Export',
                                    buttons: [
                                        {
                                            text: '<i class="fas fa-print"></i> PRINT',
                                            title: 'Report: By '+ fin_start_date +' to '+ fin_end_date,
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
                                            title: 'Report: By '+ fin_start_date +' to '+ fin_end_date,
                                            extend: 'excelHtml5',
                                            footer: true,
                                            exportOptions: {
                                                columns: ':visible'
                                            }
                                        }, 
                                        {
                                            text: '<i class="far fa-file-excel"></i> CSV',
                                            title: 'Report: By '+ fin_start_date +' to '+ fin_end_date,
                                            extend: 'csvHtml5',
                                            footer: true,
                                            fieldSeparator: ';',
                                            exportOptions: {
                                                columns: ':visible'
                                            }
                                        }, 
                                        {
                                            text: '<i class="far fa-file-pdf"></i> PDF',
                                            title: 'Report: By '+ fin_start_date +' to '+ fin_end_date,
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
                            url: "{{route('reports.rffa_fintech_files')}}",
                            data:{fin_start_date: fin_start_date, fin_end_date: fin_end_date},
                    },
                    columns: [
                        {data: 'fintech', name: 'fintech', orderable: false, searchable: true},
                        {data: 'no_of_uploaded_file', name: 'no_of_uploaded_file', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                        {data: 'no_of_disbursement_file', name: 'no_of_disbursement_file', render: $.fn.dataTable.render.number(',').display, orderable: false, searchable: true},
                        // {data: 'day', name: 'day', orderable: false, searchable: true},
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

                        total_no_upload_file = api
                                .column( 1 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 1 ).footer() ).html("Grand Total No. of Upload File:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_upload_file) );

                        total_no_disburesed_file = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 2 ).footer() ).html("Grand Total No of Disbursement File:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disburesed_file) );
                    }
                });
            }
        }
    });
</script>