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

{{-- <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initMap" async defer></script> --}}

{{-- External JS --}}
@include('FarmerModule::components.js.js')

{{-- Farmer Datatable --}}
@include('FarmerModule::components.js.datatables.farmer_datatable')
@include('FarmerModule::components.js.datatables.farmer_rffa_datatable')
@endsection


<script>

</script>


@section('content')
{{-- <input type="hidden" id="refno" value="1"> --}}
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">Farmers List</li>
</ol>
<!-- end breadcrumb -->

<br>
<br>
<ul class="nav nav-tabs">
    <li class="nav-items">
        <a href="#rffa_tbl" data-toggle="tab" class="nav-link active">
            <span class="d-sm-none">RCEF RFFA</span>
            <span class="d-sm-block d-none">RCEF RFFA</span>
        </a>
    </li>
    <li class="nav-items">
        <a href="#voucher_tbl" data-toggle="tab" class="nav-link">
            <span class="d-sm-none">VOUCHER</span>
            <span class="d-sm-block d-none">VOUCHER</span>
        </a>
    </li>
    {{-- @foreach ($programs as $p)
        <li class="nav-items">
            <a href="#{{$p->program_id}}" data-toggle="tab" class="nav-link @if ($loop->first) active @endif">
                <span class="d-sm-block d-none">{{$p->title}}</span>
            </a>
        </li>
    @endforeach --}}
</ul>
<div class="tab-content">
    @foreach ($programs as $p)
        <div class="tab-pane fade @if ($loop->first) active show @endif" id="rffa_tbl">
            <br>
            <table id="farmer-rffa-datatable" class="table table-striped table-bordered text-center" style="width: 100%">
                <thead class="table-header">
                    <tr>
                        <th>RSBSA NO.</th>
                        <th>FULLNAME</th>
                        <th>VIEW FARMER DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="voucher_tbl">
            <br>
            <table id="farmer-datatable" class="table table-striped table-bordered text-center" style="width: 100%">
                <thead class="table-header">
                    <tr>
                        <th>REFERENCE NO.</th>
                        <th>FULLNAME</th>
                        <th>VIEW FARMER DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    @endforeach
</div>
@endsection
