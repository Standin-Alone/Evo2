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

<!-- begin panel -->
<div class="panel panel-inverse mt-5">
    <div class="panel-heading">
        <h4 class="panel-title">Farmers List</h4>
    </div>
    <div class="panel-body">
        <br>
        <br><br>
        <table id="farmer-datatable" class="table table-striped table-bordered text-center">
            <thead class="table-header">
                <tr>
                    <th>Reference No.</th>
                    <th>Fullname</th>
                    <th>View farmer details</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
