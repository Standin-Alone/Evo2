@extends('global.base')
@section('title', "Supplier Registration")




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
        <h4 class="panel-title">Panel Title here</h4>
    </div>
    <div class="panel-body">
        <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
            <i class='fa fa-plus'></i> Add New
        </button>
        <br>
        <br><br>
        <table id="data-table-default" class="table table-striped table-bordered">            
            <thead>
                <tr>                    
                    <th >Supplier Name</th>
                    <th >Address</th>                    
                    <th >Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Edcel Zenarosa</td>
                    <td>San Jose Del Monte, Bulacan</td>
                    <td>
                        <button type='button' class='btn btn-success'data-toggle='modal' data-target='#ViewModal' >
                            <i class='fa fa-eye'></i> View
                        </button>
                        <button type='button' class='btn btn-warning' data-toggle='modal' data-target='#UpdateModal'>
                            <i class='fa fa-edit'></i> Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- #modal-add -->
        <div class="modal fade" id="AddModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="AddForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#6C9738;">
                            <h4 class="modal-title" style="color: white">Add</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Sample</label> <span id='reqcatnameadd'></span>
                                    <input style="text-transform: capitalize;" id="AddCatName" name="AddCatName" class="form-control"  placeholder="e.g.: Missing Persons" required="true">
                                </div>
                            </div>
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <a id="AddBTN" href="javascript:;" class="btn btn-lime">Add</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


         <!-- #modal-view -->
         <div class="modal fade" id="ViewModal">
            <div class="modal-dialog" style="max-width: 30%">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">View Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>
                        <label style="display: block; text-align: center">Sample</label>

                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- #modal-EDIT -->
        <div class="modal fade" id="UpdateModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="EditForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f59c1a">
                            <h4 class="modal-title" style="color: white">Edit Category</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <label class="form-label hide"> ID</label>
                            <input id="edit_id" name="edit_id" type="text" class="form-control hide" name="edit_id"/>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input style="text-transform: capitalize;" id="edit_sup_name" name="edit_sup_name" class="form-control"  placeholder="e.g.: SM" required="true">
                                </div>
                            </div>
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <a id="EditBTN" href="javascript:;" class="btn btn-success">Update</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- end panel -->
@endsection