@extends('global.base')
@section('title', "Reversal Accounts Module")

{{--  import in this section your css files--}}
@section('page-css')
    {{-- Include Libraries CSS --}}
    @include('components.libraries.css-components')
    <style>
        table {
            border-collapse: collapse !important;
            border-radius: 0.5em !important;
            overflow: hidden !important;
        }
    </style>
@endsection

{{--  import in this section your javascript files  --}}
@section('page-js')    
    {{-- Include Libraries JS --}}
    @include('components.libraries.js-components')

    {{-- Include Script Components --}}
    @include('ReversalAccountsModule::components.script.js')

@endsection

@section('content')
<!-- STORE DATA OBJECT -->
<input type="hidden" id="session_reg" value="{{session('region')}}">
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="selectedProgramAmount" value="{{session('disburse_amount')}}">
<input type="hidden" id="selectedlinkview" value="">
<input type="hidden" id="supplier_name" value="">

<div class="row">
    <div class="col-md-8">
    <h1 class="page-header">Reversal Accounts Module <small>&nbsp; {{session('user_region_name').' - '.session('user_agency_shortname')}}</small></h1>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Reversal Accounts Module</li>
        </ol>   
    </div>
</div>

<div class="row m-t-10">
    <div class="col-lg-4">
        <div class="panel bg-light" >
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title text-black"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;List of Uploaded Files:&nbsp;&nbsp;</h4>
            </div>
            <div class="panel-body">                
                <table id="ReversalAccountsModuleList-datatable" class="table-striped" style="width: 100%;">
                    <thead style="background-color: #008a8a;color:#fff;"></thead>
                </table>                 
                <h4 class="ReversalAccountsModuleList_total pull-right">0.00</h4>
            </div>
            <div class="hljs-wrapper">
                
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel bg-light" >
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title text-black"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;File Details:&nbsp;&nbsp;</h4>
            </div>
            <div class="panel-body">                
                <table id="ReversalAccountsModuleDetails-datatable" class="table-striped" style="width: 100%;">
                    <thead style="background-color: #008a8a;color:#fff">
                        <tr>
                            <th>SELECT ALL <input type="checkbox" class="selectall_kyc_file_id"></th>
                            <th>RSBSA NO.</th>
                            <th>LAST NAME</th>
                            <th>FIRST NAME</th>
                            <th>MIDDLE NAME</th>
                            <th>PROVINCE</th>
                            <th>MUNICIPALITY</th>
                            <th>STREET/PUROK</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>
                </table><br>                 
                <button type="button" class="btn btn-outline-danger pull-right ReversalAccountsModuleDetails_btn" style="margin-top:-10px;"><i class="fa fa-times-circle"></i> Submit for Reversal Accounts</button>
            </div>
            <div class="hljs-wrapper">
                
            </div>
        </div>
    </div>
</div>

<div class="row m-t-10">
    <div class="col-lg-12">
        <div class="panel bg-light" >
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title text-black"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Generated Files for Reversal Accounts:&nbsp;&nbsp;</h4>
            </div>
            <div class="panel-body"> 
                <table id="GeneratedReversalAccountsModule-datatable" class="table-striped" style="width: 100%;">
                    <thead style="background-color: #008a8a;color:#fff"></thead>
                </table>                 
            </div>
            <div class="hljs-wrapper">
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="previewSeletedBeneficiariesModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">
                    <h4 class="modal-title" style="color: #000"><i class="fa fa-info-circle"></i> Preview</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #000">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="previewSeletedBeneficiaries-datatable" class="table-striped" style="width: 100%;">
                        <thead style="background-color: #008a8a;color:#fff"></thead>
                    </table><br>
                    <h4 class="previewSeletedBeneficiaries_Total pull-left">0.00</h4>
                    <button type="button" class="btn btn-outline-danger pull-right GenerateReversalAccountsModuleDetails_btn pull-right" style="margin-top:-10px;"><i class="fa fa-times-circle"></i> Generate Reversal Accounts</button>
                    {{--modal body end--}}
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="GeneratedFileDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">
                    <h4 class="modal-title" style="color: #000"><i class="fa fa-info-circle"></i> Generated File Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #000">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="GeneratedFileDetails-datatable" class="table-striped" style="width: 100%;">
                        <thead style="background-color: #008a8a;color:#fff"></thead>
                    </table>
                    <h4 class="GeneratedFileDetails_total pull-right">0.00</h4>
                    {{--modal body end--}}
                </div>
            </div>
        </form>
    </div>
</div>
    
@endsection