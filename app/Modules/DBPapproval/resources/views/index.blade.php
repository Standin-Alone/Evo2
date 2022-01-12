@extends('global.base')
@section('title', "DBP Approval")

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
    @include('DBPapproval::components.script.js')
    
    

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
<a href="{{route('download.DBPBatchDownload')}}" class="dbpapproval_downloadfile" style="display:none;"></a>
<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">DBP Approval</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">DBP Approval</li>
        </ol>   
    </div>
</div>
<!-- MAIN VIEW CONTENT -->
<div>
    <a href="javascript:;" class="btn btn-xs btn-info btnApprovedHistoryList"><i class="fa fa-archive"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> DBP Approved Batch History</a>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">    
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-info btnDBPapproval"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Approve DBP File</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title btn btn-xs" style="font-weight:normal !important;">DBP File Details:</h4> 
    </div>
    <div class="panel-body">
        <div class="alert alert-danger errormsg_dbpapproval" role="alert" style="display: none;"></div>
        <table id="DBPapprovalList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a">
                <tr>
                    <th scope="col" style="color: white">
                        <div class="checkbox checkbox-css">
                            <input type="checkbox" id="cssCheckbox1" class="selectedbatchall" name="select_all" value="1">
                            <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                        </div>
                    </th>
                    <th scope="col" style="color: white">TRANSACTION DATE</th>
                    <th scope="col" style="color: white">FILE NAME</th>
                    <th scope="col" style="color: white">TOTAL AMOUNT</th>
                    <th scope="col" style="color: white">TOTAL RECORDS</th>
                    <th scope="col" style="color: white">ACTION</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved DBP History</h4>
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

<div class="modal fade" id="dbpapprovalPincodeModal" data-keyboard="false" data-backdrop="static">
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
                                        <input type="email" class="form-control dbpapproval_email" rows="3" placeholder="Enter Email" required>                                            
                                        </div>
                                    </div> 
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <input type="password" class="form-control dbpapproval_password" rows="3" placeholder="Enter Password" required>
                                            
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
                    <a href="javascript:;" class="btn btn-success dbpapproval_validate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon4 pull-left m-r-10" style="display: none;"></i> Validate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection