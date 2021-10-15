@extends('global.base')
@section('title', "Budget")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

    {{-- External CSS --}}
    @include('BudgetModule::components.css.css')
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
    <script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}" ></script> 

    {{-- External JS --}}
    @include('BudgetModule::components.js.js')

    {{-- Fund Source Breakdown Datatable --}}
    @include('BudgetModule::components.js.datatables.breakdown_datatable')
@endsection


<script>

</script>


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('fund_moni_and_disb') }}">Fund monitoring and disbursement</a></li>
    <li class="breadcrumb-item active">Fund source breakdown</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
{{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}
<!-- end page-header -->
<!-- begin panel -->
<div class="panel panel-inverse mt-5">
    <div class="panel-heading">
        <h4 class="panel-title">Fund Source Breakdown</h4>
    </div>
    <div class="panel-body mt-5">
        <table id="fund_source_breakdown_tbl" class="table table-striped table-bordered table-hover text-center mt-5" style="width:100%;">            
            <thead class="table-header">
                <tr>      
                    {{-- <th> Fund Source </th>--}}
                    <th> Reference No. </th>
                    <th> Supplier </th>
                    <th> Program </th>
                    <th> Item </th>
                    <th> Quantity </th>
                    <th> Amount </th>
                    <th> Total Amount </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                {{-- <th></th> --}}
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
<!-- end panel -->
@endsection