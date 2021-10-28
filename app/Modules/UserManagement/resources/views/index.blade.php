@extends('global.base')
@section('title', "User Management")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />

    <style>
        
        dd{
            font-size: 20
        }
        td { font-size: 17px; font-weight: 500 }

        
        #load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }

        #load-datatable > thead > tr > th {
            color:white;
            font-size: 20px;
            background-color: #008a8a;
            font-weight: bold
        }
        #load-datatable> thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
        }  

        /* MODIFY DATATABLE WRAPPER/MOBILE VIEW NAVAGATE ROW ICON */
        .dataTables_wrapper table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before{
            /* background: #008a8a !important; */
            background: #008a8a !important;
            border-radius: 10px !important;
            border: none !important;
            top: 18px !important;
            left: 5px !important;
            line-height: 16px !important;
            box-shadow: none !important;
            color: #fff !important;
            font-weight: 700 !important;
            height: 16px !important;
            width: 16px !important;
            text-align: center !important;
            text-indent: 0 !important;
            font-size: 14px !important;
        }
        
        .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td:first-child:before, 
        .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th:first-child:before{
            /* background: #008a8a !important; */
            background: #b31515 !important;
            border-radius: 10px !important;
            border: none !important;
            top: 18px !important;
            left: 5px !important;
            line-height: 16px !important;
            box-shadow: none !important;
            color: #fff !important;
            font-weight: 700 !important;
            height: 16px !important;
            width: 16px !important;
            text-align: center !important;
            text-indent: 0 !important;
            font-size: 14px !important;
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
        load_datatable = $("#load-datatable").DataTable({
            serverSide:true,
            responsive:true,
            ajax: "{{route('user.show')}}",
            columns:[
                    {data:'full_name',title:'Name'},
                    {data:'role',title:"Role"},
                    {data:'shortname',title:'Program'},
                    {data:'email',title:"email",visible:false},
                    {data:'contact_no',title:"contact_no",visible:false},
                    {data:'reg_name',title:"Region"},
                    {data:'prov_name',title:"prov_name",visible:false},
                    {data:'mun_name',title:"mun_name",visible:false},
                    {data:'bgy_name',title:"bgy_name",visible:false},
                   
                    {data:'id',
                        title:"Actions",
                        render: function(data,type,row){       
                        
                        

                            return  "<button type='button' class='btn view-modal-btn btn-outline-warning'   user_id="+row['user_id']+" data-toggle='modal' data-target='#ViewModal'>"+
                                        "<i class='fa fa-edit'></i> Edit"+
                                    "</button>   "+(
                                    row['status'] == 1 ?
                                    "<button type='button' class='btn btn-outline-danger set-status-btn ' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                                        "<i class='fa fa-trash'></i> Disable"+
                                    "</button>  " :
                                    "<button type='button' class='btn btn-outline-success set-status-btn' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                                        "<i class='fa fa-undo'></i> Enable"+
                                    "</button> ")+(
                                    row['status'] == 2 ?
                                    "<button type='button' class='btn btn-outline-success set-block-btn ' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                                        "<i class='fa fa-trash'></i> Unblock"+
                                    "</button>  " :
                                    "<button type='button' class='btn btn-outline-primary set-block-btn' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                                        "<i class='fa fa-ban'></i> Block"+
                                    "</button> ")
                        }
                    }
            ]

        })


        $("#load-datatable").on('click','.view-modal-btn',function(){
            
            let currentRow = $(this).closest('tr');
            let id =  $(this).attr('user_id');
            let full_name = load_datatable.row(currentRow).data()['full_name'];
            let email = load_datatable.row(currentRow).data()['email'];
            let contact = load_datatable.row(currentRow).data()['contact_no'];
            let program = load_datatable.row(currentRow).data()['shortname'];
            let agency = load_datatable.row(currentRow).data()['agency'];
            let reg_name = load_datatable.row(currentRow).data()['reg_name'];
            let mun_name = load_datatable.row(currentRow).data()['mun_name'];
            let prov_name = load_datatable.row(currentRow).data()['prov_name'];
            let bgy_name = load_datatable.row(currentRow).data()['bgy_name'];

            let role = load_datatable.row(currentRow).data()['role'];
         
            $("#user_id").val(id);
            
            $("#email").val(email);
            $("#contact").val(contact);
            $("#agency_view").text(agency);
            $("#program_view").text(program);
            $("#reg_name").text(reg_name);
            $("#mun_name").text(mun_name);
            $("#prov_name").text(prov_name);
            $("#bgy_name").text(bgy_name);

            $("#name").text(full_name);
            $("#role_view").text(role);
            $("#update-role").val(role);
            

      

            
        });

         // set status btn
         $("#load-datatable").on('click','.set-status-btn',function(){
            id = $(this).attr('id');
            status = $(this).attr('status');
                                    
            swal({
                    title: "Wait!",
                    text: "Are you sure you want to "+ (status == 1 ? 'disable' : 'enable')+" this user?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    $('#add-btn').prop('disabled','true');
                    // check if confirm
                    if (confirm) {                       
                        $.ajax({
                            url:'{{route("user.destroy",["id"=>":id"])}}'.replace(':id',id),
                            type:'get',
                            data:{'_token':'{{csrf_token()}}','status':status},
                            success:function(response){             
                                //    
                                
                                swal("Successfully "+(status == 1 ? 'disable' : 'enable')+" the user.", {
                                    icon: "success",
                                }).then(()=>{                    
                                    
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
        }); 


        // set block btn
        $("#load-datatable").on('click','.set-block-btn',function(){
            id = $(this).attr('id');
            status = $(this).attr('status');
                                    
            swal({
                    title: "Wait!",
                    text: "Are you sure you want to "+ (status == 1 ? 'unblocked' : 'blocked')+" this user?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((confirm) => {
                    $('#add-btn').prop('disabled','true');
                    // check if confirm
                    if (confirm) {                       
                        $.ajax({
                            url:'{{route("user.block",["id"=>":id"])}}'.replace(':id',id),
                            type:'get',
                            data:{'_token':'{{csrf_token()}}','status':status},
                            success:function(response){             
                                //    
                                
                                swal("Successfully "+(status == 2 ? 'unblocked' : 'blocked')+" the user.", {
                                    icon: "success",
                                }).then(()=>{                    
                                    
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
        }); 



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

        // check agency of CO or RFO
        check_agency = $("input[name='agency_loc']:checked").val();
        if(check_agency == 'CO'){
            $("#region").val(13).change();
            $("#region option").filter(function(){
                return this.value != 13;
            }).hide()
        }


        
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
            
            let region = $("#region option:selected").val();
            let province = $("#province option:selected").val();
            let value = $("option:selected",this).val();
            console.warn(value)                        
            $.ajax({
                url:'{{route("filter-barangay",["region_code" => ":id_region_code","province_code" => ":id_province_code","municipality_code" => ":id"])}}'.replace(':id_region_code',region).replace(':id_province_code',province).replace(':id',value),                
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
            

            
            if(value == 'CO'){
                    $("#municipality").prop('selectedIndex',0);
                    $("#barangay").prop('selectedIndex',0);
                    $("#municipality").prop('disabled','disabled');
                    $("#barangay").prop('disabled','disabled');
                    $("#region").val(13).change();
                    $("#region option").filter(function(){
                        return this.value != 13;
                    }).hide()

                    if($("#role option:selected").val() == 1 ){
                        $("#region option").filter(function(){
                            return this.value
                        }).show()
                    }else{
                        $("#region option").filter(function(){
                            return this.value == 13 ;
                        }).show()

                    }                
            }else{
                $("#province").prop('selectedIndex',0);
                $("#municipality").prop('selectedIndex',0);
                $("#barangay").prop('selectedIndex',0);
                $("#province").prop('disabled','disabled');
                $("#municipality").prop('disabled','disabled');
                $("#barangay").prop('disabled','disabled');
                $("#region").val("").change();
                $("#region option").filter(function(){
                    return this.value != 13;
                }).show()

                $("#region option").filter(function(){
                    return this.value == 13;
                }).hide()


            }

        })

        $("#role").change(function(){

            let value = $("option:selected",this).val();                  
            let check_agency = $("input[name='agency_loc']:checked").val();

            
            
            if(check_agency == "CO"){
                if(value == 1){
                    $("#region option").filter(function(){
                        return this.value
                        }).show()                    
                }else{
                    $("#region").val(13).change();
                    $("#region option").filter(function(){
                        return this.value == 13 && this.value == ""
                        }).show()    

                    $("#region option").filter(function(){
                        return this.value 
                        }).hide()                   
                }
            }
        })




    })

    </script>



    <script>
        $(document).ready(function(){

            // Add User
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
                    program: {
                        required: {
                            depends: function (){
                                if($("#role option:selected").val() != 2){
                                    return true
                                }
                            }
                        }
                    },
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
                            $(".add-btn").html('<i class="fas fa-circle-notch fa-spin"></i> Add');                 
                            $.ajax({
                                url:"{{route('user-add')}}",
                                type:'post',
                                data:$("#AddForm").serialize(),
                                success:function(response){             
                                    //    
                                    console.warn(response);
                                    if(response == 'true'){
                                        swal("Successfully added new user.", {
                                            icon: "success",
                                        }).then(()=>{
                                            $(".add-btn").html('Add'); 
                                            $("#load-datatable").DataTable().ajax.reload();
                                            $("#AddModal").modal('hide')
                                            $(".add-btn").prop('disabled',false);
                                            $("#AddForm")[0].reset();
                                        });
                                    }else{
                                        swal("Failed to add new user.", {
                                            icon: "error",
                                        }).then(()=>{
                                            $(".add-btn").html('Add');                                 
                                            $(".add-btn").prop('disabled',false);
                                            
                                        });
                                    }
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



            // import file
            $("#ImportForm").validate({

                rules:{
                    import_region: "required",
                    import_program: "required",
                    file:{
                        required:true,
                        accept: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                    }
                },
                messages:{
                    import_region: '<div class="text-danger">Please select region.</div>',
                    import_program: '<div class="text-danger">Please select program.</div>',
                    file:{
                        required: '<div class="text-danger">Please select file to upload.</div>',
                        accept: '<div class="text-danger">Please upload valid files formats .xlsx, . xls only.</div>'
                    }
                },
                submitHandler: function(){
                    let fd = this;
                    swal({
                    title: "Wait!",
                    text: "Are you sure you want to import this file?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                    })
                    .then((confirm) => {
                        let fd = new FormData();

                        fd.append('_token','{{csrf_token()}}')
                        fd.append('import_region',$("#import_region").val())
                        fd.append('import_program',$("#import_program").val())
                        fd.append('file',$("input[name='file']")[0].files[0])
                        $(".import-btn").prop('disabled',true)
                        // check if confirm
                        if (confirm) {                       
                            $.ajax({
                                url:"{{route('import-file')}}",
                                type:'post',
                                data: fd,
                                processData:false,
                                contentType:false,
                                success:function(response){             
                                    //              
                                    console.warn(response);
                                    parses_result = JSON.parse(response)
                                    total_rows_inserted = parses_result['total_rows_inserted'];
                                    total_rows = parses_result['total_rows'];
                                
                                if(parses_result['message'] == 'true'){
                                    swal(total_rows_inserted + ' out of ' + total_rows + ' rows has been successfully inserted.', {
                                        icon: "success",
                                    }).then(()=>{                    
                                            
                                        // check if it has error data;
                                        if(parses_result['error_data'].length > 0 ){
                                            $("#ErrorDataModal").modal('show');
                                            $("#error-datatable").DataTable({
                                                destroy:true,
                                                data:parses_result['error_data'],
                                                columns:[
                                                                                                        
                                                    {title:'Name',orderable:false,render:function(data,type,row){
                                                        return row.first_name + ' ' + row.last_name;
                                                    }},                                                    
                                                    {data:'agency',title:'Agency'},
                                                    {data:'email',title:'Email',orderable:false},
                                                    {data:'contact',title:'Contact',orderable:false}, 
                                                    // {data:'barangay',title:'Email',orderable:false},
                                                    // {data:'province',title:'Province',orderable:false},
                                                    // {data:'municipality',title:'Municipality',orderable:false},
                                                    {data:'region',title:'Region',orderable:false},

                                                    {data:'remarks',title:'Remarks',orderable:false},
                                                    
                                                    

                                                ]
                                            });
                                        }

                                        $("#ImportForm")[0].reset();
                                        $(".import-btn").prop('disabled',false)      
                                        $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Import');                                 
                                        $("#load-datatable").DataTable().ajax.reload();
                                    });
                                    }else{
                                        swal("Error!Wrong excel format.", {
                                                icon: "error",
                                            });
                                        $("#ImportForm")[0].reset();
                                        $("#load-datatable").DataTable().ajax.reload();
                                        $("#ImportModal").modal('hide')
                                        $(".import-btn").prop('disabled',false)
                                    }


                                    // swal("Successfully added new users.", {
                                    //         icon: "success",
                                    // }).then(()=>{
                                    //     $("#load-datatable").DataTable().ajax.reload();
                                    //     $("#ImportModal").modal('hide')
                                    //     $(".import-btn").prop('disabled',false)
                                        
                                    // });
                                },
                                error:function(response){
                                    $("#ImportModal").modal('hide')
                                    console.warn(response);
                                    $(".import-btn").prop('disabled',false)
                                }   
                            })
                            
                        } else {
                            $(".import-btn").prop('disabled',false)                            
                            swal("Operation Cancelled.", {
                                icon: "error",
                            });
                        }
                    });
                }
            })

            // Update Form validate
            $("#UpdateForm").validate({
                rules:{
                    email:{
                            required:true,
                            email   :true,
                            remote  :{
                                url:"{{route('check-email')}}",
                                type:'get'
                            }
                        },
                    contact:{
                        required:true,
                        phoneUS: true
                    }, 
                },
                messages:{
                     email:{
                            required:'<div class="text-danger">Please enter your email.</div>',
                            email:'<div class="text-danger">Please enter a valid email address.</div>', 
                            remote:'<div class="text-danger">This email is already exist.</div>'
                            },                    
                    contact:{
                            required:'<div class="text-danger">Please enter your phone number.</div>',
                            phoneUS: '<div class="text-danger">Invalid format.</div>'
                            },
                },
                submitHandler:function(){

                    $("i").removeClass('hide');
                    $(".update-btn").prop('disabled',true);   
                    

                    swal({
                    title: "Wait!",
                    text: "Are you sure you want to update this records?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                    })
                    .then((confirm) => {
                        $(".update-btn").html('<i class="fas fa-circle-notch fa-spin"></i> Updating');   
                        if (confirm) {                       
                            $.ajax({
                                url:"{{route('user-update')}}",
                                type:'post',
                                data:$("#UpdateForm").serialize(),
                                success:function(response){             
                                    //          
                                    console.warn(response);
                                    if(response == 'true'){
                                        $("i").addClass('hide');
                                       
                                        swal("Successfully updated the user info.", {
                                            icon: "success",
                                        }).then(()=>{
                                            load_datatable.ajax.reload();
                                            $("#ViewModal").modal('hide')
                                            $(".update-btn").prop('disabled',false);                                            
                                            $(".update-btn").text('update');   
                                        });
                                    }else{
                                        $("i").addClass('hide');
                                        swal("Failed to update user info.", {
                                            icon: "error",
                                        }).then(()=>{
                                            
                                            $(".update-btn").prop('disabled',false);
                                            $(".update-btn").text('Update');   
                                            
                                        });
                                    }
                                },
                                error:function(response){
                                    $(".add-btn").prop('disabled',false);
                                }
                            })
                            
                        } else {
                            $(".update-btn").prop('disabled',false);
                            swal("Operation Cancelled.", {
                                icon: "error",
                            });
                        }
                    });
                  

                }
            });


            // filter by region
            $("#filter-region").change(function(){
                    region_code = $("option:selected",this).val();
                    region_name = $("option:selected",this).text();
                 
                    if(region_code == ""){
                        $("#load-datatable").DataTable().column(5).search('').draw();
                        
                    }else{
                        $("#load-datatable").DataTable().column(5).search(region_name).draw();
                        
                    }
                     
                });
            
        })

    </script>
@endsection










@section('content')

<!-- begin page-header -->
<h1 class="page-header">User Management</h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-success ">
    <div class="panel-heading ">
        {{-- <h4 class="panel-title">Panel Title here</h4> --}}
        <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
            <i class='fa fa-plus'></i> Add New
        </button>

        <button type='button' class='btn btn-info' data-toggle='modal' data-target='#ImportModal' >
            <i class='fa fa-file-excel'></i> Import File
        </button>
    </div>
    <div class="panel-body">

   
        <div class="panel panel-primary ">
            <div class="panel-heading">Filter by Region</div>
            <div class="panel-body border">
                <div class="form-group">
                <label for=""></label>
                <select  class="form-control filter-select" name="filter_region" id="filter-region">
                    <option value=""  selected>-- Select Region --</option>                        
                    @foreach ($get_regions as $value)
                    <option value="{{$value->reg_code}}">{{$value->reg_name}}</option>                        
                        
                    @endforeach

                </select>
                </div>
            </div>
        </div>

        
        <table id="load-datatable" class="table table-striped table-bordered table-hover text-center" style="width:100%;">            
            <thead>
             
            </thead>
            <tbody>                
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

                            <div class="col-lg-12 row program-group ">
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
            <div class="modal-dialog" style="max-width: 40%">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #f59c1a">
                        <h4 class="modal-title" style="color: white">View Profile</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <form id="UpdateForm" method="post">
                        @csrf
                        <input type="text" id="user_id" name="id" hidden>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <h2 id="ViewCategName" align="center"></h2>

                            
                            <div class="note note-success">
                                <div class="note-icon"><i class="fas fa-user"></i></div>
                                <div class="note-content">
                                    <label style="display: block; text-align: center; font-weight:bold;  font-size:24px" id="name">John Edcel Zenarosa</label>
                                    <label style="display: block; text-align: center; font-weight:bold;  font-size:20px" id="role_view">CO Banner Program</label>
                                </div>
                            </div>


                            
                                <br>
                                <br>
                            <div class="col-lg-12">
                                <div class="row">
                                    <dl class="dl-horizontal">                                
                                        <dt class="text-inverse">Agency</dt>
                                        <dd  id="agency_view" ></dd>
                                    </dl>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <dl class="dl-horizontal">                                
                                        <dt class="text-inverse">Program</dt>
                                        <dd  id="program_view" ></dd>
                                    </dl>
                                </div>

                                <div class="row">
                                    <dl class="dl-horizontal">                                
                                        <dt class="text-inverse">Region</dt>
                                        <dd  id="reg_name" ></dd>
                                        <dt class="text-inverse">Province</dt>
                                        <dd id="prov_name" ></dd>                                
                                        <dt class="text-inverse">Municipality</dt>
                                        <dd id="mun_name"></dd>
                                        <dt class="text-inverse">Barangay</dt>
                                        <dd id="bgy_name"></dd>
                                    </dl>
                                </div>
                            </div>

                            <input type="text" id="update-role" name="role" class="form-control hide"   required="true" >                                              

                            <div class="col-lg-12 row">                            
                                <div class="form-group">
                                    <label>Email</label>                                       
                                        <input type="email" id="email" name="email" class="form-control"   required="true" >                                              
                                </div>&nbsp;&nbsp;&nbsp;  

                                <div class="form-group">
                                    <label>Contact</label>
                                        <input type="number" id="contact" name="contact" class="form-control"  required="true" >                                                        
                                </div>
                            </div>

                                
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-warning update-btn">    Update</button>
                        </div>
                    </form>
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
                            <button type="submit" href="javascript:;" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



         <!-- #modal-Import File -->
         <div class="modal fade" id="ImportModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="ImportForm" method="POST"  >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #3a92ab">
                            <h4 class="modal-title" style="color: white">Import File</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <label class="form-label hide"> ID</label>                            
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>File </label>
                                    <input type="file" class="form-control" accept=".xlsx" name="file">
                                </div>
                            </div>
                           


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label >Region</label> <span style="color:red">*</span>
                                    <select class="form-control" id="import_region" name="import_region" >
                                        <option selected disabled value="">Select Region</option>
                                        @foreach ($get_regions as $item)
                                            <option value="{{$item->reg_code}}">{{$item->reg_name}}</option>
                                        @endforeach
                                    </select>      
                                </div>                          
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label >Program</label> <span style="color:red">*</span>
                                    <select class="form-control" name="import_program" id="import_program" >
                                        <option selected disabled value="">Select Program</option>                                    
                                        @foreach ($get_programs as $item)
                                            <option value="{{$item->program_id}}">{{$item->shortname}} ({{$item->description}})</option>
                                        @endforeach
                                    </select>
                                </div>                              
                            </div>    

                           
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-info import-btn"><i class="fas fa-cloud-download-alt "></i>Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <!-- #modal-list of not inserted data to database from excel -->
        <div class="modal fade" id="ErrorDataModal"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="max-width: 70%">                    
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #ff5b57">
                            <h4 class="modal-title update-modal-title" style="color: white">Unsuccessful Imported Data</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}          

                            <table id="error-datatable" class="table table-hover" style="width:100%">            
                                <thead>                                        
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