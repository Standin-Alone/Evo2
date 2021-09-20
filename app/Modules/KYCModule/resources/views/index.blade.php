@extends('global.base')
@section('title', 'Supplier Registration')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
@endsection




{{-- import in this section your javascript files --}}
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
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
        <li class="breadcrumb-item active">Blank Page</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1>
    <!-- end page-header -->

    <!-- begin panel -->
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">KYC</h4>
        </div>
        {{-- KYC FORM PANEL --}}
        <div class="panel-body">
            <div class="panel panel-primary col-md-6">
                <div class="panel-heading">
                    <h4 class="panel-title">KYC Import</h4>
                </div>
                <form id="AddForm" method="POST">
                    @csrf
                    <div class="panel-body border">

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Import Excel</label> <span id='reqcatnameadd'></span>
                                <input type="file" id="AddCatName" name="AddCatName" class="form-control"
                                    placeholder="e.g.: Missing Persons" required="true">
                            </div>
                        </div>
                        
                    </div>

                    <div class="panel-footer">
                        <div class="pull-right">
                            <button type='submit' class='btn btn-lime' data-toggle='modal' data-target='#AddModal'>
                                <i class='fa fa-cloud-download-alt'></i> Import
                            </button>
                        </div>
                    </div>
                </form>
                <br>
            </div>


     
        </div>
    </div>
    <!-- end panel -->
@endsection
