@extends('global.base')
@section('title', "Cancellation Module")

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
    @include('CancellationModule::components.script.js')

@endsection

@section('content')
<!-- STORE DATA OBJECT -->
<input type="hidden" id="session_reg" value="{{session('region')}}">
<input type="hidden" id="selectedlinkview" value="">
<input type="hidden" id="supplier_name" value="">

<div class="row">
    <div class="col-md-8">
    <h1 class="page-header">Cancellation Module <small>&nbsp; {{session('reg_name')}}</small></h1>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Cancellation Module</li>
        </ol>   
    </div>
</div>
<!-- <div class="row">
    <div class="col-lg-10">         
    </div>
    <div class="col-lg-2">
        <a href="javascript:;" class="btn btn-lg btn-success btn_CancellationModule_add" style="width:100%;">
            <span class="d-flex align-items-center text-left">
            <i class="fas fa-spinner fa-spin fa-2x me-3 btnloadingIcon pull-left m-r-10" style="display: none;"></i>
                <i class="fa fa-folder-open fa-2x me-3 text-black"></i>
            <span>
                <span class="d-block">&nbsp;&nbsp;Add for Cancellation</span>
                </span>
            </span>
        </a> 
    </div> 
</div> -->

<div class="row m-t-10">
    <div class="col-lg-4">
        <div class="panel bg-light" >
            <!-- <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                
            </div> -->
            <div class="panel-body">
                <h4 class="panel-title text-black">List of Uploaded Files for Cancellation</h4>

                <table id="CancellationModuleList-datatable" class="table" style="width: 100%;">
                    <thead></thead>
                </table>                 
                <h4 class="CancellationModuleList_total pull-right">0.00</h4>
            </div>
            <div class="hljs-wrapper">
                
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel bg-light" >
            <!-- <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                
            </div> -->
            <div class="panel-body">
                <h4 class="panel-title text-black">File Details</h4>

                <table id="CancellationModuleDetails-datatable" class="table" style="width: 100%;">
                    <thead></thead>
                </table>                 
                <!-- <h4 class="CancellationModuleDetails_total pull-right">0.00</h4> -->
                <button class="btn btn-danger pull-right CancellationModuleDetails_btn" style="margin-top:-10px;"><i class="fa fa-times-circle text-white"></i> Submit for Cancellation</button>
            </div>
            <div class="hljs-wrapper">
                
            </div>
        </div>
    </div>
</div>

<div class="row m-t-10">
    <div class="col-lg-12">
        <div class="panel bg-light" >
            <!-- <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                
            </div> -->
            <div class="panel-body">
                <h4 class="panel-title text-black">Generated File for Cancellation</h4>

                <table id="GeneratedCancellationModule-datatable" class="table" style="width: 100%;">
                    <thead></thead>
                </table>                 
                <!-- <h4 class="GeneratedCancellationModule_total pull-right">0.00</h4> -->
            </div>
            <div class="hljs-wrapper">
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="GeneratedFileDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">
                    <h4 class="modal-title" style="color: #000"><i class="fa fa-info-circle"></i> Generated File Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #000">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="GeneratedFileDetails-datatable" class="table" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                    <h4 class="GeneratedFileDetails_total pull-right">0.00</h4>
                    {{--modal body end--}}
                </div>
            </div>
        </form>
    </div>
</div>
    
@endsection