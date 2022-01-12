@extends('global.base')
@section('title', "Submit Payouts")

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
    @include('SubmitPayouts::components.script.js')    

@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="SubmitPayouts_selectedbatches" value="0">
<input type="hidden" id="SubmitPayouts_selectedbatchid" value="0">
<input type="hidden" id="SubmitPayouts_selectedvoucherid" value="0">
<input type="hidden" id="SubmitPayouts_selectedamt" value="0">
<input type="hidden" id="selectedProgramId" value="">
<input type="hidden" id="download_filename" value="">
<a href="{{route('download.SubmitPayoutExcelFile')}}" class="SubmitPayout_downloadfile" style="display:none;"></a>
<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Submit Payout</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Submit Payout</li>
        </ol>   
    </div>
</div>
<!-- MAIN VIEW CONTENT -->
<div class="">
    <a href="javascript:;" class="btn btn-xs btn-info btnApprovedHistoryList"><i class="fa fa-archive"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Submitted Payout History</a>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title btn btn-xs" style="font-weight:normal !important;">Payout Details:</h4>   
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-info SubmitPayout_btnGenerateEXCEL"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Generate Payout Excel</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
                 
    </div>
    <div class="panel-body">
        <table id="SubmitPayoutGeneratedList-datatable" class="table table-striped nowrap" style="width: 100%;">            
            <thead style=" color:#000"></thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white">Submitted Payout History</h4>
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

<div class="modal fade bd-example-modal-lg" id="selectPayoutExportModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white">Payout List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <div class="row m-b-15">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger errormsg_generateexcel" role="alert" style="display: none;">
                                        </div>
                                    </div>
                                </div> 
                                    <table id="SubmitPayoutsList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                                        <thead style="background-color: #008a8a">
                                            <tr>
                                                <th scope="col" style="color: white">
                                                    <div class="checkbox checkbox-css">
                                                        <input type="checkbox" id="cssCheckbox1" class="selectedbatchall" name="select_all" value="1">
                                                        <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                                                    </div>
                                                </th>
                                                <th scope="col">TRANSACTION DATE</th>
                                                <th scope="col" style="color: white">APPLICATION NO.</th>
                                                <th scope="col" style="color: white">DESCRIPTION</th>
                                                <th scope="col" style="color: white">TOTAL AMOUNT</th>
                                            </tr>
                                        </thead>
                                    </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary SubmitPayouts_totalselectedamt" role="alert">0.00</h3>
                                    </div>
                                </label>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success SubmitPayout_btnSubmitSelectedBatch"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Generate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="SubmitPayoutPincodeModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Security</h4>
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
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <input type="email" class="form-control SubmitPayout_email" rows="3" placeholder="Enter Email" required>                                            
                                        </div>
                                    </div> 
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <input type="password" class="form-control SubmitPayout_password" rows="3" placeholder="Enter Password" required>
                                            
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
                    <a href="javascript:;" class="btn btn-success SubmitPayout_validate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon4 pull-left m-r-10" style="display: none;"></i> Validate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection