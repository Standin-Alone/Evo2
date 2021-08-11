@extends('global.base')
@section('title', "Roles and Permissions")




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
    
    <script>
        $(document).ready(function(){
        // load record to datatable
        $('#load-datatable').DataTable({
            serverSide: true,
            
            ajax: "{{route('roles.create')}}",               
            columns:[
                {data:'role'},
                {data:'modules',
                    render: function(data,type,row){
                        var print_module = [];
                        data.map(item=>{  print_module.push(item.module+'<br>') })
                            return print_module;
                    },
                    defaultContent: "",
                    sortable:false
                },
                {data:'role_id',
                    render: function(data,type,row){            
                        return "<button type='button' class='btn btn-success setModulesBtn' id='"+data+"' data-toggle='modal' data-target='#SetModulesModal'>"+
                                            "<i class='fa fa-edit'></i> Set Modules and Permissions"+
                                        "</button>";
                                        
                                    }
                }
            ]   
        });

    })
    </script>

    <script>
        $(document).ready(function(){       

        // load modules to selects
        let load_modules  = (data)=>{
            $("#select_module optgroup").remove();
            $("#select_module option").remove();
            $.ajax({
                        url:"{{route('select-modules')}}",
                        type:'POST',
                        data:data,                                    
                        success:function(data){

                            convertToJson = JSON.parse(data);
                            
                            console.warn();
                            convertToJson.main_modules.map((item)=>{
                                let check_parent_module = convertToJson.sub_modules.some((result)=> result.parent_module_id === item.sys_module_id);
                                if(item.has_sub == 1 && check_parent_module ){
                                    $("#select_module").append('<optgroup label="'+item.module+'" id="'+item.sys_module_id+'"></optgroup>');                                    

                                    convertToJson.sub_modules.map((value) =>{
                                        if(item.sys_module_id == value.parent_module_id){
                                            $('#select_module #'+item.sys_module_id).append('<option value="'+value.module+'">'+value.module+'</option>')
                                        }
                                    })
                                }else{
                                    $("#select_module").append('<option value="'+item.module+'">'+item.module+'</option>')
                                }
                            })                        
                            
                        }
                        
                    }) 
        }

        var permission_table = '';
        let data_modules = '';

             //open set modules form
        $("#load-datatable").on('click','.setModulesBtn',function(e){
                var id = $(this).attr('id');
                data_modules =  {
                    role_id:id,
                    _token:'{{csrf_token()}}'
                }
                $("#role_label").text($(this).closest('tbody tr').find('td:eq(0)').html());


                $('#select_module option').each(function() {                   
                    if($(this).val() != 'select_disabled' ){
                        $(this).remove();
                    }
                });

                load_modules(data_modules);
           
                
                // Permission table
                permission_table = $('#permission-datatable').DataTable({
                        serverSide: true,
                        ajax: {url:"{{route('roles-get-module-permissions')}}",type:'post',data:data_modules},                                                         
                        columns:[{data:'module'},
                                @foreach ($get_permissions as $key => $item)
                                    {data:'{{$item->permission}}',
                                    render: function(data,type,row,meta){                                           
                                    
                                    return  '<div class="checkbox checkbox-css">'+
                                                '<input type="checkbox" id="checkbox'+row['module']+'{{$key}}" permission="{{$item->permission}}" class="permission_chk" value="'+row['module']+'" '+ (data == 1 ? 'checked' : 'unchecked') +" />"                                                +
                                                '<label for="checkbox'+row['module']+'{{$key}}"></label>'+
                                            '</div>'                                                    
                                                }
                                },
                                @endforeach                    
                            ],                                         
                        bDestroy: true 
                    });
                
                $('#role_id').val(id);
                $("#role_span").text($(this).closest("tbody tr").find("td:eq(0)").html());
                e.preventDefault();
            });

                


            // checkbox permissions
            $("#permission-datatable").on('click','input[type="checkbox"]',function(){
                
                var role_id = $("#role_id").val();
                var module = $(this).val();
                var permission = $(this).attr('permission');
                
                
                var data = {};

                $(this).is(':checked')

                if($(this).is(':checked'))
                    {
                        data  =  {
                            role_id:role_id,
                            module:module,
                            permission:permission,
                            checked:true,
                            _token:'{{csrf_token()}}'
                            }   
                        console.warn(data)
                        // set disable 
                        $.ajax({
                            url:'{{route("roles-set-permissions")}}',
                            type:'post',
                            data:data,
                            success:function(){
                                permission_table.ajax.reload()
                            }
                        })

                }else{
                    data  =  {
                            role_id:role_id,
                            module:module,
                            permission:permission,
                            checked:false,
                            _token:'{{csrf_token()}}'
                            }   
                            console.warn(data)
                        // set enable 
                        $.ajax({
                            url:'{{route("roles-set-permissions")}}',
                            type:'post',
                            data:data,
                            success:function(){
                                permission_table.ajax.reload()
                            }
                        })
                    }   
            })


            // add module button
            $("#AddModuleBtn").click(function(){
                    var role_id = $("#role_id").val();
                    var selected_module = $("#select_module :selected").val();
                    var data = {
                        role_id: role_id,
                        module:selected_module,
                        _token:'{{csrf_token()}}'
                    }

                    if(selected_module != 'selected_disabled'){
                        $.ajax({
                            url:'{{route("roles.store")}}',
                            type:'post',
                            data:data,
                            success:function(){
                                load_modules(data_modules);
                                permission_table.ajax.reload()
                            }
                        })
                    }


            });

        })        
    </script>
@endsection


@section('content')
<!-- begin page-header -->
<h1 class="page-header">Roles and Permissions<small> This page is setup of permissions of roles.</small></h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Roles and Permissions</h4>
    </div>
    <div class="panel-body">        
        {{-- table --}}
        <table id="load-datatable" class="table table-hover">            
            <thead>
                <tr>                    
                    <th >Roles</th>
                    <th >Modules</th>
                    <th >Actions</th>                
                </tr>
            </thead>
            <tbody>              
            </tbody>
        </table>    




        <!-- #modal-view -->
         <div class="modal fade" id="SetModulesModal">
            <div class="modal-dialog" style="max-width: 50%">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">Set Modules and Permission</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>
                        

                        <div class="note note-success">
                            <div class="note-icon"><i class="fas fa-user"></i></div>
                            <div class="note-content">
                                <label style="font-size:30px" id="role_label"></label>
                            </div>
                        </div>

                        <br><br><br>
                        <input id="role_id" type="text" class="form-control hide " />
                        
                        <div class="form-group row row-space-2">
                            <label class="col-form-label col-md-3">Module Name</label>
                            <div class="col-md-6">
                                <select class="form-control" id="select_module">
                                    <option value='select_disabled' selected disabled> Select Module</option>
                                </select>                                
                            </div>

                            <div class="col-md-3">
                                <button type="button" class="btn btn-success" id="AddModuleBtn">Add</button>
                            </div>
                        </div>

                        <br><br>

                        <table id="permission-datatable" class="table table-hover">            
                            <thead>
                                <tr>                    
                                    <th >Modules</th>                                    
                                    @foreach ( $get_permissions as $item )                                    
                                    <th >{{$item->permission}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>              
                            </tbody>
                        </table>    
                
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