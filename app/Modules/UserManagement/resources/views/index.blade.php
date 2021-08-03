@extends('global.base')
@section('title', "User Management")




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
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>

    <script>


    $(document).ready(function(){

        // filter province
        $("#region").change(function(){
            let value = $("option:selected", this).val();
            $.ajax({
                url:'{{route("filter-province",["region_code" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#province").prop('disabled',false);
                    $("#province option").remove();
                    $("#province").append('<option value="" selected disabled>Select Province</option>')
                    convertToJson.map(item => {
                        $("#province").append('<option value="'+item.prov_code+'">'+item.prov_name+'</option>')
                    })
                }                
            });
        })

        // filter municipality
        $("#province").change(function(){
            let value = $("option:selected", this).val();
            $.ajax({
                url:'{{route("filter-municipality",["province_code" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#municipality").prop('disabled',false);
                    $("#municipality option").remove();
                    $("#municipality").append('<option value="" selected disabled>Select Municipality</option>')
                    convertToJson.map(item => {
                        $("#municipality").append('<option value="'+item.mun_code+'">'+item.mun_name+'</option>')
                    })
                }                
            });
        })



        // filter barangay
        $("#municipality").change(function(){
            let value = $("option:selected", this).val();
            $.ajax({
                url:'{{route("filter-barangay",["municipality_code" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#barangay").prop('disabled',false);
                    $("#barangay option").remove();
                    $("#barangay").append('<option value="" selected disabled>Select Barangay</option>')
                    convertToJson.map(item => {
                        $("#barangay").append('<option value="'+item.bgy_code+'">'+item.bgy_name+'</option>')
                    })
                }                
            });
        })

        $("input[name='agency_loc']").change(function(){
            let value = $(this).val();
            $.ajax({
                url:'{{route("filter-role",["agency_loc" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#role").prop('disabled',false);
                    $("#role option").remove();
                    $("#role").append('<option value="" selected disabled>Select Role</option>')
                    convertToJson.map(item => {
                        $("#role").append('<option value="'+item.role_id+'">'+item.role+'</option>')
                    })
                }                
            });
            
        })
    })

    </script>



    <script>
        $(document).ready(function(){


            $("#AddForm").validate({
                rules:{
                    first_name:'required',
                    last_name:'required',
                    email:{required:true,
                            email:true,
                            remote:{
                                url:"{{route('check-email')}}",
                                type:'get'
                            }
                        },
                    contact:{
                        required:true,
                        phoneUS: true
                    }, 
                    agency:'required',
                    agency_loc:'required',
                    role:'required',
                    program:'required',
                    region:'required',
                    province:'required',
                    municipality:'required',
                    barangay:'required',            
                },
                messages:{
                    first_name  :{required:'<div class="text-danger">Please enter your first name.</div>'},
                    last_name   :{required:'<div class="text-danger">Please enter your last name.</div>'},
                    email       :{
                                    required:'<div class="text-danger">Please enter your email.</div>',
                                    email:'<div class="text-danger">Please enter a valid email address.</div>', 
                                    remote:'<div class="text-danger">This email is already exist.</div>'
                                  },                    
                    contact     :{
                                    required:'<div class="text-danger">Please enter your phone number.</div>',
                                    phoneUS: '<div class="text-danger">Invalid format.</div>'
                                  },
                    agency      :{required:'<div class="text-danger">Please select your agency.</div>'},
                    agency_loc  :{required:'<div class="text-danger">Please select agency location.</div>'},
                    program     :{required:'<div class="text-danger">Please select your program.</div>'},
                    role        :{required:'<div class="text-danger">Please select your role.</div>'},
                    region      :{required:'<div class="text-danger">Please select region.</div>'},
                    province    :{required:'<div class="text-danger">Please select province.</div>'},
                    municipality:{required:'<div class="text-danger">Please select municipality.</div>'},
                    barangay    :{required:'<div class="text-danger">Please select barangay.</div>', } 
                },
                submitHandler:function(){
                    swal({
                    title: "Wait!",
                    text: "Are you sure you want to add this user?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                    })
                    .then((confirm) => {
                        id = $('input[name="id"]').val();
                        $(".add-btn").prop('disabled',true);
                        // check if confirm
                        if (confirm) {                       
                            $.ajax({
                                url:"{{route('user-add')}}",
                                type:'post',
                                data:$("#AddForm").serialize(),
                                success:function(response){             
                                    //    
                                    swal("Successfully added new user.", {
                                            icon: "success",
                                    }).then(()=>{
                                        $("#AddModal").modal('hide')
                                        $(".add-btn").prop('disabled',false);
                                        // module_table.ajax.reload();
                                    });
                                },
                                error:function(response){
                                    $(".add-btn").prop('disabled',false);
                                }
                            })
                            
                        } else {
                            $(".add-btn").prop('disabled',false);
                            swal("Operation Cancelled.", {
                                icon: "error",
                            });
                        }
                    });
                }
            })
        })

    </script>
@endsection










@section('content')

<!-- begin page-header -->
<h1 class="page-header">User Management<small>header small text goes here...</small></h1>
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
            <div class="modal-dialog" style="max-width: 40%">
                <form id="AddForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#6C9738;">
                            <h4 class="modal-title" style="color: white">Add</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <div class="col-lg-12 row">
                                <div class="form-group">
                                    <label>Name</label> <span style="color:red">*</span>
                                    <input style="text-transform: capitalize;"  name="first_name" class="form-control"  placeholder="First Name *"  >                                    
                                </div>&nbsp;
                                <div class="form-group">
                                    <label>&nbsp;</label> <span style="color:red"></span>
                                    <input style="text-transform: capitalize;"  name="middle_name" class="form-control"  placeholder="Middle Name " >                                    
                                </div>&nbsp;
                                <div class="form-group">
                                    <label>&nbsp;</label> <span style="color:red"></span>
                                    <input style="text-transform: capitalize;"  name="last_name" class="form-control"  placeholder="Last Name *"    >                                   
                                </div>&nbsp;
                                <div class="form-group">
                                    <label>&nbsp;</label> <span style="color:red"></span>
                                    <input style="text-transform: capitalize;"  name="ext_name" class="form-control"  placeholder="Extension Name *"    >                                   
                                </div>
                            </div>
                           

                            <div class="col-lg-12 row">
                                <div class="form-group">
                                    <label>Email</label><span style="color:red">*</span>
                                    <input    type="email" name="email" class="form-control"  placeholder="example@gmail.com" >
                                </div>&nbsp;&nbsp;
                                <div class="form-group">
                                    <label>Contact</label><span style="color:red">*</span>
                                    <input    type="number" name="contact" class="form-control"  placeholder="9102...." >
                                </div>
                            </div>

                            <div class="col-lg-12 row ">
                                <label class="col-md-12 row">Agency <span style="color:red">*</span></label> 
                                <div class="col-md-12  row">
                                    <div class="form-check ">
                                        <input class="form-check-input" type="radio" id="defaultRadio1" name="agency_loc"  value="CO" checked  />
                                        <label class="form-check-label" for="defaultRadio1">Central Office</label>
                                    </div> &nbsp; &nbsp;                       
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="defaultRadio2" name="agency_loc" value="RFO" />
                                        <label class="form-check-label" for="defaultRadio2">Regional Field Office</label>
                                    </div>       
                                </div>                       
                            </div><br>


                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Role</label> <span style="color:red">*</span>
                                    <select class="form-control" name="role" id="role" >
                                        <option selected disabled value="">Select Role</option>    
                                        @foreach ($get_roles as $item)
                                            <option  value="{{$item->role_id}}">{{$item->role}}</option>
                                        @endforeach                                
                                    </select>
                                </div>                              
                            </div>
                            
                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Agency</label> <span style="color:red">*</span>
                                    <select class="form-control" name="agency" >
                                        <option selected disabled value="">Select Agency</option>
                                        @foreach ($get_agency as $item)
                                            <option  value="{{$item->agency_id}}">{{$item->agency_name}}</option>
                                        @endforeach
                                    </select>
                                </div>                              
                            </div><br>

                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Program</label> <span style="color:red">*</span>
                                    <select class="form-control" name="program" id="program" >
                                        <option selected disabled value="">Select Program</option>                                    
                                        @foreach ($get_programs as $item)
                                            <option value="{{$item->program_id}}">{{$item->shortname}} ({{$item->description}})</option>
                                        @endforeach
                                    </select>
                                </div>                              
                            </div>                        

                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Region</label> <span style="color:red">*</span>
                                    <select class="form-control" id="region" name="region" >
                                        <option selected disabled value="">Select Region</option>
                                        @foreach ($get_regions as $item)
                                            <option value="{{$item->reg_code}}">{{$item->reg_name}}</option>
                                        @endforeach
                                    </select>
                                </div>                              
                            </div>

                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Province</label> <span style="color:red">*</span>
                                    <select class="form-control" id="province" name="province" disabled >
                                        <option selected disabled value="">Select Province</option>
                                    </select>
                                </div>                              
                            </div>

                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Municipality</label> <span style="color:red">*</span>
                                    <select class="form-control" id="municipality" name="municipality" disabled >
                                        <option selected disabled value="">Select Municipality</option>
                                    </select>
                                </div>                              
                            </div>

                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Barangay</label> <span style="color:red">*</span>
                                    <select class="form-control" id="barangay" name="barangay" disabled     >
                                        <option selected disabled value="">Select Barangay</option>
                                    </select>
                                </div>                              
                            </div>
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-lime add-btn">Add</button>
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