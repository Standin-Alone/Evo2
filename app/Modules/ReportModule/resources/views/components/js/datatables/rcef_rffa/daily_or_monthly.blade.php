<script type="text/javascript">
    $(document).ready(function() {

        $('#filter_month_or_daily').on('change', function(){
            if($(this).val() == 'monthly'){
                $('table#daily-or-monthly-datatable th').eq(0).append('Monthly').empty();

                $('table#daily-or-monthly-datatable th').eq(0).append('Monthly');

                var table = $('#daily-or-monthly-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
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
                                    title: 'Report: By Monthly',
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
                                    title: 'Report: By Monthly',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Report: By Monthly',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Report: By Monthly',
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
                    ajax: "{{route('reports.rffa_monthly')}}",
                    columns: [
                        {data: 'month', name: 'month', orderable: false, searchable: true},
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
                                .column( 1 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 1 ).footer() ).html("Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                        // amount column[6]: get it's total sum
                        total_no_disbursed = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 2 ).footer() ).html("Total no. of Disbursed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disbursed) );

                        // Total amount column[7]: get it's total sum
                        total_amount = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 3 ).footer() ).html("Total Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );
                    }
                });
            }else if($(this).val() == ''){

                this_month();
                
            }
        });
    });

    // custom range
    function fetch_start_date_and_end_date(start_date, end_date){
        $('#daily-or-monthly-datatable').DataTable({
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
                                    title: 'Report: By '+start_date+' to '+end_date,
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
                                    title: 'Report: By '+start_date+' to '+end_date,
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Report: By '+start_date+' to '+end_date,
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Report: By '+start_date+' to '+end_date,
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
                    url: "{{route('reports.rffa_custom_range')}}",
                    data:{start_date: start_date, end_date: end_date},
            },
            columns: [
                {data: 'day', name: 'day', orderable: false, searchable: true},
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
                            .column( 1 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( [1] ).footer() ).html("Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                    total_no_disbursed = api
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 2 ).footer() ).html("Total no. of Disbursed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disbursed) );

                    total_amount = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 3).footer() ).html("Total amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );
            }
        });
    }

    fetch_start_date_and_end_date();

    $('#reportrange').on("apply.daterangepicker", function(ev, picker) {
        // e.preventDefault();
        // console.log(ev);
        // console.log(picker);

        var start_date = picker.startDate.format('YYYY-MM-DD');
        var end_date = picker.endDate.format('YYYY-MM-DD');
        var route = "";
        var col = "";
        var col_name = "";
        var header = "";

        if(picker.chosenLabel == "Custom Range"){
            $('table#daily-or-monthly-datatable th').eq(0).append(start_date+' - '+end_date).empty();

            $('table#daily-or-monthly-datatable th').eq(0).append(start_date+' - '+end_date);

            $('#daily-or-monthly-datatable').DataTable().destroy();

            fetch_start_date_and_end_date(start_date, end_date);
        }else{
            if(picker.chosenLabel == "Today"){
                route= "{{route('reports.rffa_today')}}"

                col = "today";

                col_name = "today";

                // header = "Today";

                $('table#daily-or-monthly-datatable th').eq(0).append("Today").empty();

                $('table#daily-or-monthly-datatable th').eq(0).append("Today");

                $('#daily-or-monthly-datatable').DataTable().destroy();
            }
            if(picker.chosenLabel == "Yesterday"){
                route= "{{route('reports.rffa_yesterday')}}"

                col = "yesterday";

                col_name = "yesterday";

                $('table#daily-or-monthly-datatable th').eq(0).append("Yesterday").empty();

                $('table#daily-or-monthly-datatable th').eq(0).append("Yesterday");

                $('#daily-or-monthly-datatable').DataTable().destroy();
            }
            if(picker.chosenLabel == "Last 7 Days"){
                route= "{{route('reports.rffa_last_7_days')}}"

                col = "last_7_days";

                col_name = "last_7_days";

                // header = "Last 7 Days";

                $('table#daily-or-monthly-datatable th').eq(0).append("Last 7 Days").empty();

                $('table#daily-or-monthly-datatable th').eq(0).append("Last 7 Days");

                $('#daily-or-monthly-datatable').DataTable().destroy();
            }
            if(picker.chosenLabel == "Last 30 Days"){
                route= "{{route('reports.rffa_last_30_days')}}"

                col = "last_30_days";

                col_name = "last_30_days";

                $('table#daily-or-monthly-datatable th').eq(0).append("Last30 Days").empty();

                $('table#daily-or-monthly-datatable th').eq(0).append("Last 30 Days");

                $('#daily-or-monthly-datatable').DataTable().destroy();
            }
            if(picker.chosenLabel == "Last Month"){
                route= "{{route('reports.rffa_last_month')}}"

                col = "last_month";

                col_name = "last_month";

                $('table#daily-or-monthly-datatable th').eq(0).append("Last Month").empty();

                $('table#daily-or-monthly-datatable th').eq(0).append("Last Month");

                $('#daily-or-monthly-datatable').DataTable().destroy();
            }
            if(picker.chosenLabel == "This Month"){
                route= "{{route('reports.rffa_this_month')}}"

                col = "this_month";

                col_name = "this_month";

                $('table#daily-or-monthly-datatable th').eq(0).append("This Month").empty();

                $('table#daily-or-monthly-datatable th').eq(0).append("This Month");

                $('#daily-or-monthly-datatable').DataTable().destroy();
            }

            $('#daily-or-monthly-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                responsive: true,
                // "scrollY": "100%",
                // "scrollCollapse": true,
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
                                        // title: 'Report: By Last 7 Days',
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
                                        // title: 'Report: By Last 7 Days',
                                        extend: 'excelHtml5',
                                        footer: true,
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-excel"></i> CSV',
                                        // title: 'Report: By Last 7 Days',
                                        extend: 'csvHtml5',
                                        footer: true,
                                        fieldSeparator: ';',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                    }, 
                                    {
                                        text: '<i class="far fa-file-pdf"></i> PDF',
                                        // title: 'Report: By Last 7 Days',
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
                    {data: col, name: col_name, orderable: false, searchable: true},
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
                                .column( 1 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 1 ).footer() ).html("Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                        // amount column[6]: get it's total sum
                        total_no_disbursed = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;}, 0 );
                                $( api.column( 2 ).footer() ).html("Total no. of Disbursed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disbursed) );

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

        // var start_date = moment($("input[name=daterangepicker_start]").val()).format('YYYY-MM-DD');
        // var end_date = moment($("input[name=daterangepicker_end]").val()).format('YYYY-MM-DD');

        // var start_date = [];

        // var end_date = [];

        // ($("input[name=daterangepicker_start]")).each(function(index, start_val){
        //     if(index == 0){

        //         var start_date_value = moment(start_val.value).format('YYYY-MM-DD');

        //         console.log("["+ index+ "]" + start_date_value);

        //         start_date.push(start_date_value);
        //     }
        // });

        // ($("input[name=daterangepicker_end]")).each(function(index, end_val){
        //     if(index == 0){
        //         var end_date_value = moment(end_val.value).format('YYYY-MM-DD');

        //         console.log("["+ index+ "]" + end_date_value);

        //         end_date.push(end_date_value);
        //     }
        // });

        // $('table#daily-or-monthly-datatable th').eq(0).append(start_date+' - '+end_date).empty();

        // $('table#daily-or-monthly-datatable th').eq(0).append(start_date+' - '+end_date);

        // $('#daily-or-monthly-datatable').DataTable().destroy();

        // fetch_start_date_and_end_date(start_date, end_date);
    });


    function this_month() {
        $('table#daily-or-monthly-datatable th').eq(0).append('This Month').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('This Month');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
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
                                    title: 'Report: By This Month',
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
                                    title: 'Report: By This Month',
                                    extend: 'excelHtml5',
                                    footer: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-excel"></i> CSV',
                                    title: 'Report: By This Month',
                                    extend: 'csvHtml5',
                                    footer: true,
                                    fieldSeparator: ';',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                }, 
                                {
                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                    title: 'Report: By This Month',
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
                    url: "{{route('reports.rffa_this_month')}}",
            },
            columns: [
                {data: 'this_month', name: 'this_month', orderable: false, searchable: true},
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
                            .column( 1 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 1 ).footer() ).html("Total no. of Uploaded KYC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_upload_kyc) );

                    total_no_disbursed = api
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 2 ).footer() ).html("Total no. of Disbursed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_no_disbursed) );

                    total_amount = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;}, 0 );
                            $( api.column( 3 ).footer() ).html("Total amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );
            }
        });
    }
</script>