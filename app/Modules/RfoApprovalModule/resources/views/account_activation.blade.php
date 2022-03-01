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

    @include('RfoApprovalModule::components.js.add_role_and_program_modal')

    {{-- Datatables --}}
    @include('RfoApprovalModule::components.js.datatables.user_account_datatable')
    @include('RfoApprovalModule::components.js.datatables.list_of_account_activation_datatable')
    @include('RfoApprovalModule::components.js.datatables.list_of_approved_accounts_datatable')
@endsection


<script>

</script>


@section('content')

    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('rfo_approval_module.index') }}">Supplier application and Validation</a></li>
        <li class="breadcrumb-item active">Account Approval</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    {{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}
    <!-- end page-header -->
    <div class="row mt-5">
        {{-- @include('ReportModule::components.dashboard_cards.paid_db_card') --}}
    </div>
    <!-- end row -->

    {{-- <div class="panel-body panel-form">
    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span> <i class="fa fa-caret-down"></i>
    </div>
    </div> --}}

    {{--  --}}
    @include('RfoApprovalModule::account_activation_folder.setup_role_and_program_for_user')

    {{-- List of for checking Accounts --}}
    @include('RfoApprovalModule::account_activation_folder.list_of_for_checking_accounts')

    {{-- List of Approved Accounts --}}
    @include('RfoApprovalModule::account_activation_folder.list_of_appoved_accounts')

    <div class="row" style="margin-top: 100px; margin-left: 0.5">
        <h4 style="font-size: 21px; color: rgb(0, 118, 228)"> Also view </h3>
    </div>

    <div class="row also_view_card_row">
        <div class="card also_view_card">
            <a href="{{ route('rfo_approval_module.main_branch_approval') }}" class="cards_link_custom">
                <img src="https://image.freepik.com/free-vector/people-putting-puzzle-pieces-together_52683-28610.jpg"
                    alt="..." class="card-img-top">
                <div class="card-body">
                    <h4 class="card-title" style="font-size: 20px; text-align:center">Overview of Head Supplier Branch</h4>
                    <span class="card-text" style="font-size: 14px;"> View list of supplier main branch </span>
                </div>
            </a>
        </div>

        <div class="card also_view_card">
            <a href="{{ route('rfo_approval_module.list_of_merchs_and_supps') }}" class="cards_link_custom">
                <img src="https://image.freepik.com/free-vector/worldwide-shipping-service-international-distribution-collaborative-logistics-supply-chain-partners-freight-cost-optimization-concept-pinkish-coral-bluevector-isolated-illustration_335657-1757.jpg"
                    alt="..." class="card-img-top">
                <div class="card-body">
                    <h4 class="card-title" style="font-size: 20px; text-align:center">Overview of Suppliers and Merchants</h4>
                    <span class="card-text" style="font-size: 14px;"> View list of Supplier and Merchants </span>
                </div>
            </a>
        </div>
    </div>
@endsection
