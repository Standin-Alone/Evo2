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
        <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Supplier Payout</li>
        </ol>   
    </div>
</div>

<div class="row">                              
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL CREATED PAYOUT</div>
                <div class="stats-number batchpayoutsum">0.00</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL PENDING PAYOUT</div>
                <div class="stats-number BatchPayout_PendingSum">0.00</div>
            </div>
        </div>
    </div>
</div>       

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-success SupplierPayout_btn_Create"><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i><i class="fa fa-plus"></i> Create Payout</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Payout Details:</h4>
    </div>
    <div class="panel-body">
        <table id="supplierpayout-datatable" class="display select table table-striped display nowrap" style="width: 100%;">            
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CreatePayoutModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Create Payout</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
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
                                        <label class="control-label">Batch Application Number: <span class="text-danger">*</span></label>
                                        <div class="row m-b-15">
                                            <div class="col-md-12">
                                                <select id="default_BatchPayout" class="form-control selectbatchpayout" name="BatchPayout" data-size="10" data-style="btn-white" value="{{ old('BatchPayout') }}">
                                                <option value="" selected>Select Batch Application Number</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="ClaimedVoucher-Datatable" class="table table-striped display nowrap" style="width: 100%;">                                                       
                                                    <thead style="background-color: #008a8a">
                                                        <tr>
                                                            <th scope="col" style="color: white">
                                                                <div class="checkbox checkbox-css">
                                                                    <input type="checkbox" id="cssCheckbox1" class="selectedvoucherall" name="select_all" value="1">
                                                                    <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                                                                </div>
                                                            </th>
                                                            <th scope="col" style="color: white">TRANSACTION DATE</th>
                                                            <th scope="col" style="color: white">REFERENCE NO.</th>
                                                            <th scope="col" style="color: white">COMMODITY</th>
                                                            <th scope="col" style="color: white">QUANTITY</th>
                                                            <th scope="col" style="color: white">AMOUNT</th>
                                                            <th scope="col" style="color: white">TOTAL AMOUNT</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <label class="pull-right">
                                            <div class="input-group mb-3 ">
                                                <div class="input-group-prepend">
                                                <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                                </div>
                                                <h3 class="alert alert-primary SupplierPayout_totalselectedamt" role="alert">0.00</h3>
                                            </div>
                                        </label>
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
                    <a href="javascript:;" class="btn btn-success btnCreate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Create</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CheckStatusModal" data-keyboard="false" data-backdrop="static">  
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Check Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <div class="container">
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


<div class="modal fade bd-example-modal-lg" id="ViewDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Batch Payout Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="viewbatchpayoutdetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary batchpayouttotalamt" role="alert"></h3>
                                    </div>
                                </label>   
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewHoldTransDetailsModal" data-keyboard="false" data-backdrop="static">
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
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <b><h5><i class='fa fa-exclamation-triangle'></i> Message: &nbsp;&nbsp;</h5></b>
                        <div>
                            <span class="HoldTransactionMsg"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="viewHoldTransactionDetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                            <label class="pull-right">
                                <div class="input-group mb-3 ">
                                    <div class="input-group-prepend">
                                    <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                    </div>
                                    <h3 class="alert alert-primary batchpayout_holdtrans_totalamt" role="alert"></h3>
                                </div>
                            </label>                                
                        </div>
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

<div class="modal fade" id="ViewHoldTransAttachmentsModal" data-keyboard="false" data-backdrop="static">
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
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection