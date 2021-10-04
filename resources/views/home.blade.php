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
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <img src="{{url('assets/img/logo/DA-Logo.png')}}" width="20%" height="20%" style="display: inline-block"/>
        <h2 class="mt-3">Welcome! {{session()->get('first_name')}} {{session()->get('middle_name')}} {{session()->get('last_name')}} {{session()->get('ext_name')}}</h1>
        <h4 class="mt-3">To</h3>
        <h2 class="mt-3">Interventions Management Platform 2.0</h1>
        <p class="lead"></p>
    </div>
</div>
@endsection