<script type="text/javascript">
    $(function() {
        $('table#daily-or-monthly-datatable th').eq(0).append('This Month').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('This Month');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_this_month')}}",

            },
            columns: [
                {data: 'this_month', name: 'this_month'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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

        $('#filter_month_or_daily').on('change', function(){
            if($(this).val() == 'monthly'){

                $('table#daily-or-monthly-datatable th').eq(0).append('Monthly').empty();

                $('table#daily-or-monthly-datatable th').eq(0).append('Monthly');

                var table = $('#daily-or-monthly-datatable').DataTable({
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
                    ajax: "{{route('reports.rffa_monthly')}}",
                    columns: [
                        {data: 'month', name: 'month'},
                        {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                        {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                        {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
        });
    });


    // Get By Today
    $(document).on("click", "li[data-range-key=Today]", function(e){
        e.preventDefault();

        console.log('Today');

        $('table#daily-or-monthly-datatable th').eq(0).append('Today').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('Today');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_today')}}",
                    // data:{start_date: start_date, end_date: end_date},
            },
            columns: [
                {data: 'today', name: 'today'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
    });

    // Get By Yesterday
    $(document).on("click", "li[data-range-key=Yesterday]", function(e){
        e.preventDefault();

        console.log('Yesterday');

        $('table#daily-or-monthly-datatable th').eq(0).append('Yesterday').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('Yesterday');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_yesterday')}}",
                    // data:{start_date: start_date, end_date: end_date},
            },
            columns: [
                {data: 'yesterday', name: 'yesterday'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
    });

    // Get By Last 7 Days
    $(document).on("click", "li[data-range-key='Last 7 Days']", function(e){
        e.preventDefault();

        console.log('Last 7 Days');

        $('table#daily-or-monthly-datatable th').eq(0).append('Last 7 Days').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('Last 7 Days');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_last_7_days')}}",

            },
            columns: [
                {data: 'last_7_days', name: 'last_7_days'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
    });

    // Get by Last 30 Days
    $(document).on("click", "li[data-range-key='Last 30 Days']", function(e){
        e.preventDefault();

        console.log('Last 30 Days');

        $('table#daily-or-monthly-datatable th').eq(0).append('Last 30 Days').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('Last 30 Days');
    
        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_last_30_days')}}",

            },
            columns: [
                {data: 'last_30_days', name: 'last_30_days'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
    });

    // Get by This Month
    $(document).on("click", "li[data-range-key='This Month']", function(e){
        e.preventDefault();

        console.log('This Month');

        $('table#daily-or-monthly-datatable th').eq(0).append('This Month').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('This Month');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_this_month')}}",

            },
            columns: [
                {data: 'this_month', name: 'this_month'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
    });

    // Get by Last Month
    $(document).on("click", "li[data-range-key='Last Month']", function(e){
        e.preventDefault();

        console.log('Last Month');

        $('table#daily-or-monthly-datatable th').eq(0).append('Last Month').empty();

        $('table#daily-or-monthly-datatable th').eq(0).append('Last Month');

        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_last_month')}}",

            },
            columns: [
                {data: 'last_month', name: 'last_month'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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
    });

    // custom range
    function fetch(start_date, end_date){
        $('#daily-or-monthly-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            responsive: true,
            // "scrollY": "100%",
            // "scrollCollapse": true,
            paging: true,
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
            ajax: {
                    url: "{{route('reports.rffa_custom_range')}}",
                    data:{start_date: start_date, end_date: end_date},
            },
            columns: [
                {data: 'day', name: 'day'},
                {data: 'no_of_uploads_kyc', name: 'no_of_uploads_kyc'},
                {data: 'no_of_disbursed', name: 'no_of_disbursed'},
                {data: 'total_disbursed_amount', name: 'total_disbursed_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;').display},
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

    fetch();

    $(document).on("click", "button.applyBtn", function(e) {
        e.preventDefault();

        var start_date = moment($("input[name=daterangepicker_start]").val()).format('YYYY-MM-DD');
        var end_date = moment($("input[name=daterangepicker_end]").val()).format('YYYY-MM-DD');

        $('table#daily-or-monthly-datatable th').eq(0).append(start_date+' - '+end_date).empty();

        $('table#daily-or-monthly-datatable th').eq(0).append(start_date+' - '+end_date);

        $('#daily-or-monthly-datatable').DataTable().destroy();

        fetch(start_date, end_date);
    });
</script>