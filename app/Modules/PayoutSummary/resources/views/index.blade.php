@extends('global.base')
@section('title', "Payout Summary")

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
    @include('PayoutSummary::components.script.js')

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
            <h1 class="page-header">Payout Summary</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Payout Summary</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL PAYOUTS</div>
                <div class="stats-number totalpayoutsamt">0.00</div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Payout Details:</h4>
    </div>
    <div class="panel-body">
        <table id="PayoutSummaryList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>
        

<div class="modal fade bd-example-modal-lg" id="ViewPayoutSummaryDetailsModal" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Payout Summary Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                            <table id="PayoutSummaryDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a;"></thead>
                            </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary payoutsummarytotalamt" role="alert"></h3>
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
                        <div class="carousel-inner payoutsummaryattachmentsimgcontent"></div>
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