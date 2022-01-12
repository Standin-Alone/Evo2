@extends('global.base')
@section('title', "Supplier Profile")

{{--  import in this section your css files--}}
@section('page-css')
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css" rel="stylesheet">
<link href="assets/pgv/backend-style.css" rel="stylesheet">
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
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/pgv/backend-script.js"></script>

<script type="text/javascript">
    $(document).ready(function() {   

        SupplierProfileList();
        
        function SupplierProfileList(){             
            var table = $('#SupplierProfileList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,   
                    scrollY: "350px",
                    ajax: "{{ route('get.SupplierProfileList') }}",
                    columns: [
                        {data: 'supplier_name', name: 'supplier_name', title:'SUPPLIER NAME'}, 
                        {data: 'address', name: 'address', title:'ADDRESS'}, 
                        {data: 'supplier_group', name: 'supplier_group', title:'MAIN BRANCH'}, 
                        {data: 'bank_account_no', name: 'bank_account_no', title:'ACCOUNT NO.'},
                        {data: 'email', name: 'email', title:'EMAIL ACCOUNT'},
                        {data: 'contact', name: 'contact', title:'CONTACT NO.'},
                        {data: 'supplier_id', name: 'supplier_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return  '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-success View_SupplierProfile_Button" data-toggle="tooltip" data-placement="top" title="View Details"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-info Edit_SupplierProfile_Button" data-toggle="tooltip" data-placement="top" title="Edit Details"><span class="fa fa-edit"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-danger Remove_SupplierProfile_Button" data-toggle="tooltip" data-placement="top" title="Remove Details"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>';
                            }
                        }
                    ],
                });
        }

        function SupplierGroup(supplier_group_id){
            var _token = $("input[name=token]").val();
            $('.suppliergroup_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierGroup') }}",
                data:{_token:_token},
                success:function(data){        
                    $('.option_SupplierGroup').remove();
                    $('.selectSupplierGroup').append($('<option class="option_SupplierGroup" value="" selected>-- Select --</option>'));                 
                    for(var i=0;i<data.length;i++){ 
                        $('.selectSupplierGroup').append($('<option>', {class:'option_SupplierGroup',value:data[i].supplier_group_id, text:data[i].group_name}));
                    }
                    if(supplier_group_id != ""){
                        $('.selectSupplierGroup').val(supplier_group_id);
                    }                    
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        function SupplierRegion(reg_code){
            var _token = $("input[name=token]").val();
            $('.reg_val').remove();
            $('.prov_val').remove();
            $('.mun_val').remove();
            $('.bgy_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierRegion') }}",
                data:{_token:_token},
                success:function(data){ 
                    $('.reg_val').remove();                         
                    for(var i=0;i<data.length;i++){ 
                        $('.selectSupplierRegion').append($('<option>', {class:'reg_val',value:data[i].reg_code, text:data[i].reg_name}));
                    }
                    
                    if(reg_code != ""){
                        $('.selectSupplierRegion').val(reg_code);
                    }  
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        function SupplierProvince(reg_code,prov_code){
            var _token = $("input[name=token]").val();
            $('.prov_val, .mun_val, .bgy_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierProvince') }}",
                data:{reg_code:reg_code,_token:_token},
                success:function(data){           
                    $('.prov_val').remove();            
                    for(var i=0;i<data.length;i++){                            
                        $('.selectSupplierProvince').append($('<option>', {class:'prov_val',value:data[i].prov_code, text:data[i].prov_name}));
                    }
                    if(reg_code != "" || prov_code != ""){
                        $('.selectSupplierProvince').val(prov_code);
                    }  
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        function SupplierMunicipality(reg_code,prov_code,mun_code){
            var _token = $("input[name=token]").val();
            $('.mun_val, .bgy_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierMunicipality') }}",
                data:{reg_code:reg_code,prov_code:prov_code,_token:_token},
                success:function(data){
                    $('.mun_val').remove(); 
                    for(var i=0;i<data.length;i++){
                        $('.selectSupplierCity').append($('<option>', {class:'mun_val',value:data[i].mun_code, text:data[i].mun_name}));
                    }
                    if(reg_code != "" || prov_code != "" || mun_code != ""){
                        $('.selectSupplierCity').val(mun_code);
                    }  
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        function SupplierBarangay(reg_code,prov_code,mun_code,bgy_code){
            var _token = $("input[name=token]").val();
            $('.bgy_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierBarangay') }}",
                data:{reg_code:reg_code,prov_code:prov_code,mun_code:mun_code,_token:_token},
                success:function(data){
                    $('.bgy_val').remove();
                    for(var i=0;i<data.length;i++){
                        $('.selectSupplierBarangay').append($('<option>', {class:'bgy_val',value:data[i].bgy_code, text:data[i].bgy_name}));
                    }
                    if(reg_code != "" || prov_code != "" || mun_code != "" || bgy_code != ""){
                        $('.selectSupplierBarangay').val(bgy_code);
                    }  
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        function selectOwnerBank(supplier_Bank){
            var _token = $("input[name=token]").val();
            $('.bank_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierBank') }}",
                data:{_token:_token},
                success:function(data){                         
                    for(var i=0;i<data.length;i++){
                        $('.selectOwnerBankName').append($('<option>').val(data[i].shortname).text(data[i].name).data({banklongname: data[i].name}));
                    }
                    if(supplier_Bank != "" ){
                        $('.selectOwnerBankName').val(supplier_Bank);
                    }  
                },
                error:function(){
                    console.log('Err');
                }
            });
        }        

        $(document).on('change','.selectSupplierRegion',function(){
            var reg_code=$(this).val();
            SupplierProvince(reg_code,"");
        });

        $(document).on('change','.selectSupplierProvince',function(){
            var reg_code=$('.selectSupplierRegion').val(),
                prov_code=$('.selectSupplierProvince').val();                
                SupplierMunicipality(reg_code,prov_code,"");
        });
        
        $(document).on('change','.selectSupplierCity',function(){
            var reg_code=$('.selectSupplierRegion').val(),
                prov_code=$('.selectSupplierProvince').val(),
                mun_code=$('.selectSupplierCity').val();                
                SupplierBarangay(reg_code,prov_code,mun_code,"");
        });

        function ViewSupplierProfileDetails(supplier_id){
            var _token = $("input[name=token]").val();
            $.ajax({
                type:'post',
                url:"{{ route('get.SupplierProfileDetails') }}",
                data:{supplier_id:supplier_id,_token:_token},
                success:function(data){                    
                    for(var i=0;i<data.length;i++){   
                        // return alert(AddZero(data[i].brgy));                     
                        SupplierGroup(data[i].supplier_group_id);
                        $('.txtSupplierName').val(data[i].supplier_name);
                        $('.txtSupplierAddress').val(data[i].address);
                        $('.txtSupplierBusinessPermit').val(data[i].business_permit);
                        $('.txtSupplierEmail').val(data[i].email);
                        $('.txtSupplierContact').val(data[i].contact);
                        $('.txtOwnerFirstName').val(data[i].owner_first_name);
                        $('.txtOwnerMiddleName').val(data[i].owner_middle_name);
                        $('.txtOwnerLastName').val(data[i].owner_last_name);
                        $('.txtOwnerExtName').val(data[i].owner_ext_name);
                        $('.txtOwnerAcctName').val(data[i].bank_account_name);
                        $('.txtOwnerAcctNo').val(data[i].bank_account_no);
                        $('.txtOwnerPhoneNo').val(data[i].owner_phone);
                        selectOwnerBank(data[i].bank_short_name);
                        SupplierRegion(AddZero(data[i].reg));
                        SupplierProvince(AddZero(data[i].reg),AddZero(data[i].prv));
                        SupplierMunicipality(AddZero(data[i].reg),AddZero(data[i].prv),AddZero(data[i].mun));
                        SupplierBarangay(AddZero(data[i].reg),AddZero(data[i].prv),AddZero(data[i].mun),AddZero2(data[i].brgy));
                    }
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        $(document).on('click','.Create_Supplier_Button',function(){
            SpinnerShow('Create_Supplier_Button','btnloadingIcon');            
            SupplierGroup();
            SupplierRegion();
            selectOwnerBank();
            clearObjects();
            $('.Update_SupplierProfile_Button, .EditDetails_SupplierProfile_Button').css('display','none');
            $('.Save_SupplierProfile_Button').css('display','block');
            $('#Form_SupplierProfile_Modal').modal('toggle');  
            SpinnerHide('Create_Supplier_Button','btnloadingIcon');
        });

        $(document).on('click','.View_SupplierProfile_Button',function(){
            SpinnerShow('View_SupplierProfile_Button','btnloadingIcon'); 
            var supplier_id = $(this).data('selectedsupplierid');
            $('#selectedsupplierid').val(supplier_id);
            clearObjects();
            ViewSupplierProfileDetails(supplier_id);
            $('.EditDetails_SupplierProfile_Button').css('display','block');
            $('.Save_SupplierProfile_Button, .Update_SupplierProfile_Button').css('display','none');
            $('#Form_SupplierProfile_Modal').modal('toggle');  
            SpinnerHide('View_SupplierProfile_Button','btnloadingIcon');
        });

        $(document).on('click','.Edit_SupplierProfile_Button',function(){
            var supplier_id = $(this).data('selectedsupplierid');
            $('#selectedsupplierid').val(supplier_id);
            SpinnerShow('Edit_SupplierProfile_Button',supplier_id);
            $('.Save_SupplierProfile_Button, .EditDetails_SupplierProfile_Button').css('display','none');
            $('.Update_SupplierProfile_Button').css('display','block');
            clearObjects();
            ViewSupplierProfileDetails(supplier_id);
            $('#Form_SupplierProfile_Modal').modal('toggle');  
            SpinnerHide('Edit_SupplierProfile_Button',supplier_id);
        });

        $(document).on('click','.EditDetails_SupplierProfile_Button',function(){
            var supplier_id = $(this).data('selectedsupplierid');
            SpinnerShow('EditDetails_SupplierProfile_Button',supplier_id);
            $('.Save_SupplierProfile_Button, .EditDetails_SupplierProfile_Button').css('display','none');
            $('.Update_SupplierProfile_Button').css('display','block');
            SpinnerHide('EditDetails_SupplierProfile_Button',supplier_id);
        });       

        $('input[type=text], textarea').keyup(function(){
            var formval = $('#Form_SupplierProfile').serializeArray();
            if(this.name == "txtSupplierContact" || this.name == "txtOwnerAcctNo" || this.name == "txtOwnerPhoneNo"){
                this.value = this.value.replace(/[^0-9]/g, '');
            }else if(this.name == "txtSupplierEmail"){
                this.value = this.value.replace(/[^a-zA-Z0-9@_.]/g, '');
                if (validateEmail(this.value)) {
                    $('.err-'+this.name).css('display','none');
                    $('.'+this.name).attr('style','border: #008a8a 1px solid !important');
                }else{                    
                    $('.err-'+this.name).css('display','block');
                    $('.'+this.name).attr('style','border: #ff5b57 1px solid !important');
                    $('.err-'+this.name).html('Invalid Email!'); 
                }
            }else{
                this.value = this.value.toUpperCase();
                this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
            }
        });        

        function clearObjects(){
            $('input[type=text], textarea').val('');
            var formval = $('#Form_SupplierProfile').serializeArray();
            for (let index = 0; index < formval.length; index++) {
                const field_name = formval[index].name;
                $('.err-'+field_name).css('display','none');
                $('.'+field_name).attr('style','border: #008a8a 1px solid !important');
            }
        }

        $(document).on('change','.selectOwnerBankName',function(){
            var selected = $(this).find('option:selected');
            var extra = selected.data('banklongname'); 
        });

        $(document).on('click','.Save_SupplierProfile_Button, .Update_SupplierProfile_Button',function(){
            var formval = $('#Form_SupplierProfile').serializeArray(),
                actionbutton = $(this).data('actionbutton'),
                form_null = 0;
            for (let index = 0; index < formval.length; index++) {
                const field_name = formval[index].name,
                      field_val = formval[index].value;
                if(field_val == ""){
                    $('.err-'+field_name).css('display','block');
                    $('.'+field_name).attr('style','border: #ff5b57 1px solid !important');
                    $('.err-'+field_name).html('This field is required!');  
                    form_null += 1;                
                }else{
                    if(field_name == "txtSupplierEmail"){
                        if (validateEmail(field_val)) {
                            $('.err-'+field_name).css('display','none');
                            $('.'+field_name).attr('style','border: #008a8a 1px solid !important');
                        }else{                    
                            $('.err-'+field_name).css('display','block');
                            $('.'+field_name).attr('style','border: #ff5b57 1px solid !important');
                            $('.err-'+field_name).html('Invalid Email!'); 
                            form_null += 1;  
                        }   
                    }else{
                        $('.err-'+field_name).css('display','none');
                        $('.'+field_name).attr('style','border: #008a8a 1px solid !important');
                    }
                                    
                }              
            }
            if(form_null == 0){                
                var txtOwnerMiddleName = $('.txtOwnerMiddleName').val(),
                    txtOwnerExtName = $('.txtOwnerExtName').val(),
                    _token = $("input[name=token]").val(),
                    selected = $('.selectOwnerBankName').find('option:selected'),
                    supplier_id = $('#selectedsupplierid').val(),
                    txtbanklongname = selected.data('banklongname'); 
                formval.push({ name: "txtOwnerMiddleName", value: txtOwnerMiddleName });
                formval.push({ name: "txtOwnerExtName", value: txtOwnerExtName });
                formval.push({ name: "txtbanklongname", value: txtbanklongname });
                formval.push({ name: "_token", value: _token });
                formval.push({ name: "actionbutton", value: actionbutton });
                formval.push({ name: "supplier_id", value: supplier_id });
                SpinnerShow('Save_SupplierProfile_Button','btnloadingIcon2');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Save?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Save',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'post',
                            url:"{{ route('Saving.SupplierProfile') }}",
                            data:formval,
                            success:function(data){ 
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Saved!',
                                    text:'Your Supplier successfully Saved!',
                                    icon:'success'
                                });
                                clearObjects();   
                                SupplierProfileList();                               
                                $('#Form_SupplierProfile_Modal').modal('hide');  
                                SpinnerHide('Save_SupplierProfile_Button','btnloadingIcon2');
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('Save_SupplierProfile_Button','btnloadingIcon2');                                
                            }
                        });
                    }else{
                        SpinnerHide('Save_SupplierProfile_Button','btnloadingIcon2');                        
                    }
                });
            }
        });

        $(document).on('click','.Remove_SupplierProfile_Button',function(){
            var supplier_id = $(this).data('selectedsupplierid'),
                _token = $("input[name=token]").val();
            SpinnerShow('Remove_SupplierProfile_Button',supplier_id);
            Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Remove?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Remove',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'post',
                            url:"{{ route('Removing.SupplierProfile') }}",
                            data:{supplier_id:supplier_id,_token:_token},
                            success:function(data){ 
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Your Supplier successfully Removed!',
                                    icon:'success'
                                });
                                clearObjects();   
                                SupplierProfileList();                               
                                $('#Form_SupplierProfile_Modal').modal('hide');  
                                SpinnerHide('Remove_SupplierProfile_Button',supplier_id);
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('Remove_SupplierProfile_Button',supplier_id);                               
                            }
                        });
                    }else{
                        SpinnerHide('Remove_SupplierProfile_Button',supplier_id);                      
                    }
                });
        });
        

    });        
</script>

@endsection

@section('content')
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="selectedsupplierid" value="">
<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Supplier Profile</h1>                       
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Supplier Profile</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-xl-12 ui-sortable">       
        <div class="pull-right">                              
            <a href="javascript:;" class="btn btn-lg btn-primary Create_Supplier_Button">  
                <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                <i class="fa fa-user fa-2x pull-left m-r-10 text-black"></i>
                <b class="Create_SupplierProfile_Title"> Create Merchant/Seller/Supplier</b><br />
                <small>Click Here</small>
            </a>
        </div>
    </div>
</div><br>

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title panel-title-main btn btn-xs" style="font-weight:normal !important;"><span class="fa fa-th-large"></span> DETAILS:</h4>
        <h4 class="panel-title panel-title-sub btn btn-xs" style="font-weight:normal !important;display:none;">:</h4> 
    </div>
    <div class="panel-body">
    <table id="SupplierProfileList-datatable" class="table table-striped display nowrap" style="width: 100%;">
        <thead style="background-color: #008a8a;"></thead>
    </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="Form_SupplierProfile_Modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="Form_SupplierProfile" method="POST">
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Merchant/Seller/Supplier
                    <a href="javascript:;" class="btn btn-success EditDetails_SupplierProfile_Button"><i class="fa fa-edit"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Edit Details</a>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="form-inline mb-3">
                                                <label class="label label-info">Branch Information:</label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Supplier Group: <span class="text-danger">*</span></label>
                                                <select class="form-control selectSupplierGroup" name="selectSupplierGroup">
                                                    <!-- <option value="" selected>-- Select --</option> -->
                                                </select> 
                                                <label class="form-label text-danger err-selectSupplierGroup" style="display: none; font-size:10px;"></label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Supplier Name: <span class="text-danger">*</span></label>
                                                <input class="form-control txtSupplierName" name="txtSupplierName" type="text" placeholder="Enter Supplier Name"/>
                                                <label class="form-label text-danger err-txtSupplierName" style="display: none; font-size:10px;"></label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Complete Address: <span class="text-danger">*</span></label>
                                                <textarea class="form-control txtSupplierAddress" name="txtSupplierAddress" rows="3" placeholder="Enter Complete Address" required></textarea>
                                                <label class="form-label text-danger err-txtSupplierAddress" style="display: none; font-size:10px;"></label>
                                            </div>
                                            <div class="row mb-3">                                                
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Region: <span class="text-danger">*</span></label>
                                                    <select class="form-control selectSupplierRegion" name="selectSupplierRegion">
                                                        <option value="" selected>-- Select --</option>
                                                    </select> 
                                                    <label class="form-label text-danger err-selectSupplierRegion" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Province: <span class="text-danger">*</span></label>
                                                    <select class="form-control selectSupplierProvince" name="selectSupplierProvince">
                                                        <option value="" selected>-- Select --</option>
                                                    </select> 
                                                    <label class="form-label text-danger err-selectSupplierProvince" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Municipality/City: <span class="text-danger">*</span></label>
                                                    <select class="form-control selectSupplierCity" name="selectSupplierCity">
                                                        <option value="" selected>-- Select --</option>
                                                    </select> 
                                                    <label class="form-label text-danger err-selectSupplierCity" style="display: none; font-size:10px;"></label>
                                                </div>  
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Barangay: <span class="text-danger">*</span></label>
                                                    <select class="form-control selectSupplierBarangay" name="selectSupplierBarangay">
                                                        <option value="" selected>-- Select --</option>
                                                    </select> 
                                                    <label class="form-label text-danger err-selectSupplierBarangay" style="display: none; font-size:10px;"></label>
                                                </div>                                               
                                            </div>                                             
                                            <div class="row mb-3"> 
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Business Permit: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtSupplierBusinessPermit" name="txtSupplierBusinessPermit" type="text" placeholder="Enter Business Permit" />
                                                    <label class="form-label text-danger err-txtSupplierBusinessPermit" style="display: none; font-size:10px;"></label>
                                                </div>                                               
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Email: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtSupplierEmail" name="txtSupplierEmail" type="text" placeholder="Enter Email Address" />
                                                    <label class="form-label text-danger err-txtSupplierEmail" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Contact No.: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtSupplierContact" name="txtSupplierContact" type="text" maxlength="11" placeholder="Enter Contact Number" />
                                                    <label class="form-label text-danger err-txtSupplierContact" style="display: none; font-size:10px;"></label>
                                                </div>                                             
                                            </div><hr>  
                                            <div class="form-inline mb-3">
                                                <label class="label label-info">Main Branch Information:</label>
                                            </div>
                                            <div class="row mb-3">                                                
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">First Name: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtOwnerFirstName" name="txtOwnerFirstName" type="text" placeholder="Enter First Name" />
                                                    <label class="form-label text-danger err-txtOwnerFirstName" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Middle Name: </label>
                                                    <input class="form-control txtOwnerMiddleName" type="text" placeholder="Enter Middle Name" />
                                                    <label class="form-label text-danger err-txtOwnerMiddleName" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Last Name: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtOwnerLastName" name="txtOwnerLastName" type="text" placeholder="Enter Last Name" />
                                                    <label class="form-label text-danger err-txtOwnerLastName" style="display: none; font-size:10px;"></label>
                                                </div>  
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Extention Name: </label>
                                                    <input class="form-control txtOwnerExtName" type="text" placeholder="Enter Extention Name" />
                                                    <label class="form-label text-danger err-txtOwnerExtName" style="display: none; font-size:10px;"></label>
                                                </div>                                               
                                            </div> 
                                            <div class="row mb-3">                                                
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Bank: <span class="text-danger">*</span></label>
                                                    <select class="form-control selectOwnerBankName" name="selectOwnerBankName">
                                                        <option value="" selected>-- Select --</option>
                                                    </select> 
                                                    <label class="form-label text-danger err-selectOwnerBankName" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Account Name: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtOwnerAcctName" name="txtOwnerAcctName" type="text" placeholder="Enter Bank Account Name" />
                                                    <label class="form-label text-danger err-txtOwnerAcctName" style="display: none; font-size:10px;"></label>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Account No.: <span class="text-danger">*</span></label>
                                                    <input class="form-control txtOwnerAcctNo" name="txtOwnerAcctNo" type="text" placeholder="Enter Bank Account Number" />
                                                    <label class="form-label text-danger err-txtOwnerAcctNo" style="display: none; font-size:10px;"></label>
                                                </div>                                             
                                            </div>                                             
                                            <div class="mb-3">
                                                <label class="form-label">Phone No.: <span class="text-danger">*</span></label>
                                                <input class="form-control txtOwnerPhoneNo" name="txtOwnerPhoneNo" type="text" placeholder="Enter Main Branch Name" />
                                                <label class="form-label text-danger err-txtOwnerPhoneNo" style="display: none; font-size:10px;"></label>
                                            </div>                                                                         
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                        </div>
                        
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success Save_SupplierProfile_Button" data-actionbutton="INSERT"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Create</a>
                    <a href="javascript:;" class="btn btn-success Update_SupplierProfile_Button" data-actionbutton="EDIT" style="display: none;"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Update</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection