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
    @include('RfoApprovalModule::components.js.datatables.list_of_merchent_and_supplier_datatable')
@endsection


<script>

</script>


@section('content')

    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('rfo_approval_module.index') }}">Supplier application and Validation</a></li>
        <li class="breadcrumb-item active">Suppliers and Merchants</li>
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
        <h4 class="panel-title">OVERVIEW OF SUPPLIERS AND MERCHANTS</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        {{-- @include('RfoApprovalModule::list_of_supplier_and_merchants') --}}
        <table id="supps_and_merchs_datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th> SUPPLIER NAME </th>
                    <th> ADDRESS </th>
                    <th> MAIN BRANCH </th>
                    <th> ACCOUNT NO. </th>
                    <th> EMAIL ACCOUNT </th>
                    <th> CONTACT NO. </th>
                    <th> ACTION </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
        </table>

        <!-- #update_profile_modal -->
        <div class="modal fade" id="update_profile_modal">
            <div class="modal-dialog modal-lg">
                <form id="update_profile_form" method="PUT" route="">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <span class="error_form"></span>
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#34c2fa;">
                            <h4 class="modal-title" style="color: white;">Profile Update: 
                            <br> 
                            <span class="update_role" style="font-size: 14px;">Role: <span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div id="update_supplier_profile_modal_body" class="modal-body">
                            <!-- modal body start -->
                                <!-- Content here -->
                            <!-- modal body end -->
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" id="" class="btn btn-outline-success">UPDATE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- #view_current_profile_modal -->
        <div class="modal fade" id="view_current_profile_modal">
            <div class="modal-dialog modal-lg">
                <form id="current_profile_form" method="" route="">

                    <span class="error_form"></span>

                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#34c2fa;">
                            <h4 class="modal-title" style="color: white;">View Profile: 
                            <br> 
                            <span class="view_role" style="font-size: 14px;">Role: <span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div id="current_profile_modal_body" class="modal-body">
                            <!-- modal body start -->
                                <!-- Content here -->
                            <!-- modal body end -->
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 100px; margin-left: 0.5">
    <h4 style="font-size: 21px; color: rgb(0, 118, 228)"> Also view </h3>
</div>

<div class="row also_view_card_row">
    <div class="card also_view_card">
        <a href="{{ route('rfo_approval_module.account_activation') }}" class="cards_link_custom"> 
            <img src="https://image.freepik.com/free-vector/phone-user-activating-account-with-fingerprint-smartphone-screen-biometric-identity_74855-15499.jpg"
                alt="..." class="card-img-top">
            <div class="card-body">
                <h4 class="card-title" style="font-size: 20px; text-align:center">Head Supplier / Supplier Branch Account Activation</h4>
                <span class="card-text" style="font-size: 14px;"> View list of request for account activation </span>
            </div>
        </a>
    </div>

    <div class="card also_view_card">
        <a href="{{ route('rfo_approval_module.main_branch_approval') }}" class="cards_link_custom"> 
        <img src="https://image.freepik.com/free-vector/people-putting-puzzle-pieces-together_52683-28610.jpg"
            alt="..." class="card-img-top">
        <div class="card-body">
            <h4 class="card-title" style="font-size: 20px; text-align:center">Overview of Head Supplier</h4>
            <span class="card-text" style="font-size: 14px;"> View list of supplier main branch </span>
        </div>
        </a>
    </div>
</div>
@endsection