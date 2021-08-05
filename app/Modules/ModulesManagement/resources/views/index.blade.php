@extends('global.base')
@section('title', "Modules Management")




{{--  import in this section your css files--}}
@section('page-css')

    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <style>
       
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
                {data:'routes'},            
                {data:'sub_modules',
                    render:function(data,type,row){
                        let output = '';
                        if(data){
                        data.map((item)=>{output += item.module + '<br>'});
                        }
                        return output;
                    }


                },            
                {data:'sys_module_id',
                    render: function(data,type,row){       
                      
                        return  "<button type='button' class='btn btn-warning update-modal-btn' id="+data+" data-toggle='modal' data-target='#UpdateModal'>"+
                                    "<i class='fa fa-edit'></i> Edit"+
                                "</button>   "+(
                                row['status'] == 1 ?
                                "<button type='button' class='btn btn-danger set-status-btn ' id='"+data+"' status='"+row["status"]+"' >"+
                                    "<i class='fa fa-trash'></i> Disable"+
                                "</button>  " :
                                "<button type='button' class='btn btn-success set-status-btn' id='"+data+"' status='"+row["status"]+"' >"+
                                    "<i class='fa fa-undo'></i> Enable"+
                                "</button> ")
                    }
                }
            ]   
        });
        
        // edit modal btn
        $("#load-datatable").on('click','.update-modal-btn',function(){
            $("#UpdateForm  input[name='id']").val($(this).attr('id'));
            $("#UpdateForm  input[name='module_name']").val($(this).closest('tbody tr').find('td:eq(0)').text());
            $("#UpdateForm  input[name='route']").val($(this).closest('tbody tr').find('td:eq(1)').text());
        });

        // set status btn
        $("#load-datatable").on('click','.set-status-btn',function(){
            id = $(this).attr('id');
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



        // Insert Record
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
                ,
                sample:{
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
                            url:'{{route("modules.store")}}',
                            type:'post',
                            data:$("#AddForm").serialize(),
                            success:function(response){             
                                   
                                swal("Successfully created a new module.", {
                                    icon: "success",
                                }).then(()=>{
                                    $("#AddModal").modal('hide')
                                    e.resetForm();
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



        // Update Record
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
            submitHandler: function() { 
                swal({
                    title: "Wait!",
                    text: "Are you sure you want to update this module?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    id = $('input[name="id"]').val();
                    
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
<h1 class="page-header">Modules Management<small>setup for the modules of system.</small></h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-inverse">
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
        <table id="load-datatable" class="table table-hover">            
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





        <!-- #modal-EDIT -->
        <div class="modal fade" id="UpdateModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="UpdateForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f59c1a">
                            <h4 class="modal-title" style="color: white">Edit Module</h4>
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
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- end panel -->
@endsection