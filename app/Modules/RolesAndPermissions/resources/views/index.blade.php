@extends('global.base')
@section('title', "Roles and Permissions")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	{{-- <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css">
    <style>
        td { font-size: 17px; font-weight: 500 }

        
        #load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }

        #permission-datatable > thead > tr > th {
            color:white;
            font-size: 15px;
            background-color: #008a8a;
        }

        input[type="checkbox"] {
            cursor: pointer;
        }

        #permission-datatable> thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
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
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
    {{-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>     --}}
    <script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
    
    <script>
        $(document).ready(function(){
      

    })
    </script>

    <script>
        loadDataTable  = '';
        $(document).ready(function(){       

         // load record to datatable
        loadDataTable =  $('#load-datatable').DataTable({
            serverSide: true,
            responsive:true,
            ajax: "{{route('roles.create')}}",               
            columns:[
                {data:'role'},
                {data:'modules',                   
                    render: function(data,type,row){
                        var print_module = [];
                        data.map(item=>{  print_module.push('<li>'+item.module+'</li>') })
                            return print_module.length != 0 ? '<ul>'+print_module+'</ul>' : 'N/A' ;
                    },
                    defaultContent: "",
                    sortable:false
                },
                {data:'role_id',
                    render: function(data,type,row){            
                        return "<button type='button' class='btn btn-outline-success setModulesBtn' id='"+data+"' data-toggle='modal' data-target='#SetModulesModal'>"+
                                            "<i class='fa fa-edit'></i> Set Modules and Permissions"+
                                        "</button>";
                                        
                                    }
                }
            ]   
        });

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
                                    if(item.has_sub  == 0){
                                        $("#select_module").append('<option value="'+item.module+'">'+item.module+'</option>')
                                    }
                                }
                            })                        
                            
                        }
                        
                    }) 
        }

        let permission_table = '';
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
                        responsive:true,
                        destroy:true,                        
                        ajax: {url:"{{route('roles-get-module-permissions')}}",type:'post',data:data_modules},                                                         
                        columns:[   
                                    {data:'parent_module',visible:false}, 
                                    {data:'module'},                                
                                @foreach ($get_permissions as $key => $item)
                                    {data:'{{$item->permission}}',
                                        orderable:false,

                                    render: function(data,type,row,meta){                                           
                                    
                                    return  '<div class="checkbox checkbox-css">'+
                                                '<input type="checkbox" id="checkbox'+row['module']+'{{$key}}"  permission="{{$item->permission}}" class="permission_chk" value="'+row['module']+'" '+ (data == 1 ? 'checked' : 'unchecked') +" />"                                                +
                                                '<label for="checkbox'+row['module']+'{{$key}}"></label>'+
                                            '</div>'                                                    
                                                }
                                },
                                @endforeach   
                                {data:'sys_module_id',
                                    render: function(data,type,row){            
                                        return "<button type='button' class='btn btn-danger remove-module-btn' id='"+data+"' >"+
                                                            "<i class='fa fa-times'></i> Remove"+
                                                        "</button>";
                                                        
                                                    }
                                }                                                                                 
                            ],      
                         
                        columnDefs:[{visible:false,target:1}],
                        drawCallback:function(data){
                            let api = this.api();
                            let rows = api.rows({page:'current'}).nodes();
                            let last = null ;

                            api.column(0,{page:"current"})
                                .data()
                                .each((group,i)=>{
                                    
                                    console.warn(group);
                                        if(last != group && group != null){
                                            $(rows).eq(i).before('<tr  class="bg-success font-weight-bold  text-white" ><td colspan="{{count($get_permissions)+2}}" >'+group+'</td></tr>')
                                            last = group;
                                        }
                                });
                        },                        
                  
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



             // remove module
             $("#permission-datatable").on('click','.remove-module-btn',function(){
                swal({
                    title: "Wait!",
                    text: "Are you sure you want to remove this module?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                    })
                    .then((confirm) => {
                        role_id = $('#role_id').val();                        
                        // check if confirm
                        if (confirm) {                       
                            $.ajax({
                                url:"{{route('remove-module')}}",
                                type:'post',
                                data:{role_id : role_id , _token: '{{csrf_token()}}', sys_module_id : $(this).attr('id')},
                                success:function(response){             
                                    //    
                                    console.warn(response);
                                    swal("Successfully removed the module.", {
                                            icon: "success",
                                    }).then(()=>{
                                        load_modules(data_modules);
                                        loadDataTable.ajax.reload()
                                        permission_table.ajax.reload()
                                    });
                                },
                                error:function(response){
                                    $(".add-btn").prop('disabled',false);
                                }
                            })
                            
                        } else {
                     
                            swal("Operation Cancelled.", {
                                icon: "error",
                            });
                        }
                    });


             });


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
                                $("#load-datatable").DataTable().ajax.reload();
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
        <h4 class="panel-title">Roles and Permissions </h4>
    </div>
    <div class="panel-body">        
        {{-- table --}}
        <table id="load-datatable" class="table table-hover text-center" style="width:100%">            
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
         <div class="modal fade bd-example-modal-lg" id="SetModulesModal">
            <div class="modal-dialog modal-lg">
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

                        <table id="permission-datatable" class="table table-hover" style="width: 100%" >            
                            <thead>
                                <tr>                 
                                    <th >Parent Module</th>   
                                    <th >Modules</th>
                                    @foreach( $get_permissions as $item )                                    
                                        <th>{{$item->permission}}</th>
                                    @endforeach
                                    <th >Action</th>
                                </tr>
                            </thead>
                            <tbody>              
                            </tbody>
                        </table>                    
                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white " data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>


      

    </div>
</div>
<!-- end panel -->
@endsection