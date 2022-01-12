@extends('global.base')
@section('title', "Report")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

    {{-- Include Date Range Picker --}}
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    {{-- Select2 plugin --}}
        <link href="{{url('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
   
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

    {{-- Date Range Picker --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

    {{-- Select2 plugin --}}
        <script src="{{url('assets/plugins/select2/dist/js/select2.min.js')}}"></script>

    {{-- Include external JS components --}}
    @include('ReportModule::components.js.js')
    {{-- RCEF-RFFA DataTables --}}

        <!-- By Region RFO or CO Focal Summary -->
        @include('ReportModule::components.js.datatables.rcef_rffa.by_region_co_focal_summary')


        <!-- By Region and Province RFO or CO Focal Summary -->
        @include('ReportModule::components.js.datatables.rcef_rffa.by_region_and_province_summary')
        {{-- @include('ReportModule::components.js.datatables.rcef_rffa.rffa_summary_reports_datatable') --}}


        <!-- By Region, Province, Municipality, and Barangay RFO or CO Focal Summary -->
        @include('ReportModule::components.js.datatables.rcef_rffa.by_reg_prov_muni_and_brgy_summary')


        <!-- Set by Date Range Picker RFO or CO Focal Summary -->
        @include('ReportModule::components.js.date_range_picker.daterangepicker')


        <!-- Set by DAILY or MONTHLY RFO or CO Focal Summary -->
        @include('ReportModule::components.js.datatables.rcef_rffa.daily_or_monthly')


        <!-- Set by DAILY or MONTHLY FINTECH FILES -->
        @include('ReportModule::components.js.date_range_picker.fintech_file_daterangepicker')
        @include('ReportModule::components.js.datatables.rcef_rffa.fintech_uploaded_file_and_disbursement_file')


        <!-- Set by DAILY or MONTHLY FINTECH RECORDS -->
        @include('ReportModule::components.js.date_range_picker.fintech_records_daterangepicker')
        @include('ReportModule::components.js.datatables.rcef_rffa.fintech_records_uploaded_and_records_disbursed')


        <!-- By Region Report By Approval RFO or CO Focal Summary -->
        @include('ReportModule::components.js.datatables.rcef_rffa.region_report_by_approval')


        <!-- By Report By Approval Level RFO or CO Focal Summary -->
        @include('ReportModule::components.js.datatables.rcef_rffa.report_by_approval_level')


    {{-- Dynamic dropdown --}}
        <!-- By Region CO Focal Summary -->
        @include('ReportModule::components.js.dropdown.by_region_co_focal_summary')


        <!-- By Region and Province CO Focal Summary -->
        @include('ReportModule::components.js.dropdown.by_reg_and_prov_co_focal_summary')
        

        <!-- By Region, Province, Municipality and Barangay -->
        @include('ReportModule::components.js.dropdown.by_reg_prov_muni_and_brgy_summary_dynamic_dropdown_co_focal_summary')


        <!-- By Region Report Level CO Focal Summary -->
        @include('ReportModule::components.js.dropdown.by_region_report_level_co_focal_summary')


        <!-- By Report Level CO Focal Summary -->
        @include('ReportModule::components.js.dropdown.by_report_level_co_focal_summary')
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
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">By RFO Program Focal Summary</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        {{-- By RFO Program Focal Summary --}}
        @include('ReportModule::user.by_rfo_program_focal_summary')
        <br>
        <hr>
        <br>
        {{-- REPORT BY REGIONAL LEVEL --}}
        @include('ReportModule::user.report_by_regional_level')
        <br>
        <hr>
        <br>
        {{-- REPORTS BY APPROVAL LEVEL --}}
        @include('ReportModule::user.rfo_reports_by_approval_level')
    </div>
</div>
@else
<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">BY CO PROGRAM FOCAL SUMMARY</h4>
    </div>
    <div class="panel-body">
        <br>
        {{-- BY FINTECH FILES --}}
        @include('ReportModule::admin.fintech_uploaded_file')
        <br>
        <hr>
        <br>
        {{-- BY FINTECH RECORDS --}}
        @include('ReportModule::admin.fintech_record_uploaded')
        <br>
        <hr>
        <br>
        {{-- BY REGION --}}
        @include('ReportModule::admin.by_region')
        <br>
        <hr>
        <br>
        {{-- BY REGION AND PROVINCE --}}
        @include('ReportModule::admin.by_region_and_province')
        <br>
        <hr>
        <br>
        {{-- BY REGION, PROVINCE AND MUNICIPALITY --}}
        @include('ReportModule::admin.by_region_province_and_municipality')
        <br>
        <hr>
        <br>
        {{-- REPORT BY REGIONAL LEVEL --}}
        @include('ReportModule::admin.report_by_regional_level')
        <hr>
        <br>
        {{-- REPORT BY APPROVAL LEVEL --}}
        @include('ReportModule::admin.report_by_approval_level')
    </div>
</div>
@endif

<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">SET BY DATE RANGE OR BY MONTHLY</h4>
    </div>
    <div class="panel-body">
        <br>
        <div class="row">
            @include('ReportModule::components.filter_cards.daily_or_monthly_filter_card')
        </div>
        <br>
        {{-- SET BY ADVANCE DATE RANGE OR SET BY MONTHLY --}}
        @include('ReportModule::set_by_date_range_or_by_monthly')
    </div>
</div>
@endsection
