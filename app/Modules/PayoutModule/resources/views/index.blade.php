@extends('global.base')
@section('title', "Payout Module")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/pgv/backend-style.css" rel="stylesheet">
    
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
    <script src="assets/pgv/backend-script.js"></script>   

@endsection


@section('content')
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">

<!-- begin jumbotron -->
<div class="jumbotron text-center bg-white">
                <h1 class="text-inverse" 
                    data-hintPosition="top-middle" 
                    data-position="bottom-right-aligned">Welcome</h1>
                <p class="lead m-b-20"
                    data-hintPosition="top-middle" 
                    data-position="bottom-right-aligned">Web-based System Voucher Management Platform</p>
                <p>
                <a href="javascript:;" class="text-muted" 
                    data-hintPosition="top-middle" 
                    data-position="bottom-right-aligned">Powered by Department of Agriculture</a>
            </div>
            <!-- end jumbotron -->
@endsection