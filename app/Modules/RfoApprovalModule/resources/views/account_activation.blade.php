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
    @include('RfoApprovalModule::components.css.account_guidelines_css')
    @include('RfoApprovalModule::components.css.css')

    <link href="{{ url('assets/plugins/jquery-smart-wizard/src/css/smart_wizard.css') }}" rel="stylesheet" />
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

    <script src="{{ url('assets/plugins/jquery-smart-wizard/src/js/jquery.smartWizard.js') }}"></script>
	<script src="{{ url('assets/js/demo/form-wizards.demo.min.js') }}"></script>

    {{-- Date Range Picker --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

    {{-- Select2 plugin --}}
    <script src="{{ url('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    {{-- Include external JS components --}}
    @include('RfoApprovalModule::components.js.js')

    @include('RfoApprovalModule::components.js.add_program_permission_modal_js')

    {{-- Datatables --}}
    @include('RfoApprovalModule::components.js.datatables.setup_program_user_datatable_js')
    @include('RfoApprovalModule::components.js.datatables.list_of_account_activation_datatable_js')
    @include('RfoApprovalModule::components.js.datatables.list_of_approved_accounts_datatable_js')
    <script>
		$(document).ready(function() {
			FormWizard.init();
		});
	</script>
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
    
    <button type="button" id="account_activation_guidelines_btn" name="account_activation_guidelines_btn" class="btn btn-xs btn-info" data-toggle="modal"
        data-target="#account_activcation_guidelines_modal">
        <i class="fa fa-info-circle fa-lg"></i> ACCOUNT ACTIVATION GUIDELINES
    </button>
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

      <!-- The Modal -->
    <div class="modal fade" id="account_activcation_guidelines_modal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header" style="background-color:#21a8dd;">
                <h4 class="modal-title" style="color: white;">ACCOUNT ACTIVATION GUIDELINES</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body guidelines_modal_body">
                    <img class="center_image" src="{{ url('/assets/img/images/steps.png') }}" width="250px;" height="250px;" alt="steps">

                        <!-- begin wizard -->
                        <div id="wizard">
                            <!-- begin wizard-step -->
                            <ul>
                                <li class="col-md-4 col-sm-4 col-6">
                                    <a href="#step-1">
                                        <span class="number">1</span> 
                                        <span class="info text-ellipsis">
                                            PROGRAM PERMISSION
                                            {{-- <small class="text-ellipsis">Name, Address, IC No and DOB</small> --}}
                                        </span>
                                    </a>
                                </li>
                                <li class="col-md-4 col-sm-4 col-6">
                                    <a href="#step-2">
                                        <span class="number">2</span> 
                                        <span class="info text-ellipsis">
                                            REQUIREMENTS
                                            {{-- <small class="text-ellipsis">Email and phone no. is required</small> --}}
                                        </span>
                                    </a>
                                </li>
                                <li class="col-md-4 col-sm-4 col-6">
                                    <a href="#step-3">
                                        <span class="number">3</span>
                                        <span class="info text-ellipsis">
                                            APPROVED ACCOUNTS
                                            {{-- <small class="text-ellipsis">Enter your username and password</small> --}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <!-- end wizard-step -->
                            <!-- begin wizard-content -->
                            <div>
                                <!-- begin step-1 -->
                                <div id="step-1">
                                    <div class="jumbotron m-b-0 text-center">
                                        <h4 class="text-inverse"> <b>SETUP PROGRAM FOR USER</b> </h4>
                                        <p class="m-b-30 f-s-16">In Step 1, select active program for head supplier or supplier branch.</p>
                                    </div>
                                </div>
                                <!-- end step-1 -->
                                <!-- begin step-2 -->
                                <div id="step-2">
                                    <div class="jumbotron m-b-0 text-center">
                                        <h4 class="text-inverse"> <b>REQUIREMENT CHECKLIST FOR ACCOUNT APPROVAL</b> </h4>
                                        <p class="m-b-30 f-s-16">In Step 2, The RFO Program Focal shall review the listed requirements of the Head Supplier or Supplier Branch.
                                            After reviewing requirements, the RFO Program Focal may active the supplier by pressing the ACTIVE button. Once the RFO Program Focal click the ACTIVE button. 
                                            The system will auto generate the Certificate of Accreditation with MRN QR code and will be send via email to the Head Supplier or Supplier Branch.
                                        </p>
                                    </div>
                                </div>
                                <!-- end step-2 -->
                                <!-- begin step-3 -->
                                <div id="step-3">
                                    <div class="jumbotron m-b-0 text-center">
                                        <h4 class="text-inverse"> <b>APPROVED ACCOUNTS</b> </h4>
                                        <p class="m-b-30 f-s-16">In Step 3, After the reviewing, approving and activation of account. It will generate a list of approved accounts on the 3rd table </p>
                                    </div>
                                </div>
                                <!-- end step-3 -->
                            </div>
                            <!-- end wizard-content -->
                        </div>
                        <!-- end wizard -->

                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                </div>
            
            </div>
        </div>
    </div>
@endsection
