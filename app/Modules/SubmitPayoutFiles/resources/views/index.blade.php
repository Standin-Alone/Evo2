@extends('global.base')
@section('title', "Submit Payout Files")

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
    @include('SubmitPayoutFiles::components.script.js')
    
    

@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">

<div class="row">
    <div class="col-md-6">
            <div class="input-group">
            <h1 class="page-header">Submit Payout File</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Submit Payout File</li>
        </ol>   
    </div>
</div>
<div>
    <a href="javascript:;" class="btn btn-xs btn-info btngeneratedtextfilehistory"><i class="fa fa-archive"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Generated Textfile History</a>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <!-- <a href="javascript:;" class="btn btn-xs btn-info btnGenerateKey"><i class="fa fa-file-alt"></i> Generate Payout Textfile</a> -->
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Payout file Details:</h4>
    </div>
    <div class="panel-body">
        <table id="SubmitPayoutFileList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="GeneratedtextfileHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Generated Texfile History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="GeneratedtextfileHistorylist-datatable" class="table table-striped display nowrap" style="width: 100%;">
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

@endsection