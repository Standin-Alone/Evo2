@extends('global.base')
@section('title', 'Supplier Application and Validation')

{{-- import in this section your css files --}}
@section('page-css')
    <link href="{{ url('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet" />

    {{-- SWITCHERY --}}
    <link href="{{ url('assets/plugins/powerange/powerange.min.css') }}" rel="stylesheet" />

    {{-- Include Date Range Picker --}}
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    {{-- Select2 plugin --}}
    <link href="{{ url('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />

    <link href="{{ url('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />

    {{-- Include external JS components --}}
    @include('RfoApprovalModule::components.css.css')
@endsection




{{-- import in this section your javascript files --}}
@section('page-js')
    <script src="{{ url('assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/js/demo/ui-modal-notification.demo.min.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('assets/js/demo/table-manage-default.demo.min.js') }}"></script>

    {{-- Date Range Picker --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

    {{-- Select2 plugin --}}
    <script src="{{ url('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    {{-- Include external JS components --}}
    @include('RfoApprovalModule::components.js.js')

    {{-- Datatables --}}
    {{-- @include('RfoApprovalModule::components.js.datatables.list_of_request_for_main_branch_approval_datatable') --}}
@endsection


<script>

</script>


@section('content')

    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
        {{-- <li class="breadcrumb-item"><a href="{{ route('rfo_approval_module.index') }}">Supplier application and Validation</a></li> --}}
        <li class="breadcrumb-item active">Supplier Dashboard</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Supplier Dashboard</h1>
    <!-- end page-header -->

    <!-- end row -->

    @include('RfoApprovalModule::list_of_main_branch');

    @include('RfoApprovalModule::list_of_supplier_and_merchants');

{{-- <div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                    class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                    class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                    class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">List of Approved Head Supplier</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>

    </div>
</div> --}}
@endsection