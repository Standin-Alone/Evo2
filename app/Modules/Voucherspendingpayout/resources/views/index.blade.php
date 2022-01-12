@extends('global.base')
@section('title', "Vouchers Pending Payout")

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
    @include('Voucherspendingpayout::components.script.js')

@endsection

@section('content')
<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">

<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Vouchers Pending Payout</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li> 
            <li class="breadcrumb-item"><a href="{{route('VoucherTrans.index')}}">Voucher Transaction Monitoring</a></li>
            <li class="breadcrumb-item active">Vouchers Pending Payout</li>
        </ol>   
    </div>
</div> 
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="note note-success">
            <div class="note-icon"><i class="fas fa-chart-pie"></i></div>
            <div class="note-content">
                <h4><b>Total Amount</b></h4>
                <h3><span class="totalvoucherpendingpayoutamt">₱ 0.00</span></h3>
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
        <h4 class="panel-title" style="font-weight:normal !important;">Voucher Details:</h4>
    </div>
    <div class="panel-body">
    <table id="VoucherPendingPayout-datatable" class="table table-striped display nowrap" style="width: 100%;">
        <thead style="background-color: #008a8a;"></thead>
    </table>
    </div>
</div>
<!-- end panel -->
@endsection