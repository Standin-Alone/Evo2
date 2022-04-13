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
            
            background-color: #89d8d3;
		    background-image: linear-gradient(315deg, #89d8d3 0%, #03c8a8 74%);
            font-size: 20px;
            font-family: calibri
        }

        #load-datatable > thead > tr > th {
            color:white;
            font-size: 20px;            
            background-color: #89d8d3;
		    background-image: linear-gradient(315deg, #89d8d3 0%, #03c8a8 74%);
            font-weight: bold
        }
        #load-datatable> thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
        }  
        div.dataTables_wrapper div.dataTables_processing {
          background-color: rgba(0, 0, 0, 0.5);
          backdrop-filter: blur(6px);
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
            processing:true,    
            ajax: "{{route('user.show')}}",
            columns:[
                    {data:'full_name',title:'Name'},
                    {data:'role',title:"Role"},
                    {data:'shortname',title:'Program'},
                    {data:'email',title:"Email"},
                    {data:'contact_no',title:"contact_no",visible:false},
                    {data:'reg_name',title:"Region"},
                    {data:'prov_name',title:"prov_name",visible:false},
                    {data:'mun_name',title:"mun_name",visible:false},
                    {data:'bgy_name',title:"bgy_name",visible:false},
                   
                    {data:'id',
                        title:"Actions",
                        render: function(data,type,row){       
                        
                        

                            return  "<button type='button' class='btn view-modal-btn btn-outline-warning' user_id="+row['user_id']+" role_id="+row['role_id']+"  agency_name="+row['agency_name']+" agency_loc="+row['agency_loc']+" agency_id="+row['agency_id']+" reg="+row['reg']+" prov="+row['prov']+"  mun="+row['mun']+"  bgy="+row['bgy']+"   data-toggle='modal' data-target='#ViewModal'>"+
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
            ],
            "language": {
            processing: '<img src="assets/img/images/da-loading.gif" >'
        },
 
        // processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw text-success"></i><span class="sr-only">Loading...</span> '
           

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

            
            let reg = $(this).attr('reg');
            let mun = $(this).attr('mun');
            let prov = $(this).attr('prov');            
            let bgy = $(this).attr('bgy');
            


            let role = load_datatable.row(currentRow).data()['role'];
            let role_id = $(this).attr('role_id');
            
            
            
            let agency_loc = $(this).attr('agency_loc');
            
            let agency_id = $(this).attr('agency_id');


            $("input[name='edit_agency_loc'][value=" + agency_loc + "]"). prop('checked', true)
            

            $.ajax({
                url:'{{route("ac-filter-role",["agency_loc" => ":id"])}}'.replace(':id',agency_loc),
                
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#edit_role_id").prop('disabled',false);
                    $("#edit_role_id option").remove();
                    $("#edit_role_id").append('<option value="" selected disabled>Select Role</option>')
                    convertToJson.map(item => {
                        $("#edit_role_id").append('<option value="'+item.role_id+'">'+item.role+'</option>')
                    })
                     
                    $("#edit_role_id").val(role_id); 
                }                
            });



               // edit region fields
               if(agency_loc == 'CO'){
                    $("#edit_municipality").prop('selectedIndex',0);
                    $("#edit_barangay").prop('selectedIndex',0);
                    $("#edit_municipality").prop('disabled','disabled');
                    $("#edit_barangay").prop('disabled','disabled');
                    $("#edit_region").val(00).change();
                    $("#edit_region option").filter(function(){
                        return this.value != 00;
                    }).hide()

                    if($("#role option:selected").val() == 1 ){
                        $("#edit_region option").filter(function(){
                            return this.value
                        }).show()
                    }else{
                        $("#edit_region option").filter(function(){
                            return this.value == 00 ;
                        }).show()

                    }                
            }else{
                $("#edit_province").prop('selectedIndex',0);
                $("#edit_municipality").prop('selectedIndex',0);
                $("#edit_barangay").prop('selectedIndex',0);
                $("#edit_province").prop('disabled','disabled');
                $("#edit_municipality").prop('disabled','disabled');
                $("#edit_barangay").prop('disabled','disabled');
                $("#edit_region").val("").change();
                $("#edit_region option").filter(function(){
                    return this.value != 00;
                }).show()

                $("#edit_region option").filter(function(){
                    return this.value == 00;
                }).hide()


            }
            check_edit_agency = $("input[name='edit_agency_loc']:checked").val();
            if(check_edit_agency == 'CO'){
                $("#edit_region").val(00).change();
                $("#edit_region option").filter(function(){
                    return this.value != 00;
                }).hide()
            }
            
            // put values in update form
            $("#user_id").val(id);                         
            $("#edit_email").val(email);
            $("#edit_contact").val(contact);
            $("#agency_view").text(agency);
            $("#program_view").text(program);
            $("#edit_reg_name").text(reg_name);
            $("#edit_mun_name").text(mun_name);
            $("#edit_prov_name").text(prov_name);
            $("#edit_role_id").val(role_id).change();
            $("#edit_agency").val(agency_id).change();
            $("#bgy_name").text(bgy_name);            
            $("#edit_region").val(reg).change();
            $("#edit_municipality").val(mun).change();
            $("#edit_province").val(prov).change();
            $("#edit_barangay").val(bgy).change();            
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

        


        // add agency loc function
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



        // edit agency loc
        $("input[name='edit_agency_loc']").change(function(){
            let value = $(this).val();
                
            $.ajax({
                url:'{{route("ac-filter-role",["agency_loc" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){ 
         
                    let convertToJson = JSON.parse(data);
                    
                    $("#edit_role_id").prop('disabled',false);
                    $("#edit_role_id option").remove();
                    $("#edit_role_id").append('<option value="" selected disabled>Select Role</option>')
                    convertToJson.map(item => {
                        $("#edit_role_id").append('<option value="'+item.role_id+'">'+item.role+'</option>')
                    })
                }                
            }); 

            // edit region fields
            if(value == 'CO'){
                    $("#edit_municipality").prop('selectedIndex',0);
                    $("#edit_barangay").prop('selectedIndex',0);
                    $("#edit_municipality").prop('disabled','disabled');
                    $("#edit_barangay").prop('disabled','disabled');
                    $("#edit_region").val(00).change();
                    $("#edit_region option").filter(function(){
                        return this.value != 00;
                    }).hide()

                    if($("#role option:selected").val() == 1 ){
                        $("#edit_region option").filter(function(){
                            return this.value
                        }).show()
                    }else{
                        $("#edit_region option").filter(function(){
                            return this.value == 00 ;
                        }).show()

                    }                
            }else{
                $("#edit_province").prop('selectedIndex',0);
                $("#edit_municipality").prop('selectedIndex',0);
                $("#edit_barangay").prop('selectedIndex',0);
                $("#edit_province").prop('disabled','disabled');
                $("#edit_municipality").prop('disabled','disabled');
                $("#edit_barangay").prop('disabled','disabled');
                $("#edit_region").val("").change();
                $("#edit_region option").filter(function(){
                    return this.value != 00;
                }).show()

                $("#edit_region option").filter(function(){
                    return this.value == 00;
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
                    $("#region").val(00).change();
                    $("#region option").filter(function(){
                        return this.value == 00 && this.value == ""
                        }).show()    

                    $("#region option").filter(function(){
                        return this.value 
                        }).hide()                   
                }
            }
        })




        // FILTER REGION UPDATE FORM

        
        $("#role_id").change(function(){

            let value = $("option:selected",this).val();                  
            let check_agency = $("input[name='agency_loc']:checked").val();

            
            
            if(check_agency == "CO"){
                if(value == 1){
                    $("#region option").filter(function(){
                        return this.value
                        }).show()                    
                }else{
                    $("#region").val(00).change();
                    $("#region option").filter(function(){
                        return this.value == 00 && this.value == ""
                        }).show()    

                    $("#region option").filter(function(){
                        return this.value 
                        }).hide()                   
                }
            }
        })
        // filter province
        $("#edit_region").change(function(){
            let value = $("option:selected", this).val();
               
            $.ajax({
                url:'{{route("filter-province",["region_code" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#edit_province").prop('disabled',false);
                    $("#edit_province option").remove();
                    $("#edit_province").append('<option value="" selected disabled>Select Province</option>')
                    convertToJson.map(item => {
                        $("#edit_province").append('<option value="'+item.prov_code+'">'+item.prov_name+'</option>')
                    })
                }                
            });
        })

      
        // check agency of CO or RFO UPDATE
        edit_check_agency = $("input[name='edit_agency_loc']:checked").val();
        if(edit_check_agency == 'CO'){
            $("#edit_region").val(00).change();
            $("#edit_region option").filter(function(){
                return this.value != 00;
            }).hide()
        }


        
        
        // filter municipality
        $("#edit_province").change(function(){
            let value = $("option:selected", this).val();
            $.ajax({
                url:'{{route("filter-municipality",["province_code" => ":id"])}}'.replace(':id',value),
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#edit_municipality").prop('disabled',false);
                    $("#edit_municipality option").remove();
                    $("#edit_municipality").append('<option value="" selected disabled>Select Municipality</option>')
                    convertToJson.map(item => {
                        $("#edit_municipality").append('<option value="'+item.mun_code+'">'+item.mun_name+'</option>')
                    })
                }                
            });
        })



        // filter barangay
        $("#edit_municipality").change(function(){
            
            let region = $("#edit_region option:selected").val();
            let province = $("#edit_province option:selected").val();
            let value = $("option:selected",this).val();
            console.warn(value)                        
            $.ajax({
                url:'{{route("filter-barangay",["region_code" => ":id_region_code","province_code" => ":id_province_code","municipality_code" => ":id"])}}'.replace(':id_region_code',region).replace(':id_province_code',province).replace(':id',value),                
                type:'get',
                success:function(data){
                    let convertToJson = JSON.parse(data);
                    $("#edit_barangay").prop('disabled',false);
                    $("#edit_barangay option").remove();
                    $("#edit_barangay").append('<option value="" selected disabled>Select Barangay</option>')
                    convertToJson.map(item => {
                        $("#edit_barangay").append('<option value="'+item.bgy_code+'">'+item.bgy_name+'</option>')
                    })
                }                
            });
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
                            // remote:{
                            //     url:"{{route('check-email')}}",
                            //     type:'get'
                            // }                                                    
                        },
                    contact:{
                        required:true,
                        phoneUS: true
                    }, 
                    role:{
                        required:true,
                    },                    
                    agency:'required',
                    edit_agency_loc:'required',                                        
                    region:'required',                   
                },
                messages:{
                     email:{
                            required:'<div class="text-danger">Please enter your email.</div>',
                            email:'<div class="text-danger">Please enter a valid email address.</div>',                             
                            // remote:'<div class="text-danger">This email is already exist.</div>'
                            },                    
                    contact:{
                            required:'<div class="text-danger">Please enter your phone number.</div>',
                            phoneUS: '<div class="text-danger">Invalid format.</div>'
                            },
                    role:{
                        required:'<div class="text-danger">Please enter your role</div>',  
                    },     
                    agency      :{required:'<div class="text-danger">Please select agency location.</div>'},                                   
                    edit_agency_loc  :{required:'<div class="text-danger">Please select agency location.</div>'},                    
                    role        :{required:'<div class="text-danger">Please select your role.</div>'},
                    region      :{required:'<div class="text-danger">Please select region.</div>'},                   
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
                                            $("#load-datatable").DataTable().ajax.reload();
                                            $("#ViewModal").modal('hide')
                                            $(".update-btn").prop('disabled',false);                                            
                                            $(".update-btn").text('update');   
                                            $(".update-btn").html('Update');   
                                        });
                                    }else{
                                        $("i").addClass('hide');
                                        swal("Failed to update user info.", {
                                            icon: "error",
                                        }).then(()=>{
                                            $("#load-datatable").DataTable().ajax.reload();
                                            $("#ViewModal").modal('hide')
                                            $(".update-btn").prop('disabled',false);                                            
                                            $(".update-btn").text('update');    
                                            $(".update-btn").html('Update');   
                                            
                                        });
                                    }
                                },
                                error:function(response){
                                    $(".add-btn").prop('disabled',false);
                                }
                            })
                            
                        } else {
                            $("#load-datatable").DataTable().ajax.reload();
                            $(".update-btn").prop('disabled',false);                            
                            $(".update-btn").prop('disabled',false);                                            
                            $(".update-btn").text('update');    
                            $(".update-btn").html('Update');   
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



                $("#SendAccountCreationLinkForm").validate({
                rules:{
                    email:{
                            required:true,
                            email:true,
                            remote:{
                                url:"{{route('check-email')}}",
                                type:'get'
                            }
                        },
                    program: {
                        required: true
                    },
                },
                messages:{
                    email:{
                            required:'<div class="text-danger">Please enter your email.</div>',
                            email:'<div class="text-danger">Please enter a valid email address.</div>',                             
                            remote:'<div class="text-danger">This email is already exist.</div>'
                    },   
                    program     :{required:'<div class="text-danger">Please select your program.</div>'}
                },
                submitHandler:()=>{
                    $('.send-link-btn').prop('disbled',true)
                    swal({
                    title: "Wait!",
                    text: "Are you sure you want to send accoun creation link?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                })
                .then((result) => {


                    if(result){

                        $('.send-link-btn').prop('disabled',true)
                        $('.send-link-btn').html('<i class="fas fa-spinner fa-spin"/> Sending')
                        $.ajax({
                            url:"{{route('send-account-creation-link')}}",
                            type:'post',
                            data:$("#SendAccountCreationLinkForm").serialize(),
                            success:function(response){       
                                parses_result = JSON.parse(response);


                                swal(parses_result['message'], {
                                    icon:parses_result['result'],
                                });

                                $("#SendAccountCreationLinkForm")[0].reset();
                                

                                $('.send-link-btn').prop('disabled',false)
                                $('.send-link-btn').html('Send Account Creation Link')

                            }                                                
                        });
                    }
                   

                                


                                
                });

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
<div class="panel  ">
    <div class="panel-heading  gradient-bg">
        {{-- <h4 class="panel-title">Panel Title here</h4> --}}
        <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
            <i class='fa fa-plus'></i> Add New
        </button>

        <button type='button' class='btn btn-primary'data-toggle='modal' data-target='#AddByEmailModal' >
            <i class='fa fa-plus'></i> Add New User By Email
        </button>
        
        <button type='button' class='btn btn-info' data-toggle='modal' data-target='#ImportModal' >
            <i class='fa fa-file-excel'></i> Import File
        </button>
    </div>
    <div class="panel-body">

   
        <div class="panel ">
            <div class="panel-heading  gradient-bg text-white">Filter by Region</div>
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


         <!-- #modal-view VIEW PROFILE-->
         <div class="modal fade" id="ViewModal">
            <div class="modal-dialog" style="max-width: 40%">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #f59c1a">
                        <h4 class="modal-title" style="color: white">Update Profile</h4>
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
                                    <label style="display: block; text-align: center; font-weight:bold;  font-size:24px" id="name"></label>
                                    <label style="display: block; text-align: center; font-weight:bold;  font-size:20px" id="role_view"></label>
                                </div>
                            </div>


                    
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

                        
                            </div>

                            <div class="col-lg-12 row">
                                <div class="form-group">
                                    <label>Email</label><span style="color:red">*</span>
                                    <input    type="email" name="email" id="edit_email" class="form-control"  placeholder="example@gmail.com" >
                                </div>&nbsp;&nbsp;
                                <div class="form-group">
                                    <label>Contact</label><span style="color:red">*</span>
                                    <input    type="number" name="contact" id="edit_contact" class="form-control"  placeholder="9102...." >
                                </div>
                            </div>


                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Agency</label> <span style="color:red">*</span>
                                    <select class="form-control" id="edit_agency" name="agency" >
                                        <option selected disabled value="">Select Agency</option>
                                        @foreach ($get_agency as $item)
                                            <option  value="{{$item->agency_id}}">{{$item->agency_name}}</option>
                                        @endforeach
                                    </select>
                                </div>                              
                            </div><br>
                            
                            <div class="col-lg-12 row ">
                                <label class="col-md-12 row">Agency <span style="color:red">*</span></label> 
                                <div class="col-md-12  row">
                                    <div class="form-check ">
                                        <input class="form-check-input" type="radio" id="defaultRadio1" name="edit_agency_loc"  value="CO" checked  />
                                        <label class="form-check-label" for="defaultRadio1">Central Office</label>
                                    </div> &nbsp; &nbsp;                       
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="defaultRadio2" name="edit_agency_loc" value="RFO" />
                                        <label class="form-check-label" for="defaultRadio2">Regional Field Office</label>
                                    </div>       
                                </div>                       
                            </div><br>


                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Role</label> <span style="color:red">*</span>
                                    <select class="form-control" name="role_id" id="edit_role_id" >
                                        <option selected disabled value="">Select Role</option>    
                                        @foreach ($get_roles as $item)
                                            <option  value="{{$item->role_id}}">{{$item->role}}</option>
                                        @endforeach                                
                                    </select>
                                </div>                              
                            </div>                        
                                          

                            <div class="col-lg-12 row ">
                                <div class="form-group" style="width:95%">
                                    <label >Region</label> <span style="color:red">*</span>
                                    <select class="form-control" id="edit_region" name="region" >
                                        <option selected disabled value="">Select Region</option>
                                        @foreach ($get_regions as $item)
                                            <option value="{{$item->reg_code}}">{{$item->reg_name}}</option>
                                        @endforeach
                                    </select>
                                </div>                              
                            </div>

                            <div class="col-lg-12 row  hide">
                                <div class="form-group" style="width:95%">
                                    <label >Province</label> <span style="color:red">*</span>
                                    <select class="form-control" id="edit_province" name="province" disabled >
                                        <option selected disabled value="">Select Province</option>
                                    </select>
                                </div>                              
                            </div>

                            <div class="col-lg-12 row  hide">
                                <div class="form-group" style="width:95%">
                                    <label >Municipality</label> <span style="color:red">*</span>
                                    <select class="form-control" id="edit_municipality" name="municipality" disabled >
                                        <option selected disabled value="">Select Municipality</option>
                                    </select>
                                </div>                              
                            </div>

                            <div class="col-lg-12 row  hide">
                                <div class="form-group" style="width:95%">
                                    <label >Barangay</label> <span style="color:red">*</span>
                                    <select class="form-control" id="edit_barangay" name="barangay" disabled     >
                                        <option selected disabled value="">Select Barangay</option>
                                    </select>
                                </div>                              
                            </div>

                            <input type="text" id="update-role" name="role" class="form-control hide"   required="true" >                                              

                          

                                
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



        <!-- #modal-list of not inserted data to database from excel -->
        <div class="modal fade" id="AddByEmailModal"  data-backdrop="static" data-keyboard="false">
            <form id="SendAccountCreationLinkForm">
                @csrf
                <div class="modal-dialog">                    
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title update-modal-title" style="color: white">Add User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                            </div>
                            <div class="modal-body">
                                {{--modal body start--}}          
                                <div class="col-lg-12">
                                    <div class="form-group" >
                                        <label >Program</label> <span style="color:red">*</span>
                                        <select class="form-control" name="program" id="program" >
                                            <option selected disabled value="">Select Program</option>                                    
                                            @foreach ($get_programs as $item)
                                                <option value="{{$item->program_id}}">{{$item->shortname}} ({{$item->description}})</option>
                                            @endforeach
                                        </select>
                                    </div>                              
                                </div> 
                                <div class="col-lg-12 ">
                                    <div class="form-group">
                                        <label>Email</label><span style="color:red">*</span>
                                        <input    type="email" name="email" id="email" class="form-control border-info col-lg-12"  placeholder="example@gmail.com" >
                                </div>
                                {{--modal body end--}}
                            </div>
                            <div class="modal-footer">
                                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary send-link-btn">Send Account Creation Link</button>
                            </div>
                        </div>                    
                </div>
            </form>
        </div>


        
    </div>
</div>
<!-- end panel -->
@endsection