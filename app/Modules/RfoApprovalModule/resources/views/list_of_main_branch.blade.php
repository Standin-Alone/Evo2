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
    @include('RfoApprovalModule::components.js.datatables.list_of_request_for_main_branch_approval_datatable')
@endsection


<script>

</script>


@section('content')

    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('rfo_approval_module.index') }}">Supplier application and Validation</a></li>
        <li class="breadcrumb-item active">Main Branch Approval</li>
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
        <h4 class="panel-title">Overview of Head Supplier / Supplier Branch</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        {{-- @include('RfoApprovalModule::main_branch_approval') --}}

        <table id="main_branch_and_supplier_branch_datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>GROUP NAME</th>
                    <th>ADDRESS</th>
                    <th>CREATED AGENCY</th>
                    <th>CREATED BY FULLNAME</th>
                    <th>APPROVED BY FULLNAME</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
        
        {{-- Update modal --}}
        <div class="modal fade" id="update_main_branch_approval_status_modal">
            <div class="modal-dialog modal-lg">
                <form id="update_main_branch_approval_form" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <span class="error_form"></span>
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#21a8dd;">
                            <h4 class="modal-title" style="color: white;">UPDATE STATUS <br> <span class="group_name" style="font-size: 14px;">GROUP NAME: <span> </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                style="color: white">×</button>
                        </div>
                        <div id="update_main_branch_modal_body" class="modal-body">
                            <div class="col-lg-12">
        
                                <div id="show_alert_with_apply_button_for_" class="show_alert_with_apply_button mt-3"></div>
        
                                <table id="preview_table" class="table table-bordered text-center" style="width:100%;">
                                    <thead class="table-header">
                                        <tr>
                                            <th style="color: white !important; width: 100px !important; font-size: 13px;"> STATUS </th>
                                            <th style="color: white !important; width: 100px !important; font-size: 13px;"> ACTIVE / INACTIVE </th>
                                            <th style="color: white !important; width: 100px !important; font-size: 13px;"> BLOCK / UN-BLOCK </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color: white !important; width: 100px !important;"> 
                                                <div class="form-group">  
                                                    <h4 class="approval_status_badge"></h4>
                                                </div> 
                                            </td>
                                            <td style="color: white !important; width: 100px !important;"> 
                                                <div class="form-group btn_toggle_active_and_inactive">  
                                                    <input type="checkbox" id="main_branch_approve_and_disapprove" name="checkbox3" class="cm-toggle main_branch_approve_and_disapprove">
                                                </div> 
                                            </td>
                                            <td style="color: white !important; width: 100px !important;"> 
                                                <div class="form-group"> 
                                                    <input type="checkbox" id="main_branch_onhold_and_unhold" name="checkbox4" class="cm-toggle2 main_branch_onhold_and_unhold" >
                                                </div> 
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            {{-- <button type="submit" class="btn btn-outline-success">SAVE</button> --}}
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
    
    <div class="card also_view_card" >
        <a href="{{ route('rfo_approval_module.list_of_merchs_and_supps') }}" class="cards_link_custom"> 
        <img src="https://image.freepik.com/free-vector/worldwide-shipping-service-international-distribution-collaborative-logistics-supply-chain-partners-freight-cost-optimization-concept-pinkish-coral-bluevector-isolated-illustration_335657-1757.jpg"
            alt="..." class="card-img-top">
        <div class="card-body">
            <h4 class="card-title" style="font-size: 20px; text-align:center">Overview of Suppliers and Merchants </h4>
            <span class="card-text" style="font-size: 14px;"> View list of Supplier and Merchants </span>
        </div>
        </a>
    </div>
</div>
@endsection