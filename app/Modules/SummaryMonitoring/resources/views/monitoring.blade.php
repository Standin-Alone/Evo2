@extends('global.base')
@section('title', "Execution Monitoring")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />

    <link href="assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
    <style type="text/css">
        .highcharts-figure, .highcharts-data-table table {
            min-width: 320px; 
            max-width: 500px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }
        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }
        .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
            padding: 0.5em;
        }
        .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }
        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
    <!-- <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
    <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/demo/table-manage-default.demo.min.js"></script>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script> -->

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    
    <script src="assets/plugins/pace/pace.min.js"></script>

    <script type="text/javascript">
        var _token   = "{{csrf_token()}}";
        var reg_code = "<?php echo Session::get('reg_code'); ?>";
        var start    = moment();
        var end      = moment();
        

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        gauge_chart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        call_pie_chart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        stacked_chart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        bar_chart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        call_bar_chart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var picked_start = picker.startDate.format('YYYY-MM-DD');
            var picked_end   = picker.endDate.format('YYYY-MM-DD');
            console.log(picked_start);
            console.log(picked_end);
            gauge_chart(picked_start, picked_end);
            call_pie_chart(picked_start, picked_end);
            stacked_chart(picked_start, picked_end);
        });

        function gauge_chart(date_start, date_end){
            $.ajax({
                url: '{{ route("get-funds-liquidation") }}',
                type: 'get',
                data: {'date_start': date_start, 'date_end': date_end},
                dataType: 'json',
                success: function(data){
                    var el = '';
                    $.each(data, function(k,v){
                        el += '<figure class="highcharts-figure">'+
                                '<div id="container'+k+'" style="height: 300px;"></div>'+
                                '<p class="highcharts-description"></p>'+
                              '</figure>';
                    });
                    //console.log(el);
                    $('#gauge_charts').html(el);

                    $.each(data, function(k,v){
                        generate_gauge_chart(v, k);
                    });
                }
            });
        }

        function call_pie_chart(date_start, date_end){
            $.ajax({
                url: '{{ route("get-pie-data") }}',
                type: 'get',
                data: {'date_start': date_start, 'date_end': date_end},
                dataType: 'json',
                success: function(data){
                    pie_chart(data);
                }
            });
        }

        function stacked_chart(date_start, date_end){
            $.ajax({
                url: '{{ route("get-uploaded-disbursed-data") }}',
                type: 'get',
                data: {'has_special': 1, 'date_start': date_start, 'date_end': date_end},
                dataType: 'json',
                success: function(data){
                    stacked(data);
                }
            });
        }

        function call_bar_chart(date_start, date_end){
            $.ajax({
                url: '{{ route("get-rfo-transactions-data") }}',
                type: 'get',
                data: {'date_start': date_start,'date_end': date_end},
                dataType: 'json',
                success: function(data){
                    console.log(data);

                    bar_chart(data);
                }
            });
        }
    </script>
   
@endsection


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item active">Overview</li>
    <!-- <li class="breadcrumb-item"><a href="javascript:;">Commodity Management</a></li> -->
    <!-- <li class="breadcrumb-item active">Blank Page</li> -->
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Execution Monitoring <small>Overview</small></h1>
<!-- end page-header -->
<div class="row">
    <div class="col-lg-5">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Filters</h4>
            </div>
            <div class="panel-body">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </div>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title">Fund Execution Gauge</h4>
            </div>
            <div class="panel-body">
                <div id="gauge_charts">
                </div>
            </div>
        </div>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title">Transactions</h4>
            </div>
            <div class="panel-body">
                <figure class="highcharts-figure">
                    <div id="bar_chart" style="width: 600px; margin-left: -50px;"></div>
                    <p class="highcharts-description">
                    </p>
                </figure>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title">Distributed Amount</h4>
            </div>
            <div class="panel-body">
                <figure class="highcharts-figure">
                    <div id="pie"></div>
                    <p class="highcharts-description"></p>
                </figure>
            </div>
        </div>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title">Daily Uploaded and Disbursed</h4>
            </div>
            <div class="panel-body">
                <figure class="highcharts-figure">
                        <div id="stacked" style="height:500px;width: 910px; margin-left: -205px"></div>
                        <p class="highcharts-description"></p>
                </figure>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/Highcharts-9.1.2/code/highcharts.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/highcharts-3d.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/themes/sand-signika.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/highcharts-more.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/solid-gauge.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/exporting.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/export-data.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/accessibility.js"></script>
<script type="text/javascript">

    function generate_gauge_chart(data, k){
        Highcharts.chart('container'+k, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: data[0]+'<br>Disbursement<br>',
                align: 'center',
                verticalAlign: 'middle',
                y: 60
            },
            tooltip: {
                formatter: function(tooltip){
                    var x          = this.point.x;
                    var label      = this.key;
                    var percentage = this.y;

                    var content = '<b>'+label+': '+percentage.toFixed(2)+'%</b><br>Amount: '+x;
                    return content;
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        distance: -50,
                        style: {
                            fontWeight: 'bold',
                            color: 'white'
                        }
                    },
                    startAngle: -90,
                    endAngle: 90,
                    center: ['50%', '75%'],
                    size: '110%'
                }
            },
            series: [{
                type: 'pie',
                name: 'Amount',
                keys: ['x'],
                innerSize: '50%',
                data: data[1]
            }]
        });
    }

    function pie_chart(data){
        Highcharts.chart('pie', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'RCEF-RFFA 2021'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            tooltip: {
                //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                formatter: function(){
                    var amount     = this.y;
                    var label      = this.key;
                    var percentage = this.point.percentage;

                    var content = '<b>Amount: ₱'+numberWithCommas(amount.toFixed(2))+'</b><br><b>Percent: '+percentage.toFixed(2)+'%</b>';
                    return content;
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                type: 'pie',
                name: 'Disbursed Amount',
                data: data
            }]
        });
    }

    function stacked(data){
        Highcharts.chart('stacked', {
            chart: {
                type: 'column',
                width: 910,
                options3d: {
                    enabled: true,
                    alpha: 15,
                    beta: 15,
                    viewDistance: 25,
                    depth: 40
                }
            },

            title: {
                text: 'Total Uploaded and Disbursed daily'
            },

            xAxis: {
                tickInterval: 1,
                categories: data['categories'],
                labels: {
                    skew3d: true,
                    style: {
                        fontSize: '16px'
                    }
                }
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Number of records',
                    skew3d: true
                }
            },

            credits: {
                enabled: false
            },

            tooltip: {
                //headerFormat: '<b>{point.key}</b><br>',
                formatter: function(){
                    var date = this.key;
                    var records     = this.point.y;
                    var name      = this.series.name;
                    var total = this.point.stackTotal;
                    var color = this.series.color;

                    var content = '<b>'+date+'</b><br><span style="color:'+color+'">\u25CF</span> <b>'+name+'<b> '+numberWithCommas(records)+' / '+ numberWithCommas(total);
                    return content;
                }
            },

            plotOptions: {
                column: {
                    stacking: 'normal',
                    depth: 40
                }
            },

            series: data['series']
        });
    }

    function bar_chart(data){
        Highcharts.chart('bar_chart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Data Transactions'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: data['categories'],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Transactions',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                },
                max: 500
            },
            tooltip: {
                valueSuffix: ' records'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: data['series'] /*[{
                name: 'Year 1800',
                data: [107, 31, 635, 203, 2]
            }, {
                name: 'Year 1900',
                data: [133, 156, 947, 408, 6]
            }, {
                name: 'Year 2000',
                data: [814, 841, 3714, 727, 31]
            }, {
                name: 'Year 2016',
                data: [1216, 1001, 4436, 738, 40]
            }]*/
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

@endsection