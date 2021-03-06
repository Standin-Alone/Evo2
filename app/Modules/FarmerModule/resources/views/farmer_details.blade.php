@extends('global.base')
@section('title', "Farmers")

{{--  import in this section your css files--}}
@section('page-css')
<link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
<link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
<link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

{{-- External CSS --}}
@include('FarmerModule::components.css.css')
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
<script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
<script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
<script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
<script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
<script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}"></script>

{{-- External JS --}}
@include('FarmerModule::components.js.js')

{{-- Interventions and Location row details on datatable --}}
@include('FarmerModule::components.js.datatables.intervention_datatable')

{{-- Datatable of farmer details --}}
@include('FarmerModule::components.js.datatables.farmer_details_datatable')
@include('FarmerModule::components.js.datatables.farmer_rffa_details_datatable')
@endsection


<script>

</script>


@section('content')

<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('farmer.index') }}">Farmers List</a></li>
    <li class="breadcrumb-item active">Farmer Details</li>
</ol>

<!-- 
    IF program id is RFFA
    ELSE program id are Cash and Food, Dry, or Wet
-->
@if ($program_id == "37b5fdab-6482-433c-af96-455402d5ef77")
    @foreach ($farmer as $f)
    <h1 class="page-header">{{$f->last_name}}, {{$f->first_name}} {{$f->middle_name}} {{$f->ext_name}} - <small> RSBSA NO. {{$f->rsbsa_no}}</small></h1>
    @endforeach

    <!-- begin panel -->
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Farmer Details</h4>
        </div>
        <div class="panel-body">
            <br>
            <br><br>
            <table id="farmer-rrfa-details-datatable" class="table table-bordered text-center mb-5 display" style="width:100%">
                <thead class="table-header">
                    <tr>
                        <th>FULLNAME</th>
                        <th>PROGRAM</th>
                        <th>AMOUNT</th>
                        <th>PROCESSING AMOUNT FEE</th>
                        <th>BATCH DATE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>      
@else
    @foreach ($farmer as $f)
    <h1 class="page-header">{{$f->last_name}}, {{$f->first_name}} {{$f->middle_name}} {{$f->ext_name}} - <small> RSBSA NO. {{$f->reference_no}}</small></h1>
    @endforeach

    <!-- begin panel -->
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Farmer Details</h4>
        </div>
        <div class="panel-body" style="overflow-y: auto;">
            <br>
            <br><br>
            <table id="farmer-details-datatable" class="table table-bordered text-center mb-5 display" style="width:100%">
                <thead class="table-header">
                    <tr>
                        <th></th>
                        <th>FULLNAME</th>
                        <th>PROGRAM</th>
                        <th>ITEM</th>
                        <th>QUANTITY</th>
                        <th>AMOUNT</th>
                        <th>TOTAL AMOUNT</th>
                        <th>TRANSACT BY</th>
                        <th>PAYOUT DATE</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection