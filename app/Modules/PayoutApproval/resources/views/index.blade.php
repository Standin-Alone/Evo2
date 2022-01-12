@extends('global.base')
@section('title', "Payout Approval")

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
    @include('PayoutApproval::components.script.js')

    
@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="PayoutAppval_selectedbatches" value="0">
<input type="hidden" id="PayoutAppval_selectedbatchid" value="0">
<input type="hidden" id="PayoutAppval_selectedvoucherid" value="0">
<input type="hidden" id="PayoutAppval_selectedamt" value="0">
<input type="hidden" id="selectedProgramId" value="">
<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Payout Approval</h1>                                  
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Payout Approval</li>
        </ol>   
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-pie fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">APPROVED PAYOUT HISTORY</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkApprovedPayoutHistory" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon5 pull-left m-r-10" style="display: none;"></i> View Details</a>                
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-bar fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">HOLD VOUCHER HISTORY</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkHoldVoucherHistory" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon6 pull-left m-r-10" style="display: none;"></i> View Details</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-info btnApprovalBatchPayout"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Approve Payout</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title btn btn-xs" style="font-weight:normal !important;">Payout Details:</h4> 
    </div>    
    <div class="panel-body">
        <div class="alert alert-danger errormsg_approval" role="alert" style="display: none;"></div>
        <table id="PayoutApprovalList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a">
                <tr>
                    <th></th>
                    <th scope="col" style="color: white">
                        <div class="checkbox checkbox-css">
                            <input type="checkbox" id="cssCheckbox1" class="selectedbatchall" name="select_all" value="1">
                            <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                        </div>
                    </th>
                    <th scope="col" style="color: white">TRANSACTION DATE</th>
                    <th scope="col" style="color: white">APPLICATION NO.</th>
                    <th scope="col" style="color: white">DESCRIPTION</th>
                    <th scope="col" style="color: white">TOTAL AMOUNT</th>
                    <th scope="col" style="color: white">ACTION</th>                                
                </tr>
            </thead>
        </table>
        <label class="pull-right">
            <div class="input-group mb-3 ">
                <div class="input-group-prepend">
                <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                </div>
                <h3 class="alert alert-primary PayoutApproval_totalselectedamt" role="alert">0.00</h3>
            </div>
        </label>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="ApprovedHistoryList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Batch Payout Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff; ">
                    {{--modal body start--}}
                    <table id="viewPayoutApprovalDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewApprovedHistoryDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved Payout Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                            <table id="viewApprovalHistoryDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="HoldTransactionHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Hold Transaction Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                            <table id="viewHolTransactionDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary holdtransactiontotalamt" role="alert"></h3>
                                    </div>
                                </label>

                                
                                
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ViewAttachmentsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-file-image"></i> Attachments</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff;">
                    {{--modal body start--}}

                    <div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
                        <div class="carousel-inner voucherattachmentsimg"></div>
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
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="AddRemarksModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Note/Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control PayoutAppval_holdremarks " rows="3" placeholder="Enter Description" required></textarea>
                                            <span class="text-danger errormsg_holdremarks"></span>
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success PayoutApproval_submitholdremarks"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="AddDescriptionModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Description</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <textarea class="form-control PayoutAppval_appdescription " rows="3" placeholder="Enter Description" required></textarea>
                                            <span class="text-danger errormsg_appdescription"></span>
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success PayoutApproval_submitappdescription"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection