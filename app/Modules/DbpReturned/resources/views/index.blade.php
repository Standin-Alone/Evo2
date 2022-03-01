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

    {{-- Include external CS components --}}
    @include('DbpReturned::components.css.css')
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
    @include('DbpReturned::components.js.js')

    @include('DbpReturned::components.js.datatable.list_of_return_disbursement_file')
@endsection


<script>

</script>


@section('content')

    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
        <li class="breadcrumb-item active">List of Return Disbursement Files</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    {{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}
    <!-- end page-header -->

    <div class="row mt-5">
        {{-- @include('ReportModule::components.dashboard_cards.paid_db_card') --}}
    </div>
    <!-- end row -->

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                        class="fa fa-expand mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                        class="fa fa-redo mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                        class="fa fa-minus mt-1"></i></a>
            </div>
            <h4 class="panel-title">List of Return Disbursement Files</h4>
        </div>
        <div class="panel-body">
            <br>
            <br>
            <table id="list_of_return_disbursement_files_datatable" class="table table-bordered text-center" style="width:100%">
                <thead class="table-header">
                    <tr>
                        <th>RSBSA NO.</th>
                        <th>FILE NAME</th>
                        <th>ACCOUNT NUMBER</th>
                        <th>FIRST NAME</th>
                        <th>MIDDLE NAME</th>
                        <th>LAST NAME</th>
                        <th>PROVINCEE</th>
                        <th>CITY MUNICIPALITY</th>
                        <th>DBP STATUS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>

    {{-- <div class="row rfo_approval_module_card_row" style="margin-top: 100px; margin-right: 100px; margin-left: 100px;">
        <div class="card rfo_approval_module_card">
            <a href="{{ route('rfo_approval_module.account_activation') }}" class="cards_link_custom"> 
                <img src="https://image.freepik.com/free-vector/phone-user-activating-account-with-fingerprint-smartphone-screen-biometric-identity_74855-15499.jpg"
                    alt="..." class="card-img-top">
                <div class="card-body">
                    <h4 class="card-title" style="font-size: 20px; text-align:center">Head Supplier / Supplier Branch Account Activation</h4>
                    <span class="card-text" style="font-size: 14px;"> View list of request for account activation </span>
                </div>
            </a>
        </div>
        
        <div class="card rfo_approval_module_card">
            <a href="{{ route('rfo_approval_module.main_branch_approval') }}" class="cards_link_custom"> 
            <img src="https://image.freepik.com/free-vector/people-putting-puzzle-pieces-together_52683-28610.jpg"
                alt="..." class="card-img-top">
            <div class="card-body">
                <h4 class="card-title" style="font-size: 20px; text-align:center">Overview of Head Supplier</h4>
                <span class="card-text" style="font-size: 14px;"> View list of supplier main branch </span>
            </div>
            </a>
        </div>
        
        <div class="card rfo_approval_module_card" >
            <a href="{{ route('rfo_approval_module.list_of_merchs_and_supps') }}" class="cards_link_custom"> 
            <img src="https://image.freepik.com/free-vector/worldwide-shipping-service-international-distribution-collaborative-logistics-supply-chain-partners-freight-cost-optimization-concept-pinkish-coral-bluevector-isolated-illustration_335657-1757.jpg"
                alt="..." class="card-img-top">
            <div class="card-body">
                <h4 class="card-title" style="font-size: 20px; text-align:center">Overview of Suppliers and Merchants</h4>
                <span class="card-text" style="font-size: 14px;"> View list of Supplier and Merchants </span>
            </div>
            </a>
        </div>
    </div> --}}
@endsection
