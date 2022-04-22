@extends('global.base')
@section('title', "Submitted Text Files")

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
    @include('SubmittedPayoutFiles::components.script.js')
    
    

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
            <h1 class="page-header">Submitted Text Files <small>&nbsp; {{session('user_region_name')}}</small></h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Submitted Text Files</li>
        </ol>   
    </div>
</div>
<div>


</div>

<div class="row">
    <div class="col-md-3"><br>
        <div class="row">
            <div class="col-lg-12 col-md-6 m-t-10">
                <center><img class="result-image" src="assets/img/product/side_4.gif" height="auto" width="100%"/></center>   
            </div>
            <div class="col-lg-12 col-md-6 m-t-10">
                <div class="widget widget-stats" style="background-color: #EBF2F8 !important;">
                    <!-- <div class="stats-icon"><i class="fa fa-"></i></div> -->
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive text-inverse"></i></div>
                    <div class="stats-info">
                    <a href="javascript:void(0)" class="d-flex justify-content-left f-s-12 btn_CompletedPayoutTextfiles"><h4 class="text-inverse">Total Completed Payout Textfiles</h4></a>
                        <div class="stats-number amt_Total_Completed_Payout_Textfiles text-inverse">₱0.00</div>	
                        <div class="text-inverse">
                            <small class="">Total Vouchers: </small>
                            <small class="cnt_Total_Completed_Payout_Textfiles ">0</small>
                        </div>
                    </div>
                </div>
            </div>               
        </div> 
    </div>
    <div class="col-md-9"><br><br>
        <div class="panel panel-info" data-sortable-id="index-6">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                </div>
                <h4 class="panel-title">
                    <i class="fa fa-info-circle"></i>&nbsp;&nbsp;List of Payout Textfile
                </h4>
            </div>
            <div class="panel-body p-t-0" style="background-color: #EBF2F8 !important;"><br>
                <div class="table-responsive">
                    <table id="SubmittedPayoutFilesList-datatable" class="" style="width: 100%;">
                        <thead></thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CompletedPayoutTextfilesHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-header" style="background-color: #fff">
                    <h4 class="modal-title" style="color: #000"><i class="fa fa-info-circle"></i> List of Completed Payout Textfile</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #000">×</button>
                </div>
                <div class="modal-body" style="background-color: transparent;">
                    {{--modal body start--}}
                    <table id="CompletedPayoutTextfilesHistory-Datatable" class="table-striped nowrap" style="width: 100%">                                                       
                        <thead></thead>
                    </table>
                    {{--modal body end--}}
                    
                </div>
            </div>
        </form>
    </div>
</div>

@endsection