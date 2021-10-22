@extends('global.base')
@section('title', "Report")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

    <!-- Include Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    {{-- Include external JS components --}}
    @include('ReportModule::components.css.css')
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
    <script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

    {{-- Include external JS components --}}
    @include('ReportModule::components.js.js')

    @include('ReportModule::components.js.datatables.by_region_co_focal_summary')
    @include('ReportModule::components.js.datatables.rffa_summary_reports_datatable')
    @include('ReportModule::components.js.datatables.by_reg_prov_muni_and_brgy_summary')
    
    @include('ReportModule::components.js.date_range_picker.daterangepicker')

    @include('ReportModule::components.js.datatables.daily_or_monthly')

    @include('ReportModule::components.js.datatables.report_by_approval_level')

    {{-- Dynamic dropdown --}}
    {{-- @include('ReportModule::components.js.dropdown.dynamic_dropdown') --}}

    <script type="text/javascript">
        // var handleDateRangePicker = function() {
        //     $('#date_range_filter span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        //     $('#date_range_filter').daterangepicker({
        //         format: 'MM/DD/YYYY',
        //         startDate: moment().subtract(29, 'days'),
        //         endDate: moment(),
        //         minDate: '01/01/2012',
        //         maxDate: '12/31/2015',
        //         dateLimit: { days: 60 },
        //         showDropdowns: true,
        //         showWeekNumbers: true,
        //         timePicker: false,
        //         timePickerIncrement: 1,
        //         timePicker12Hour: true,
        //         ranges: {
        //         'Today': [moment(), moment()],
        //         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        //         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        //         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //         'This Month': [moment().startOf('month'), moment().endOf('month')],
        //         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        //         }, 
        //         opens: 'right',
        //         drops: 'down',
        //         buttonClasses: ['btn', 'btn-sm'],
        //         applyClass: 'btn-primary',
        //         cancelClass: 'btn-default',
        //         separator: ' to ',
        //         locale: {
        //             applyLabel: 'Submit',
        //             cancelLabel: 'Cancel',
        //             fromLabel: 'From',
        //             toLabel: 'To',
        //             customRangeLabel: 'Custom',
        //             daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
        //             monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        //             firstDay: 1
        //         }
        //     }, function(start, end, label) {
        //         $('#date_range_filter span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //     });
        // };
    </script>

@endsection


<script>

</script>


@section('content')
{{-- <input type="hidden" id="refno" value="1"> --}}
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">RCEF RFFA REPORTS</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
{{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}
<!-- end page-header -->
<div class="row mt-5">
    {{-- @include('ReportModule::components.dashboard_cards.paid_db_card') --}}
</div>
<!-- end row -->

<!-- begin panel -->
{{-- <div class="panel-body panel-form">
    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span> <i class="fa fa-caret-down"></i>
    </div>
</div> --}}

@if (session()->get('region') != 13)
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">By RFO Program Focal Summary</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        <table id="rfo-program-focal-datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>PROVINCE</th>
                    <th>NO. OF UPLOADED KYC</th>
                    <th>NO. OF DISBURSED</th>
                    <th>TOTAL DISBURSED AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
        <br>
        <hr>
        <br>
        <span class="mt-5">
            <div class="note note-primary">
                <div class="note-icon"><i class="far fa-file-alt"></i></div>
                <div class="note-content">
                  <h4><b> REPORTS BY APPROVAL LEVEL </b></h4>
                </div>
            </div>
        </span>
        <br>
        <table id="co-program-focal-report_approval" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>PROVINCE</th>
                    <th>TOTAL UPLOADED</th>
                    <th>GENERATE BENEFECIARIES</th>
                    <th>BUDGET</th>
                    <th>DISBURSEMENT</th>
                    {{-- <th>Final Approval</th> --}}
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                {{-- <th></th> --}}
            </tfoot>
        </table>
    </div>
</div>
@else
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">BY CO PROGRAM FOCAL SUMMARY</h4>
    </div>
    <div class="panel-body">
        <br>
        <span class="mt-5">
            <div class="note note-primary">
                <div class="note-icon"><i class="far fa-file-alt"></i></div>
                <div class="note-content">
                  <h4><b> BY REGION </b></h4>
                </div>
            </div>
        </span>
        <br>
        <table id="co-program-focal-datatable-by-region" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>REGION</th>
                    <th>NO. OF UPLOADED KYC</th>
                    <th>NO. OF DISBURSED</th>
                    <th>TOTAL DISBURSED AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
        <br>
        <hr>
        <br>
        <span class="mt-5">
            <div class="note note-primary">
                <div class="note-icon"><i class="far fa-file-alt"></i></div>
                <div class="note-content">
                  <h4><b> BY REGION AND PROVINCE </b></h4>
                </div>
            </div>
        </span>
        <br>
        <table id="co-program-focal-datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>REGION</th>
                    <th>PROVINCE</th>
                    <th>NO. OF UPLOADED KYC</th>
                    <th>NO. OF DISBURSED</th>
                    <th>TOTAL DISBURSED AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
        <br>
        <hr>
        <br>
        <span class="mt-5">
            <div class="note note-primary">
                <div class="note-icon"><i class="far fa-file-alt"></i></div>
                <div class="note-content">
                  <h4><b> BY REGION, PROVINCE, MUNICIPALITY AND BARANGAY </b></h4>
                </div>
            </div>
        </span>
        <br>
        <table id="co-program-focal-datatable-by-region-province-municipality-and-barangay" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>REGION</th>
                    <th>PROVINCE</th>
                    <th>MUNICIPALITY</th>
                    <th>BARANGAY</th>
                    <th>NO. OF UPLOADED KYC</th>
                    <th>NO. OF DISBURSED</th>
                    <th>TOTAL DISBURSED AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
        <br>
        <hr>
        <br>
        <span class="mt-5">
            <div class="note note-primary">
                <div class="note-icon"><i class="far fa-file-alt"></i></div>
                <div class="note-content">
                  <h4><b>REPORT BY APPROVAL LEVEL</b></h4>
                </div>
            </div>
        </span>
        <br>
        <table id="co-program-focal-report_approval" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>PROVINCE</th>
                    <th>TOTAL UPLOADED</th>
                    <th>GENERATE BENEFECIARIES</th>
                    <th>BUDGET</th>
                    <th>DISBURSEMENT</th>
                    {{-- <th>Final Approval</th> --}}
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                {{-- <th></th> --}}
            </tfoot>
        </table>
    </div>
</div>
@endif

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title"></h4>
    </div>
    <div class="panel-body">
        <br>
        @include('ReportModule::components.filter_cards.daily_or_monthly_filter_card')
        <br>
        <table id="daily-or-monthly-datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>NO. OF UPLOADED KYC</th>
                    <th>NO. OF DISBURSED</th>
                    <th>TOTAL DISBURSED AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</div>
@endsection
