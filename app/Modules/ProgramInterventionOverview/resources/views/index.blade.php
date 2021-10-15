@extends('global.base')
@section('title', "Program Intervention Management")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <style type="text/css">
        .highcharts-figure, .highcharts-data-table table {
            min-width: 320px; 
            max-width: 500px;
            margin: 1em auto;
        }

        #container {
            height: 400px;
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
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
    <!-- <script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
    <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/demo/table-manage-default.demo.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-loading-overlay/dist/loadingoverlay.min.js"></script>

    <script type="text/javascript">
        var token         = "{{csrf_token()}}";
        var dt_program_id = '';
        var program_status= '';
        var program_tr;

        $('#no_data').LoadingOverlay('show');
        $('#info_panel').LoadingOverlay('show');

        $(function() {
            $('#no_data').LoadingOverlay('hide');
            $('#info_panel').LoadingOverlay('hide');
        });

        $.ajax({
            url: '{{route("get-programs")}}',
            type: 'get',
            dataType: 'json',
            success: function(result){
                var tr = '';

                if(result.length > 0){
                    $.each(result, function(k, v){
                        var status = v.status == 1 ? 'Active/Ongoing' : 'Inactive/Ended';
                        tr += '<tr>'+
                                '<td class="text-center"><a href="javascript:void(0)" class="view_status" data-progid="'+v.program_id+'" data-status="'+v.status+'">'+v.title+'</a></td>'+
                                '<td class="text-center">'+'₱'+numberWithCommas(v.amount)+'</td>'+
                                '<td class="text-center prog_status">'+status+'</td>'+
                              '</tr>';
                    });
                }else {
                    tr = '<tr id="base_tr"><td class="text-center">No data available.</td></tr>';
                }

                $('#no_data').LoadingOverlay('show');
                $('#prog_tbody').empty().append(tr);
            }
        });

        $(document.body).on('click', '.view_status', function() {
            var program_id = $(this).attr('data-progid');
            var status     = $(this).attr('data-status');
            var that       = $(this);
            $(this).closest('tbody').find('tr').removeClass('info');

            $('#info_panel').LoadingOverlay('show');
            $('#empty_info').show();
            $('#info').hide();
            $.ajax({
                url: '{{route("get-program-liquidation")}}',
                type: 'get',
                data: {'program_id': program_id},
                dataType: 'json',
                success: function(result){
                    if(result.length > 0){
                        generate_gauge_chart(result);
                        that.closest('tr').addClass('info');

                        $('#info_title').text(result[2].title);
                        $('#info_author').text(result[2].author);
                        $('#info_date_encoded').text(result[2].date_created);
                        $('#info_program_id').text(result[2].program_id);
                        $('#info_description').text(result[2].description);

                        if(status == '1'){
                            $('#lock_intervention').html('<i class="fas fa-lock t-plus-1 fa-fw fa-lg text-danger"></i> Lock/Disable');
                        }else {
                            $('#lock_intervention').html('<i class="fas fa-unlock t-plus-1 fa-fw fa-lg text-success"></i> Unlock/Activate');
                        }
                        
                        program_tr = that;

                        $('#empty_info').hide();
                        $('#info').show();
                    }
                } 
            });

            $('#info_panel').LoadingOverlay('hide');

            program_status = status;
            dt_program_id  = program_id;
            payout_details.draw();
        });

        $(document.body).on('click', '#lock_intervention', function(){
            //console.log(Swal.fire());
            console.log(program_status);
            var swal_text = program_status == 1 ? 'Locked' : 'Unlocked';
            var swal_btn  = program_status == 1 ? 'lock' : 'unlock';
            Swal.fire({
                title: 'Are you sure?',
                text: "This intervention will be "+swal_text+".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, '+swal_btn+' it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    console.log(program_tr);
                    console.log(program_status);
                    $.ajax({
                        url: '{{route("lock-intervention")}}',
                        type: 'post',
                        data: {'_token': token, 'dt_program_id': dt_program_id, 'status': program_status},
                        dataType: 'json',
                        success: function(result){
                            console.log(result);
                            program_status = result.status;
                            program_tr.attr('data-status', result.status);
                            
                            if(result.status == '0'){
                                $('#lock_intervention').html('<i class="fas fa-unlock t-plus-1 fa-fw fa-lg text-success"></i> Unlock/Activate');
                                program_tr.closest('tr').find('.prog_status').text('Inactive/Ended');
                            }else {
                                $('#lock_intervention').html('<i class="fas fa-lock t-plus-1 fa-fw fa-lg text-danger"></i> Lock/Disable');
                                program_tr.closest('tr').find('.prog_status').text('Active/Ongoing');
                            }

                            Swal.fire(
                                swal_text+'!',
                                'Intervention has been '+swal_text+'.',
                                'success'
                            )
                        }
                    });
                }
            })
        });

        var payout_details = $('#payout_details').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route("get-program-payouts")}}',
                type: 'post',
                data: function(d) { 
                    d._token     = token; 
                    d.program_id = dt_program_id;
                }
            },
            columns: [
                {
                    class: 'text-center',
                    render: function(data, type, row){
                        var href = '<a href="#">'+row.supplier_name+'</a>';
                        return href;
                    }
                },
                {
                    class: 'text-center',
                    render: function(data, type, row){
                        var href = '<a href="#">'+row.application_number+'</a>';
                        return href;
                    }
                },
                {
                    class: 'text-center',
                    render: function(data, type, row){
                        var amount = numberWithCommas(row.amount);
                        return '₱'+amount;
                    }
                },
                {
                    data: 'transac_date',
                    class: 'text-center'
                }
            ]
        });

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
@endsection


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item active">Overview</li>
    <li class="breadcrumb-item"><a href="{{ url('create-program-intervention') }}">Create Program</a></li>
    <!-- <li class="breadcrumb-item"><a href="javascript:;">Commodity Management</a></li> -->
    <!-- <li class="breadcrumb-item active">Blank Page</li> -->
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Interventions Program <small>Overview</small></h1>
<!-- end page-header -->

<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Active and Inactive Interventions</h4>
            </div>
            <div class="panel-body" style="height: 456px;">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>                    
                            <th class="text-center">Intervention Program</th>
                            <th class="text-center">Budget</th>                    
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="prog_tbody">
                         <tr>
                            <td colspan="3" class="text-center" id="no_data">No data available.</td>
                            <!-- <td>&#8369;8,500,000</td>
                            <td>Active/Ongoing</td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Disbursement Gauge</h4>
            </div>
            <div class="panel-body">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description"></p>
                </figure>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Intervention Information</h4>
            </div>
            <div class="panel-body" id="info_panel">
                <div class="alert alert-warning text-center" id="empty_info">Please select intervention above</div>
                <div class="invoice" style="display: none;" id="info">
                    <div class="invoice-company text-inverse f-w-600">
                        <span class="pull-right hidden-print">
                        <!-- <a href="javascript:;" class="btn btn-sm btn-white m-b-10 p-l-5"><i class="fa fa-file-pdf t-plus-1 text-danger fa-fw fa-lg"></i> Export as PDF</a> -->
                        <a href="javascript:;" id="lock_intervention" class="btn btn-sm btn-white m-b-10 p-l-5"> Lock/Disable</a>
                        </span>
                        <span id="info_title"></span>
                    </div>
                    <div class="invoice-header">
                        <div class="invoice-from">
                            <small>Encoded By</small>
                            <address class="m-t-5 m-b-5">
                                <strong class="text-inverse"><span id="info_author"></span></strong><br />
                                Department of Agriculture
                            </address>
                        </div>
                        <div class="invoice-to">
                        </div>
                        <div class="invoice-date">
                            <small>Date Encoded:</small>
                            <div class="date text-inverse m-t-5"><span id="info_date_encoded"></span></div>
                            <div class="invoice-detail">
                                ID: <span id="info_program_id"></span>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-content">
                        <div class="table-responsive">
                            <table class="table table-invoice">
                                <thead>
                                    <tr>
                                        <th>INTERVENTION DESCRIPTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>
                                        <p id="info_description"></p>
                                    </td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Intervention Payout Details</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" id="payout_details">
                    <thead>
                        <tr>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Application Number</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/Highcharts-9.1.2/code/highcharts.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/themes/sand-signika.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/highcharts-more.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/solid-gauge.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/exporting.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/export-data.js"></script>
<script src="assets/js/Highcharts-9.1.2/code/modules/accessibility.js"></script>
<script type="text/javascript">
    Highcharts.setOptions({
        colors: ['#313131','#50B432']
    });

    
    // Bring life to the dials
    /*setInterval(function () {
        // Speed
        var point,
            newVal,
            inc;

        if (chartSpeed) {
            point = chartSpeed.series[0].points[0];
            inc = Math.round((Math.random() - 0.5) * 100);
            newVal = point.y + inc;

            if (newVal < 0 || newVal > 200) {
                newVal = point.y - inc;
            }

            point.update(newVal);
        }
    }, 2000);*/

    var data = ['Program'];
    generate_gauge_chart(data);

    function generate_gauge_chart(data){
        Highcharts.chart('container', {
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
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
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
                name: 'Browser share',
                innerSize: '50%',
                data: data[1]
            }]
        });
    }

</script>
@endsection