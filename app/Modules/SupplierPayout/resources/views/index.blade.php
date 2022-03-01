@extends('global.base')
@section('title', "Supplier Payout")

{{--  import in this section your css files--}}
@section('page-css')
    {{-- Include Libraries CSS --}}
    @include('components.libraries.css-components')
@endsection

{{--  import in this section your javascript files  --}}
@section('page-js')    
    {{-- Include Libraries JS --}}
    @include('components.libraries.js-components')
    
    {{-- Include Script Components --}}
    @include('SupplierPayout::components.script.js')


@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="totalvoucheramt" value="0">
<input type="hidden" id="selected_voucheramt" value="0">
<input type="hidden" id="selected_batchid" value="0">
<input type="hidden" id="selectedProgramId" value="">

<!-- PROGRAM SELECTION -->
<div class="row">

    <!-- PROGRAM DROPDOWN SELECTION -->
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Supplier Payout</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Supplier Payout</li>
        </ol>   
    </div>
</div>

<div class="row">                              
    <div class="col-lg-12 col-md-6">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-red">
                    <!-- <div class="stats-icon"><i class="fa fa-"></i></div> -->
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-bar text-black"></i></div>
                    <div class="stats-info">
                        <h4>TOTAL CLAIMED VOUCHERS</h4>
                        <div class="stats-number amt_total_claimed_voucher" style="text-shadow: 2px 2px 4px #000000;">₱0.00</div>	
                    </div>
                    <div class="stats-link">
                        <a href="javascript:void(0)" class="text-white btn_ViewTotalClaimedVoucherDetails">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-orange">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-pie"></i></div>
                    <div class="stats-info">
                        <h4>TOTAL PENDING PAYOUTS</h4>
                        <div class="stats-number amt_total_pending_payouts" style="text-shadow: 2px 2px 4px #000000;">₱0.00</div>	
                    </div>
                    <div class="stats-link">
                        <a href="javascript:void(0)" class="text-white btn_ViewTotalPendingPayoutsDetails">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-grey-darker">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-line"></i></div>
                    <div class="stats-info">
                        <h4>TOTAL APPROVED PAYOUTS</h4>
                        <div class="stats-number amt_total_approved_payouts" style="text-shadow: 2px 2px 4px #000000;">₱0.00</div>	
                    </div>
                    <div class="stats-link">
                        <a href="javascript:void(0)" class="text-white btn_ViewTotalApprovedPayoutsDetails">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-black-lighter">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-area"></i></div>
                    <div class="stats-info">
                        <h4>TOTAL RETURN VOUCHERS</h4>
                        <div class="stats-number amt_total_hold_vouchers" style="text-shadow: 2px 2px 4px #000000;">₱0.00</div>
                    </div>
                    <div class="stats-link">
                        <a href="javascript:void(0)" class="text-white btn_ViewTotalHoldVoucherDetails">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>


            <!-- <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-gradient-blue">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-right-left-alt text-black"></i></div>
                    <div class="stats-content">
                        <div class="stats-title f-s-14"><a href="javascript:void(0)" class="text-white btn_ViewTotalClaimedVoucherDetails">TOTAL CLAIMED VOUCHERS</a></div>
                        <div class="stats-number amt_total_claimed_voucher">₱0.00</div>
                    </div> -->
                    <!-- <div class="stats-progress progress">
                        <div class="progress-bar per_total_claimed_voucher" style="width: 0.01%;"></div>
                    </div> -->
                    <!-- <div class="stats-desc">Total Percentage (<span class="per_total_claimed_voucher">0.0%</span>)</div> -->
                    <!-- <div class="stats-desc pull-right"><a href="javascript:void(0)" class="text-white btn_ViewTotalClaimedVoucherDetails"><i class="fas fa-spinner fa-spin btnloadingIcon_1 pull-left m-r-10" style="display: none;"></i> View Details...</a></div> -->
                <!-- </div>
            </div> -->
            <!-- <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-gradient-green">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-pie text-black"></i></div>
                    <div class="stats-content">
                        <div class="stats-title f-s-14"><a href="javascript:void(0)" class="text-white btn_ViewTotalPendingPayoutsDetails">TOTAL PENDING PAYOUTS</a></div>
                        <div class="stats-number amt_total_pending_payouts">₱0.00</div>
                    </div> -->
                    <!-- <div class="stats-progress progress">
                        <div class="progress-bar per_total_pending_payouts" style="width: 0.01%;"></div>
                    </div> -->
                    <!-- <div class="stats-desc">Total Percentage (<span class="per_total_pending_payouts">0.0%</span>)</div> -->
                    <!-- <div class="stats-desc pull-right"><a href="javascript:void(0)" class="text-white btn_ViewTotalPendingPayoutsDetails"><i class="fas fa-spinner fa-spin btnloadingIcon_1 pull-left m-r-10" style="display: none;"></i> View Details...</a></div> -->
                <!-- </div>
            </div> -->
            <!-- <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-gradient-black">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-line text-white"></i></div>
                    <div class="stats-content">
                        <div class="stats-title f-s-14"><a href="javascript:void(0)" class="text-white btn_ViewTotalApprovedPayoutsDetails">TOTAL APPROVED PAYOUTS</a></div>
                        <div class="stats-number amt_total_approved_payouts">₱0.00</div>
                    </div> -->
                    <!-- <div class="stats-progress progress">
                        <div class="progress-bar per_total_approved_payouts" style="width: 0.01%;"></div>
                    </div> -->
                    <!-- <div class="stats-desc">Total Percentage (<span class="per_total_approved_payouts">0.0%</span>)</div> -->
                    <!-- <div class="stats-desc pull-right"><a href="javascript:void(0)" class="text-white btn_ViewTotalApprovedPayoutsDetails"><i class="fas fa-spinner fa-spin btnloadingIcon_1 pull-left m-r-10" style="display: none;"></i> View Details...</a></div> -->
                <!-- </div>
            </div> -->
            <!-- <div class="col-lg-3 col-md-6">
                <div class="widget widget-stats bg-gradient-purple">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-area text-black"></i></div>
                    <div class="stats-content">
                        <div class="stats-title f-s-14"><a href="javascript:void(0)" class="text-white btn_ViewTotalHoldVoucherDetails">TOTAL RETURN VOUCHERS</a></div>
                        <div class="stats-number amt_total_hold_vouchers">₱0.00</div>
                    </div> -->
                    <!-- <div class="stats-progress progress">
                        <div class="progress-bar per_total_hold_vouchers" style="width: 0.01%;"></div>
                    </div> -->
                    <!-- <div class="stats-desc">Total Percentage (<span class="per_total_hold_vouchers">0.0%</span>)</div> -->
                    <!-- <div class="stats-desc pull-right"><a href="javascript:void(0)" class="text-white btn_ViewTotalHoldVoucherDetails"><i class="fas fa-spinner fa-spin btnloadingIcon_1 pull-left m-r-10" style="display: none;"></i> View Details...</a></div> -->
                <!-- </div>
            </div> -->
        </div>        
    </div>
    <div class="col-lg-10 col-md-12"></div>
    <div class="col-lg-2 col-md-12">
            <a href="javascript:;" class="btn btn-md btn-success SupplierPayout_btn_Create" style="width:100%;">
            <span class="d-flex align-items-center text-start">
                <i class="fas fa-spinner fa-spin fa-3x me-3 btnloadingIcon pull-left m-r-10" style="display: none;"></i>
                <i class="fa fa-clone fa-3x me-3 text-black"></i>
            <span>
                <span class="d-block f-s-16" style="text-shadow: 2px 2px 4px #000000;">&nbsp;&nbsp;Create Batch Payout</span>
                <span class="d-block" style="font-size:13px !important; opacity: 0.5;">&nbsp;&nbsp;Batching of Claimed Vouchers</span>
                </span>
            </span>
        </a>        
    </div>    
</div> 

<div class="panel-body">
    <table id="supplierpayout-datatable" class="select" style="width: 100%;">            
        <thead></thead>
    </table>
</div>

<div class="modal fade bd-example-modal-lg" id="CreatePayoutModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <img class="result-image justify-content-center" src="assets/img/product/payout_1.png" height="auto" width="100%"/>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="background-color: transparent;margin-top:-130px;">
                    {{--modal body start--}}
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    @CSRF                                    
                                    <div class="row m-b-15">
                                        <div class="col-md-12 table-wrapper-scroll-y my-custom-scrollbar">  
                                            <div class="row">
                                                <div class="col-md-12">
                                                    
                                                    <table id="ClaimedVoucher-Datatable" class="table table-striped display nowrap" style="width: 100%;">                                                       
                                                        <thead style="background-color: #008a8a">
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <label class="pull-right">
                                                <div class="input-group mb-3 ">
                                                    <div class="input-group-prepend">
                                                    <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                                    </div>
                                                    <h3 class="alert alert-success SupplierPayout_totalselectedamt" role="alert">0.00</h3>
                                                </div>
                                            </label>
                                            <a href="javascript:;" class="btn btn-lg btn-success pull-left btnCreate" style="width:100%;">
                                                <span class="d-flex align-items-center text-start">
                                                <i class="fas fa-spinner fa-spin fa-3x me-3 btnloadingIcon1 pull-left m-r-10" style="display: none;"></i>
                                                    <i class="fa fa-check-circle fa-3x me-3 text-black"></i>
                                                <span>
                                                    <span class="d-block">&nbsp;&nbsp;Save Batch Payout</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    {{--modal body end--}}                    
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CreatePayout_AddVoucherModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <img class="result-image justify-content-center" src="assets/img/product/payout_1.png" height="auto" width="100%"/>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="background-color: transparent;margin-top:-130px;">
                    {{--modal body start--}}
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    @CSRF                                    
                                    <div class="row m-b-15">
                                        <div class="col-md-12 table-wrapper-scroll-y my-custom-scrollbar">  
                                            <div class="row">
                                                <div class="col-md-12">
                                                    
                                                    <table id="ClaimedVoucher_AddVoucher-Datatable" class="table table-striped display nowrap" style="width: 100%;">                                                       
                                                        <thead style="background-color: #008a8a">
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <label class="pull-right">
                                                <div class="input-group mb-3 ">
                                                    <div class="input-group-prepend">
                                                    <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                                    </div>
                                                    <h3 class="alert alert-success SupplierPayout_totalselectedamt" role="alert">0.00</h3>
                                                </div>
                                            </label>
                                            <a href="javascript:;" class="btn btn-lg btn-success pull-left btnCreate" style="width:100%;">
                                                <span class="d-flex align-items-center text-start">
                                                <i class="fas fa-spinner fa-spin fa-3x me-3 btnloadingIcon1 pull-left m-r-10" style="display: none;"></i>
                                                    <i class="fa fa-check-circle fa-3x me-3 text-black"></i>
                                                <span>
                                                    <span class="d-block">&nbsp;&nbsp;Add Voucher</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    {{--modal body end--}}                    
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CheckStatusModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <img class="result-image justify-content-center" src="assets/img/product/trace_1.png" height="auto" width="100%"/>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="margin-top:-90px;">
                    {{--modal body start--}}
                        <article class="card">
                            <div class="card-body">
                                <article class="">
                                    <div class="row">
                                        <div class="col"> <strong>TRANSACTION DATE:</strong> <br> <span class="status_transac_date"></span> </div>
                                        <div class="col"> <strong>APPLICATION NO.:</strong> <br> <span class="status_application_number"></span> </div>                                    
                                        <div class="col"> <strong>DESCRIPTION:</strong> <br> <span class="status_description"></span> </div>
                                        <div class="col"> <strong>AMOUNT:</strong> <br> <span class="status_amount"></span> </div>
                                    </div>
                                </article>
                                <div class="track">
                                    <div class="step createdpayout"> <span class="icon"> <i class="fa fa-users"></i> </span> <span class="text">Created Payout</span> </div>
                                    <div class="step submittedpayout"> <span class="icon"> <i class="fa fa-file-alt"></i> </span> <span class="text"> Submitted Payout</span> </div>
                                    <div class="step approvalprocess"> <span class="icon"> <i class="fa fa-cogs"></i> </span> <span class="text"> Approval Process</span> </div>
                                    <div class="step payoutcomplete"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Complete</span> </div>
                                </div>
                                <hr>
                            </div>
                        </article>
                    {{--modal body end--}}
                    
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="ViewDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <img class="result-image justify-content-center" src="assets/img/product/viewing_1.png" height="auto" width="100%"/>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="background-color: transparent;margin-top:-80px;">
                    {{--modal body start--}}
                    <table id="viewbatchpayoutdetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                        <label class="pull-right">
                            <div class="input-group mb-3 ">
                                <div class="input-group-prepend">
                                <span style="margin-top: 20px;">Total Amount:&nbsp;&nbsp;</span>
                                </div>
                                <h3 class="alert alert-success batchpayouttotalamt" role="alert" style="margin-top: 20px;"></h3>
                            </div>
                        </label>  
                    {{--modal body end--}}
                    
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewHoldTransDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <img class="result-image justify-content-center" src="assets/img/product/holdtrans_1.png" height="auto" width="100%"/>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>                
                <div class="modal-body" style="background-color: transparent;margin-top:-130px;">
                    <!-- <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-0">
                        <div class="d-flex">
                            <i class="fa fa-info-circle fa-2x me-1"> </i>
                            <div class="mb-0 ps-2">
                                <span class="HoldTransactionMsg m-l-10 m-t-10"></span>
                            </div>
                        </div>
                    </div><br> -->
                    {{--modal body start--}}
                    
                    <!-- <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <b><h5><i class='fa fa-exclamation-triangle'></i> Message: &nbsp;&nbsp;</h5></b>
                        <div>
                           
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12">
                            <table id="viewHoldTransactionDetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                            <label class="pull-right m-t-10">
                                <div class="input-group mb-3 ">
                                    <div class="input-group-prepend">
                                    <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                    </div>
                                    <h3 class="alert alert-success batchpayout_holdtrans_totalamt" role="alert"></h3>
                                </div>
                            </label>                                
                        </div>
                        <!-- <div class="col-lg-12 col-md-12">
                            <a href="javascript:;" class="btn btn-lg btn-success SupplierPayout_btn_Create" style="width:100%;">
                                <span class="">
                                <i class="fas fa-spinner fa-spin fa-3x me-3 btnloadingIcon pull-left m-r-10" style="display: none;"></i>
                                    <i class="fa fa-cubes fa-3x me-3 text-black"></i>
                                <span>
                                    <span class="d-block">&nbsp;&nbsp;Update Transaction</span>
                                    <span class="d-block" style="font-size:13px !important; opacity: 0.5;">Review and Update the error transactions</span>
                                    </span>
                                </span>
                            </a>        
                        </div>    -->
                    </div>
                    {{--modal body end--}}
                    
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ViewHoldTransAttachmentsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <div class="col-md-6"></div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>     
                <div class="modal-body" style="background-color: #fff;">
                    {{--modal body start--}}

                    <div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
                        <div class="carousel-inner holdtransattachmentsimgcontent"></div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>                     
                    
                    {{--modal body end--}}
                </div>
                <!-- <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div> -->
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewTotalDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">                    
                    <img class="result-image justify-content-center" src="assets/img/product/viewing_1.png" height="auto" width="100%"/>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>                
                <div class="modal-body" style="background-color: transparent;margin-top:-110px;">
                    {{--modal body start--}}
                    <div class="row">
                        <div class="col-md-12">
                            <table id="ViewTotalDetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                            <label class="pull-right">
                                <div class="input-group mb-3 ">
                                    <div class="input-group-prepend">
                                    <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                    </div>
                                    <h3 class="alert alert-success ViewTotalDetails_totalamt" role="alert">0.00</h3>
                                </div>
                            </label>                                
                        </div>
                    </div>
                    {{--modal body end--}}
                    
                </div>
            </div>
        </form>
    </div>
</div>
@endsection