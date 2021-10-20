@extends('global.base')
@section('title', "Dashboard")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
@endsection

{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<script src="assets/js/demo/table-manage-default.demo.min.js"></script>
@endsection

<script>
    
</script>

@section('content')
<div class="jumbotron jumbotron-fluid" style="background-color: transparent !important;">
<img src="{{url('assets/img/logo/WELCOME_PAGE_LOGO.png')}}" class="" style=" width:35%; height:auto; display: block;margin-left: auto;margin-right: auto;"/>
    <div class="container" style="margin-top: -3px;">
        <h3 class="">Welcome!  <span class="text-success">{{session()->get('first_name')}} {{session()->get('middle_name')}} {{session()->get('last_name')}} {{session()->get('ext_name')}}</span></h3>
        <h5 class="">To</h5>
        <h3 class="">Interventions Management Platform </h3>
        <p class="lead"></p>
    </div>
</div>
@endsection