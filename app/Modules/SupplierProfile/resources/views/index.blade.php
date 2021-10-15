@extends('global.base')
@section('title', "Supplier Profile")




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

    <script type="text/javascript">
        $(function () {
            
            var table = $('#yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollY:        "300px",
                // scrollX:        true,
                // scrollCollapse: true,
                columnDefs: [
                    // { width: '20%', targets: 0 }
                ],
                ajax: "{{ route('supplier.list') }}",
                columns: [
                    {data: 'supplier_name', name: 'supplier_name'},
                    {data: 'address', name: 'address'},
                    {data: 'reg', name: 'reg'},
                    {data: 'prv', name: 'prv'},
                    {data: 'mun', name: 'mun'},
                    {data: 'brgy', name: 'brgy'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                    {
                        data: 'access_control', 
                        name: 'access_control', 
                        orderable: true, 
                        searchable: true
                    },
                ]
            });
            
        });

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                )
            }
            })
        
</script>

@endsection


@section('content')
<!-- begin breadcrumb -->
<!-- <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
    <li class="breadcrumb-item active">Blank Page</li>
</ol> -->
<!-- end breadcrumb -->
<!-- begin page-header -->
<!-- <h1 class="page-header">Supplier Profiling of Data <small>Supplier informations of access control</small></h1> -->
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Supplier Profile</h4>
    </div>
    <div class="panel-body">
        <button type='button' class='btn btn-success' data-toggle='modal' data-target='#AddMotherCompModal'>
            <i class='fa fa-plus'></i> Create Supplier Mother Company
        </button>
        <button type='button' class='btn btn-success pull-right' data-toggle='modal' data-target='#AddSuppBranchModal'>
            <i class='fa fa-plus'></i> Create Supplier Branch 
        </button>
        <br>
        <br><br>
        <table id="yajra-datatable" class="table table-striped table-bordered">            
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Address</th>
                        <th>Region</th>
                        <th>Province</th>
                        <th>Municipality</th>
                        <th>Barangay</th>
                        <th>Action</th>
                        <th>Access Control</th>
                    </tr>
                </thead>
                <tbody style="width:100px;">
                </tbody>
        </table>


        <!-- #modal-add -->
        <div class="modal fade" id="AddMotherCompModal">
            <div class="modal-dialog">
                <form id="AddForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #008a8a">
                            <h4 class="modal-title" style="color: white">Create Supplier Mother Company Information</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            
                            <!-- begin right-content -->
                                <div class="right-content">
                                    <div class="register-content">
                                        <form action="index.html" method="GET" class="margin-bottom-0">
                                            <label class="control-label">Company Name: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Address: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Complete Address" required />
                                                </div>
                                            </div>                                            
                                        </form>
                                    </div>
                                    <!-- end register-content -->
                                </div>
                                <!-- end right-content -->
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <a id="AddBTN" href="javascript:;" class="btn btn-success">Create</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- #modal-add -->
        <div class="modal fade" id="AddSuppBranchModal">
            <div class="modal-dialog">
                <form id="AddForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #008a8a">
                            <h4 class="modal-title" style="color: white">Create Supplier Branch Information</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            
                            <!-- begin right-content -->
                                <div class="right-content">
                                    <div class="register-content">
                                        <form action="index.html" method="GET" class="margin-bottom-0">
                                            <label class="control-label">Supplier Branch Name: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Supplier Mother Company: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Address: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Region: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Province: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Minicipality: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Barangay: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Bank Name: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Bank Account Name: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Bank Account No.: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                            <label class="control-label">Supplier Branch Name: <span class="text-danger">*</span></label>
                                            <div class="row m-b-15">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" placeholder="Company Name" required />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end register-content -->
                                </div>
                                <!-- end right-content -->
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <a id="AddBTN" href="javascript:;" class="btn btn-success">Create</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

       <!-- #modal-Edit -->
       <div class="modal fade" id="Edit_Modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">Update Supplier Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>
                        <label style="display: block; text-align: center">Update Supplier Information</label>

                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- #modal-Delete -->
        <div class="modal fade" id="Delete_Modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">Delete Supplier Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>
                        <label style="display: block; text-align: center">Delete Supplier Information</label>

                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- #modal-ViewDetails -->
        <div class="modal fade" id="ViewDetails_Modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">View Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>
                        <label style="display: block; text-align: center">View Information</label>

                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- #modal-Program_Access -->
        <div class="modal fade" id="ProgramAccess_Modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">Setup Program Access</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>
                        <label style="display: block; text-align: center">Setup Program Access</label>

                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- end panel -->
@endsection