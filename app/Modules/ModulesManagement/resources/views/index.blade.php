@extends('global.base')
@section('title', "Modules Management")




{{--  import in this section your css files--}}
@section('page-css')

    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <style>
        #load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }
    </style>
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
        // load record to datatable
        module_table = $('#load-datatable').DataTable({
            serverSide: true,                        
            ajax: {
                "url" : '{{route("modules.show",["module" => "0"])}}',
                "type" : "get"
            },
            columns:[
                {data:'module'},
                {data:'routes',
                    render: function(data){                   
                        return data == null ? 'N/A' : data;
                    }
                },            
                {data:'sub_modules',
                    render:function(data,type,row){
                        let output = '';
                        if(data){
                        data.map((item)=>{output += item.module + '<br>'});
                        }
                        return output == '' ? "N/A" : output;
                    }


                },            
                {data:'sys_module_id',
                    orderable:false,
                    render: function(data,type,row){       
                        
                        

                        return  "<button type='button' class='btn btn-outline-warning update-modal-btn' has_sub="+row['has_sub']+" sys_module_id="+data+" data-toggle='modal' data-target='#UpdateModal'>"+
                                    "<i class='fa fa-edit'></i> Edit"+
                                "</button>   "+(
                                row['status'] == 1 ?
                                "<button type='button' class='btn btn-outline-danger set-status-btn ' sys_module_id='"+data+"' status='"+row["status"]+"' >"+
                                    "<i class='fa fa-trash'></i> Disable"+
                                "</button>  " :
                                "<button type='button' class='btn btn-outline-success set-status-btn' sys_module_id='"+data+"' status='"+row["status"]+"' >"+
                                    "<i class='fa fa-undo'></i> Enable"+
                                "</button> ")
                    }
                }
            ]   
        });
        
        let sub_module_table ;
        // edit modal btn
        $("#load-datatable").on('click','.update-modal-btn',function(){
            $('.update-modal-title').text('Edit '+$(this).closest('tbody tr').find('td:eq(0)').text());            
            // check if has sub modules
            if($(this).attr('has_sub')!= 1){
            
                $(".update-modal-dialog").css('max-width','30%')
                $(".main-module-component").show();
                $(".sub-modules-component").hide();                
                $('#update-id').val($(this).attr('sys_module_id'));
                $("#UpdateForm  input[name='module_name']").val($(this).closest('tbody tr').find('td:eq(0)').text());
                $("#UpdateForm  input[name='route']").val($(this).closest('tbody tr').find('td:eq(1)').text());
                $(".main-module-update-btn").show();
                $(".add-sub-module-btn").hide();
            }else{                
                
                $("input[name='parent_module_id']").val($(this).attr('sys_module_id'));
                $(".update-modal-dialog").css('max-width','50%')
                $(".add-sub-module-btn").show();
                $(".main-module-update-btn").hide();
                $(".main-module-component").hide();
                $(".sub-modules-component").show();
                $("#sub-modules-datatable").DataTable().destroy()
                $("#sub-modules-datatable").empty();

                // sub modules id
                 sub_module_table = $("#sub-modules-datatable").DataTable({
                                        serverSide: true,                                                       
                                        ajax: {
                                                "url" : '{{route("modules.show_sub_modules",["parent_module_id" => ":id"])}}'.replace(':id',$(this).attr('sys_module_id')),
                                                "type" : "get"
                                                },
                                                columns:[
                                                            {data:'module',title:'Module'},
                                                            {data:'routes',title:'Route'},                                                             
                                                            {data:'sys_module_id', title:'Actions',
                                                                render: function(data,type,row){       
                                                                    
                                                                    

                                                                    return  "<button type='button' class='btn btn-outline-info update-modal-btn' has_sub="+row['has_sub']+" sys_module_id="+data+" data-toggle='modal' data-target='#UpdateSubModuleModal'>"+
                                                                                "<i class='fa fa-edit'></i> Edit"+
                                                                            "</button>   "+(
                                                                            row['status'] == 1 ?
                                                                            "<button type='button' class='btn btn-outline-danger set-status-btn ' sys_module_id='"+data+"' status='"+row["status"]+"' >"+
                                                                                "<i class='fa fa-trash'></i> Disable"+
                                                                            "</button>  " :
                                                                            "<button type='button' class='btn btn-outline-success set-status-btn' sys_module_id='"+data+"' status='"+row["status"]+"' >"+
                                                                                "<i class='fa fa-undo'></i> Enable"+
                                                                            "</button> ")
                                                                }                                                            
                                                            }
                                                    ] 
                                            })
                                        }
            });
  

            // edit button of sub modules
            $("#sub-modules-datatable").on('click','.update-modal-btn',function(){
                $('.update-sub-modal-title').text('Edit '+$(this).closest('tbody tr').find('td:eq(0)').text());            
            // check if has sub modules
           
                             
                $("#UpdateSubModuleForm  input[name='id']").val($(this).attr('sys_module_id'));
                $("#UpdateSubModuleForm  input[name='module_name']").val($(this).closest('tbody tr').find('td:eq(0)').text());
                $("#UpdateSubModuleForm  input[name='route']").val($(this).closest('tbody tr').find('td:eq(1)').text());
          
            
            });
        
   
        // set status btn
        $("#load-datatable").on('click','.set-status-btn',function(){
            id = $(this).attr('sys_module_id');
            status = $(this).attr('status');
                                    
            swal({
                    title: "Wait!",
                    text: "Are you sure you want to "+ (status == 1 ? 'disable' : 'enable')+"?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    $('#add-btn').prop('disabled','true');
                    // check if confirm
                    if (confirm) {                       
                        $.ajax({
                            url:'{{route("modules.destroy",["module"=>":id"])}}'.replace(':id',id),
                            type:'DELETE',
                            data:{'_token':'{{csrf_token()}}','status':status},
                            success:function(response){             
                                //    
                                swal("Successfully "+(status == 1 ? 'disable' : 'enable')+" the module.", {
                                    icon: "success",
                                }).then(()=>{                    
                                    $('#add-btn').prop('disabled','false');            
                                    module_table.ajax.reload();
                                    
                                });
                            },
                            error:function(response){

                            }
                        })
                        
                    } else {
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                });
        }); 




         // set status btn sub modules
         $("#sub-modules-datatable").on('click','.set-status-btn',function(){
            id = $(this).attr('sys_module_id');
            status = $(this).attr('status');
                                    
            swal({
                    title: "Wait!",
                    text: "Are you sure you want to "+ (status == 1 ? 'disable' : 'enable')+"?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    $('#add-btn').prop('disabled','true');
                    // check if confirm
                    if (confirm) {                       
                        $.ajax({
                            url:'{{route("modules.destroy",["module"=>":id"])}}'.replace(':id',id),
                            type:'DELETE',
                            data:{'_token':'{{csrf_token()}}','status':status},
                            success:function(response){             
                                //    
                                swal("Successfully "+(status == 1 ? 'disable' : 'enable')+" the module.", {
                                    icon: "success",
                                }).then(()=>{                    
                                    $('#add-btn').prop('disabled','false');            
                                    sub_module_table.ajax.reload();
                                    
                                });
                            },
                            error:function(response){

                            }
                        })
                        
                    } else {
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                });
        }); 
        
        // Insert Module Record
        $("#AddForm").validate({
            rules:{
                module_name:"required",
                route:"required",
            },
            messages:{
                module_name:{
                    required:'<div class="text-danger">Please enter module name.</div>'
                },
                route:{
                    required:'<div class="text-danger">Please enter route.</div>'
                }              
            },
            submitHandler: function(e) { 
                swal({
                    title: "Wait!",
                    text: "Are you sure you want to add this module?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    $has_sub = $("input[name='has_sub']:checked").val();
                        
                    // check if confirm
                    if (confirm) {       
                        $('.add-btn').prop('disabled','true');
                        $.ajax({
                            url:'{{route("modules.store")}}',
                            type:'post',
                            data:$("#AddForm").serialize(),
                            success:function(response){             
                                
                                if(response == 'true'){
                                    swal("Successfully created a new module.", {
                                        icon: "success",
                                    }).then(()=>{
                                        $("#AddModal").modal('hide')
                                        $("#AddForm")[0].reset();
                                        module_table.ajax.reload();
                                        $('.add-btn').prop('disabled','false');
                                        
                                    });

                                }else{
                                    $('.add-btn').removeAttr('disabled');
                                    swal("This module is already exist.", {
                                            icon: "error",
                                        });

                                        
                                }
                            },
                            error:function(response){
                                $('.add-btn').removeAttr('disabled');
                                console.warn(response)
                            }
                        })
                        
                    } else {
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                        $('.add-btn').removeAttr('disabled');
                    }
                });
                
                
            }
        })




        $("#AddSubModulesForm").validate({
            rules:{
                module_name:"required",
                route:"required",
            },
            messages:{
                module_name:{
                    required:'<div class="text-danger">Please enter module name.</div>'
                },
                route:{
                    required:'<div class="text-danger">Please enter route.</div>'
                }              
            },
            submitHandler: function(e) { 
                
                swal({
                    title: "Wait!",
                    text: "Are you sure you want to add this module?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    $has_sub = $("input[name='has_sub']:checked").val();
                    
                    // check if confirm
                    if (confirm) {       

                        $.ajax({
                            url:'{{route("modules.store_sub_modules")}}',
                            type:'post',
                            data:$("#AddSubModulesForm").serialize(),
                            success:function(response){             
                                   
                                swal("Successfully created a new sub module.", {
                                    icon: "success",
                                }).then(()=>{
                                    $("#AddSubModulesModal").modal('hide')
                                     sub_module_table.ajax.reload();
                                     $("#AddSubModulesForm")[0].reset();
                                     $("#load-datatable").DataTable().ajax.reload();
                                    
                                    
                                });
                            },
                            error:function(response){

                            }
                        })
                        
                    } else {
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                });
               
                
            }
        })


        // Update Record of Main Module
        $("#UpdateForm").validate({
            rules:{
                module_name:"required",
                route:"required",
            },
            messages:{
                module_name:{
                    required:'<div class="text-danger">Please enter module name.</div>'
                },
                route:{
                    required:'<div class="text-danger">Please enter route.</div>'
                }
            },
            submitHandler: function(e) { 
              
                swal({
                    title: "Wait!",
                    text: "Are you sure you want to update this module?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    id = $('#update-id').val();
                    
                    // check if confirm
                    if (confirm) {                       
                        $.ajax({
                            url:"{{route('modules.update',['module' => ':id'])}}".replace(':id', id),
                            type:'PUT',
                            data:$("#UpdateForm").serialize(),
                            success:function(response){             
                                //    
                                swal("Successfully updated the module.", {
                                         icon: "success",
                                }).then(()=>{
                                    $("#UpdateModal").modal('hide')
                                    module_table.ajax.reload();
                                });
                            },
                            error:function(response){

                            }
                        })
                        
                    } else {
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                    
                });         
            }
        })






        // Update record of sub module
        $("#UpdateSubModuleForm").validate({
            rules:{
                module_name:"required",
                route:"required",
            },
            messages:{
                module_name:{
                    required:'<div class="text-danger">Please enter module name.</div>'
                },
                route:{
                    required:'<div class="text-danger">Please enter route.</div>'
                }
            },
            submitHandler: function(e) { 
                swal({
                    title: "Wait!",
                    text: "Are you sure you want to update this module?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    id = $('#UpdateSubModuleForm input[name="id"]').val();
                    
                    // check if confirm
                    if (confirm) {                       
                        $.ajax({
                            url:"{{route('modules.update',['module' => ':id'])}}".replace(':id', id),
                            type:'PUT',
                            data:$("#UpdateSubModuleForm").serialize(),
                            success:function(response){             
                                //    
                                swal("Successfully updated the module.", {
                                         icon: "success",
                                }).then(()=>{
                                    $("#UpdateSubModuleModal").modal('hide')
                                    sub_module_table.ajax.reload();
                                });
                            },
                            error:function(response){
                                console.warn(response)
                            }
                        })
                        
                    } else {
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                });
                
            }
        })
       
    })
    </script>


    <script>
        $(document).ready(function(){            

            // has sub radio button
            $("input[name='has_sub']").change(function(){
                
                if($(this).val() == 1){
                    
                    $(".sub_module").append('<div class="sub_module_component row">'+
                                        '<div class="form-group">'+
                                            '<label>Sub Module Name</label> <span id="reqcatnameadd" style="color:red">*</span>'+
                                            '<input   name="module_name[]" class="form-control"  placeholder="module.index" required="true">'+
                                        '</div>&nbsp;&nbsp;'+
                                        '<div class="form-group">'+
                                            '<label>Route </label> <span id="reqcatnameadd" style="color:red">*</span>'+
                                            '<input   name="route[]" class="form-control"  placeholder="module.index" required="true">'+
                                        '</div>&nbsp;&nbsp;'+
                                        '<div class="form-group">'+
                                            '<label>&nbsp;</label> <span id="reqcatnameadd" style="color:red">&nbsp;</span>'+                                            
                                            '<button type="button" class="form-control btn btn-lime component-plus-btn"><span class="fas fa-plus"></span></button>'+
                                        '</div>'+
                                    '</div>');
                                    
                    $('.route-component').remove();
                    
                }else{

                    $('.route').append(
                                '<div class="form-group route-component">'+
                                '<label>Route </label> <span id="reqcatnameadd" style="color:red">*</span>'+
                                '<input   name="route[]" class="form-control"  placeholder="module.index" required="true">'+
                                '</div>'
                    );

                    $('.sub_module_component').remove();
                }
            });

         

            // add sub module component
            $(document).on('click', '.component-plus-btn' , function(){

                $(".sub_module").prepend('<div class="sub_module_component row">'+
                                        '<div class="form-group">'+
                                            '<label>Sub Module Name</label> <span id="reqcatnameadd" style="color:red">*</span>'+
                                            '<input   name="module_name[]" class="form-control"  placeholder="module.index" required="true">'+
                                        '</div>&nbsp;&nbsp;'+
                                        '<div class="form-group">'+
                                            '<label>Route </label> <span id="reqcatnameadd" style="color:red">*</span>'+
                                            '<input   name="route[]" class="form-control"  placeholder="module.index" required="true">'+
                                        '</div>&nbsp;&nbsp;'+
                                        '<div class="form-group">'+
                                            '<label>&nbsp;</label> <span id="reqcatnameadd" style="color:red">&nbsp;</span>'+
                                            '<button type="button"  class="form-control btn btn-danger remove-btn" ><span class="fas fa-times"></span></button>'+                                            
                                        '</div>'+
                                    '</div>');
            })


            $(document).on('click', '.remove-btn', function(e) {
            e.preventDefault();
            $(this).closest('.sub_module_component').remove();
            return false;
            });
      
        });
        
    </script>

@endsection





    




@section('content')
<!-- begin page-header -->
<h1 class="page-header">Modules Management</h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title">Module Management</h4>
    </div>
    <div class="panel-body">
        <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
            <i class='fa fa-plus'></i> Add New
        </button>
        <br>
        <br>
        <br>
        <table id="load-datatable" class="table table-hover table-bordered">            
            <thead>
                <tr>                    
                    <th >Module Name</th>
                    <th >Route</th> 
                    <th >Sub Modules</th>                    
                    <th >Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>


        <!-- #modal-add -->
        <div class="modal fade" id="AddModal"  data-backdrop="static" data-keyboard="false">
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
                                    <label>Module Name</label> <span id='reqcatnameadd' style='color:red'>*</span>
                                    <input style="text-transform: capitalize;"  name="module_name[]" class="form-control"  placeholder="module name" required="true">
                                </div>

                                <div class="form-group">
                                    <label>Do you want to add sub modules? <span style="color:red">*</span></label> 
                                    <div class="col-md-12  row">
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" id="defaultRadio1" name="has_sub"  value="1"   />
                                            <label class="form-check-label" for="defaultRadio1">Yes</label>
                                        </div> &nbsp; &nbsp;                       
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="defaultRadio2" name="has_sub" value="0" checked/>
                                            <label class="form-check-label" for="defaultRadio2">No</label>
                                        </div>       
                                    </div>                       
                                </div>


                                <div class="form-group route">
                                    <div class="form-group route-component">
                                    <label>Route </label> <span id='reqcatnameadd' style='color:red'>*</span>
                                    <input   name="route" class="form-control"  placeholder="module.index" required="true">
                                    </div>
                                </div>
                                

                                <div class="col-md-12 row  sub_module">                                 
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





        <!-- #modal-EDIT Main Modal and Sub Modules -->
        <div class="modal fade" id="UpdateModal"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog update-modal-dialog" style="max-width: 100%">
                <form id="UpdateForm" method="PUT"  >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f59c1a">
                            <h4 class="modal-title update-modal-title" style="color: white">Edit Module</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <label class="form-label hide"> ID</label>
                            <input name="id" type="text" class="form-control hide" id="update-id" />
                            <button type='button' class='btn btn-lime add-sub-module-btn' data-toggle='modal' data-target='#AddSubModulesModal' >
                                <i class='fa fa-plus'></i> Add New
                            </button>
                            <br><br>
                            <div class="col-lg-12 main-module-component">
                                <div class="form-group">
                                    <label>Module Name</label>
                                    <input style="text-transform: capitalize;"  name="module_name" class="form-control"  placeholder="module name"  required="true">
                                    <label>Route</label>
                                    <input   name="route" class="form-control"  placeholder="route" required="true">
                                </div>
                            </div>


                            
                            <div class="col-md-12 sub-modules-component">                               
                            <table id="sub-modules-datatable" class="table table-hover" style="width:100%">            
                                <thead>
                                    <tr>                    
                                        <th >Module Name</th>
                                        <th >Route</th>                                           
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white update-close-btn" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-warning main-module-update-btn">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>





        <!-- #modal-EDIT sub modules-->
        <div class="modal fade" id="UpdateSubModuleModal"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="UpdateSubModuleForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #49b6d6">
                            <h4 class="modal-title update-sub-modal-title" style="color: white">Edit Module</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <label class="form-label hide"> ID</label>
                            <input name="id" type="text" class="form-control hide" />

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Module Name</label>
                                    <input style="text-transform: capitalize;"  name="module_name" class="form-control"  placeholder="module name"  required="true">
                                    <label>Route</label>
                                    <input   name="route" class="form-control"  placeholder="route" required="true">
                                </div>
                            </div>
                        
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white update-close-btn" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>




         <!-- #modal-ADD sub modules-->
         <div class="modal fade" id="AddSubModulesModal"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="AddSubModulesForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #6C9738">
                            <h4 class="modal-title update-sub-modal-title" style="color: white">Add Sub Module</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <div class="col-lg-12 row">
                                <input type="text" name="parent_module_id"   class="form-control hide">
                                <div class="form-group">
                                    <label>Module Name</label>
                                    <input style="text-transform: capitalize;"  name="module_name" class="form-control"  placeholder="module name"  required="true">                                        
                                </div> &nbsp;&nbsp;
                                <div class="form-group">
                                    <label>Route</label>
                                    <input   name="route" class="form-control"  placeholder="route" required="true">
                                </div>&nbsp;&nbsp;                                
                            </div>  
                        
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white update-close-btn" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-lime">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>
<!-- end panel -->
@endsection