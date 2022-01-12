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
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
    
    {{-- Include external JS components --}}
    @include('ReportModule::components.js.js')

    {{-- Voucher Claimed DataTable --}}
    @include('ReportModule::components.js.datatables.voucher.ready_voucher_datatable')

    {{-- Dynamic dropdown --}}
    @include('ReportModule::components.js.dropdown.ready_voucher_dynamic_dropdown')
@endsection


<script>

</script>


@section('content')
{{-- <input type="hidden" id="refno" value="1"> --}}
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    {{-- <li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li> --}}
    <li class="breadcrumb-item active">TOTAL READY VOUCHERS</li>
</ol>
<!-- end breadcrumb -->

<div class="row mt-5">
    @include('ReportModule::components.dashboard_cards.ready_voucher_db_card')
</div>
<!-- end row -->

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Total Ready Voucher</h4>
    </div>
    <div class="panel-body">
        <br>
        <div class="row">
            @include('ReportModule::components.filter_cards.ready_voucher_filter_card')
        </div>
        <br>
        <table id="ready-voucher-datatable" class="table table-bordered" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>REGION</th>
                    <th>PROVINCE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</div>
@endsection
