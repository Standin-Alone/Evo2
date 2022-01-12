@extends('global.base')
@section('title', "Report")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />
    
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

    {{-- Include external JS components --}}
    @include('ReportModule::components.js.js')

    {{-- Voucher Claimed DataTable --}}
    @include('ReportModule::components.js.datatables.voucher.voucher_claimed_datatable')

    {{-- Dynamic dropdown --}}
    @include('ReportModule::components.js.dropdown.dynamic_dropdown')
@endsection


<script>

</script>


@section('content')
{{-- <input type="hidden" id="refno" value="1"> --}}
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">Total Claimed Voucher</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
{{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}
<!-- end page-header -->
<div class="row mt-5">
    @include('ReportModule::components.dashboard_cards.paid_db_card')
</div>
{{-- <span class="text-danger">*Note: If the program card did not appear above. It means that the program are not available or does have a value of "0.00".</span> --}}
<!-- end row -->

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">TOTAL CLAIMED VOUCHER BY REGION, PROVINCE & SUPPLIER</h4>
    </div>
    <div class="panel-body">
        <br>
        <div class="row">
            @include('ReportModule::components.filter_cards.filter_card')
        </div>
        <br>
        <table id="voucher-claimed-datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>SUPPLIER</th>
                    <th>PROGRAM</th>
                    <th>REGION</th>
                    <th>PROVINCE</th>
                    <th>QUANTITY</th>
                    <th>AMOUNT</th>
                    <th>TOTAL AMOUNT</th>
                    <th>PAYOUT STATUS</th>
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
                <th></th>
            </tfoot>
        </table>
    </div>
</div>
@endsection
