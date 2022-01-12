@extends('global.base')
@section('title', "Batch Payout")

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
    @include('BatchPayout::components.script.js')


@endsection

@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- MAIN VIEW HEADER -->

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">

<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Batch Payout</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Batch Payout</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-line fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL CREATED BATCH PAYOUT</div>
                <div class="stats-number BatchPayout_totalamt">0.00</div>
            </div>
        </div> 
    </div>
</div>

<!-- MAIN VIEW CONTENT -->
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-success btnCreateBatch btnload"><i class="fa fa-plus"><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i></i> Create Batch</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Batch Details:</h4>
    </div>
    <div class="panel-body">
        <table id="BatchPayout-datatable" class="table table-striped display nowrap" style="width: 100%;">            
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>

<!-- MODALS -->

<!-- MODAL FOR CREATION OF BATCH -->
<div class="modal fade" id="CreateBatchModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Create Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}                            
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <label class="control-label">Description: <span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control txtdescription " rows="3" placeholder="Enter Description" required></textarea>
                                        </div>
                                    </div>                                            
                                </form>
                            </div>
                        </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a id="AddBTN" href="javascript:;" class="btn btn-success btnSubmitBatch"><i class="fa fa-check-circle"><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TO EDIT EXISTING SELECTED BATCH DATA -->
<div class="modal fade" id="EditBatchPayoutModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Edit Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <input type="hidden" class="edit_txtbatch_id">
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsgedit" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <label class="control-label">Description: <span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control edit_txtdescription" rows="3" placeholder="Enter Description" required></textarea>
                                        </div>
                                    </div>                                            
                                </form>
                            </div>
                        </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a id="AddBTN" href="javascript:;" class="btn btn-success btnUpdateBatch"><i class="fa fa-check-circle"><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i></i> Update</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection