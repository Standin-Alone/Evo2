@extends('global.base')
@section('title', "Program Items")

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
<style>

    :root {
        background-color: #FCFEFD;
        font-family: helvetica, arial, sans-serif;
        font-size: 15px;
    }

    input, select, textarea {
        display: block;
        box-sizing: border-box;
        width: 100%;
        outline: none;
        border: none;
        border-radius: 2px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .label{
        display: block;
        margin-bottom: 0.25em;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 2px;
    }

    .input, .select, .textarea {
        padding: 10px;
        border:1px solid lightgray;
        background-color: white;color:#aaa;letter-spacing:.7px;
    }

    .input:focus, .textarea:focus {
        border-color: gray;
    }

    .textarea {
        min-height: 100px;
        resize: vertical;
    }

    .tr{
        padding-top:50px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;

        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        max-width: 600px;
    }

    .p{padding:30px;}

    .input, .checkbox-label:before, .radio-label:before, .checkbox-label:after, .radio-label:after, .select, .textarea, .checkbox-label, .radio-label {
        margin-bottom: 1em;
    }

    .r{height:250px;width:250px;background:lightgrey;border-radius:50%;float:left;margin-right:30px;}

    .icon{
        width:75px;
        height:75px; 
        
        margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);  
    
    }

    .camera4{
        display:block;
        width:70%;
        height:50%;
        position:absolute;
        top:30%;
        left:15%;
        background-color:#000;
        border-radius:5px;
    }

    .camera4:after{
        content:"";
        display:block;
        width:15px;
        height:15px;
        border:7px solid #fff;
        position:absolute;
        top:15%;
        left:25%;
        background-color:#000;
        border-radius:15px;
    }

    .camera4:before{
        content:"";
        display:block;
        width:50%;
        height:10px;
        position:absolute;
        top:-16%;
        left:25%;
        background-color:#000;
        border-radius:10px;
    }

    #profile-upload{
        /* background-image:url('');
        background-size:cover;
        background-position: center; */
        height: 250px; 
        /* border: 1px solid #bbb; */
        position:relative;
        /* border-radius:50%; */
        overflow:hidden;
        float:left;margin-right:30px;margin-bottom:30px;    
    }

    #profile-upload:hover input.upload{
        display:block;
    }

    #profile-upload:hover .hvr-profile-img{
        opacity:1;
    }

    /* .hvr-profile-img{opacity:.3;} */

    #profile-upload input.upload {
        z-index:1;
        left: 0;
        margin: 0;
        bottom: 0;
        top: 0;
        padding: 0;
        opacity: 0;
        outline: none;
        cursor: pointer;
        position: absolute;
    
        width:100%;
        display:none;
    }

    #count{font-size:12px;display:inline-block;text-align:center;color:lightgrey;}

    .e{max-width:880px;}

    .select {
        position: relative;
        z-index: 1;
        padding-right: 40px;
    }

    .select::-ms-expand {
        display: none;
    }

    .select-wrap {
        position: relative;
    }

    .select-wrap:after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        padding: 0 15px;
        width: 10px;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20version%3D%221.1%22%20x%3D%220%22%20y%3D%220%22%20width%3D%2213%22%20height%3D%2211.3%22%20viewBox%3D%220%200%2013%2011.3%22%20enable-background%3D%22new%200%200%2013%2011.3%22%20xml%3Aspace%3D%22preserve%22%3E%3Cpolygon%20fill%3D%22%23424242%22%20points%3D%226.5%2011.3%203.3%205.6%200%200%206.5%200%2013%200%209.8%205.6%20%22%2F%3E%3C%2Fsvg%3E");
        background-position: center;
        background-size: 10px;
        background-repeat: no-repeat;
        content: "";
        pointer-events: none;
    }

    @media (max-width: 600px){
        #profile-upload{float:none;margin:auto; 
        
        }    
    }

    .tr span{display:inline-block;color:lightgrey;font-size:11px;}

    #texte{display:inline-block;color:grey;}




    .profile-pic-wrapper {
  /* height: 100vh;
  width: 100%; */
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.pic-holder {
  text-align: center;
  position: relative;
  border-radius: 50%;
  width: 150px;
  height: 150px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}

.pic-holder .pic {
  height: 100%;
  width: 100%;
  -o-object-fit: cover;
  object-fit: cover;
  -o-object-position: center;
  object-position: center;
}

.pic-holder .upload-file-block,
.pic-holder .upload-loader {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: rgba(90, 92, 105, 0.7);
  color: #f8f9fc;
  font-size: 12px;
  font-weight: 600;
  opacity: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.pic-holder .upload-file-block {
  cursor: pointer;
}

.pic-holder:hover .upload-file-block {
  opacity: 1;
}

.pic-holder.uploadInProgress .upload-file-block {
  display: none;
}

.pic-holder.uploadInProgress .upload-loader {
  opacity: 1;
}

/* Snackbar css */
.snackbar {
  visibility: hidden;
  min-width: 250px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  left: 50%;
  bottom: 30px;
  font-size: 14px;
  transform: translateX(-50%);
}

.snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {
    bottom: 0;
    opacity: 0;
  }
  to {
    bottom: 30px;
    opacity: 1;
  }
}

@keyframes fadein {
  from {
    bottom: 0;
    opacity: 0;
  }
  to {
    bottom: 30px;
    opacity: 1;
  }
}

@-webkit-keyframes fadeout {
  from {
    bottom: 30px;
    opacity: 1;
  }
  to {
    bottom: 0;
    opacity: 0;
  }
}

@keyframes fadeout {
  from {
    bottom: 30px;
    opacity: 1;
  }
  to {
    bottom: 0;
    opacity: 0;
  }
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
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/pgv/backend-script.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        ProgramItemsList();         
        
        function ProgramItemsList(){             
            var table = $('#ProgramItemsList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,   
                    scrollY: "350px",
                    ajax: "{{ route('get.ProgramItemsList') }}",
                    columns: [
                        {data: 'supplier_name', name: 'supplier_name', title:'SUPPLIER NAME'}, 
                        {data: 'address', name: 'address', title:'ADDRESS'}, 
                        {data: 'supplier_group', name: 'supplier_group', title:'MAIN BRANCH'}, 
                        {data: 'bank_account_no', name: 'bank_account_no', title:'ACCOUNT NO.'},
                        {data: 'email', name: 'email', title:'EMAIL ACCOUNT'},
                        {data: 'contact', name: 'contact', title:'CONTACT NO.'},
                        {data: 'supplier_id', name: 'supplier_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return  '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-success View_ProgramItems_Button" data-toggle="tooltip" data-placement="top" title="View Details"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-info Edit_ProgramItems_Button" data-toggle="tooltip" data-placement="top" title="Edit Details"><span class="fa fa-edit"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>|'+
                                        '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-danger Remove_ProgramItems_Button" data-toggle="tooltip" data-placement="top" title="Remove Details"><span class="fa fa-trash"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i></a>';
                            }
                        }
                    ],
                });
        }

        function ProgramRegion(){
            var _token = $("input[name=token]").val();
            $('.reg_val').remove();
            $('.prov_val').remove();
            $('.mun_val').remove();
            $('.bgy_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.ProgramRegion') }}",
                data:{_token:_token},
                success:function(data){ 
                    $('.reg_val').remove();                         
                    for(var i=0;i<data.length;i++){ 
                        $('.selectProgramRegion').append($('<option>', {class:'reg_val',value:data[i].reg_code, text:data[i].reg_name}));      
                        $('.selectProgramRegion').val(data[i].reg_code);                   
                    }
                    
                    
                },
                error:function(){
                    console.log('Err');
                }
            });
        }

        function ProgramProvince(){
            var _token = $("input[name=token]").val();
            $('.prov_val').remove();
            $.ajax({
                type:'post',
                url:"{{ route('get.ProgramProvince') }}",
                data:{_token:_token},
                success:function(data){           
                    $('.prov_val').remove();            
                    for(var i=0;i<data.length;i++){                            
                        $('.selectProgramProvince').append($('<option>', {class:'prov_val',value:data[i].prov_code, text:data[i].prov_name}));
                    }
                },
                error:function(){
                    console.log('Err');
                }
            });
        }       

        function ViewProgramItemsDetails(supplier_id){
            var _token = $("input[name=token]").val();
            $.ajax({
                type:'post',
                url:"{{ route('get.ProgramItemsDetails') }}",
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
                        ProgramRegion(AddZero(data[i].reg));
                        ProgramProvince(AddZero(data[i].reg),AddZero(data[i].prv));
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
            ProgramRegion();
            ProgramProvince();  
            clearObjects();
            $('.Update_ProgramItems_Button, .EditDetails_ProgramItems_Button').css('display','none');
            $('.Save_ProgramItems_Button').css('display','block');
            $('#Form_ProgramItems_Modal').modal('toggle');  
            SpinnerHide('Create_Supplier_Button','btnloadingIcon');
        });

        $(document).on('click','.View_ProgramItems_Button',function(){
            SpinnerShow('View_ProgramItems_Button','btnloadingIcon'); 
            var supplier_id = $(this).data('selectedsupplierid');
            $('#selectedsupplierid').val(supplier_id);
            clearObjects();
            ViewProgramItemsDetails(supplier_id);
            $('.EditDetails_ProgramItems_Button').css('display','block');
            $('.Save_ProgramItems_Button, .Update_ProgramItems_Button').css('display','none');
            $('#Form_ProgramItems_Modal').modal('toggle');  
            SpinnerHide('View_ProgramItems_Button','btnloadingIcon');
        });

        $(document).on('click','.Edit_ProgramItems_Button',function(){
            var supplier_id = $(this).data('selectedsupplierid');
            $('#selectedsupplierid').val(supplier_id);
            SpinnerShow('Edit_ProgramItems_Button',supplier_id);
            $('.Save_ProgramItems_Button, .EditDetails_ProgramItems_Button').css('display','none');
            $('.Update_ProgramItems_Button').css('display','block');
            clearObjects();
            ViewProgramItemsDetails(supplier_id);
            $('#Form_ProgramItems_Modal').modal('toggle');  
            SpinnerHide('Edit_ProgramItems_Button',supplier_id);
        });

        $(document).on('click','.EditDetails_ProgramItems_Button',function(){
            var supplier_id = $(this).data('selectedsupplierid');
            SpinnerShow('EditDetails_ProgramItems_Button',supplier_id);
            $('.Save_ProgramItems_Button, .EditDetails_ProgramItems_Button').css('display','none');
            $('.Update_ProgramItems_Button').css('display','block');
            SpinnerHide('EditDetails_ProgramItems_Button',supplier_id);
        });       

        $('input[type=text], textarea').keyup(function(){
            var formval = $('#Form_ProgramItems').serializeArray();
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
            var formval = $('#Form_ProgramItems').serializeArray();
            for (let index = 0; index < formval.length; index++) {
                const field_name = formval[index].name;
                $('.err-'+field_name).css('display','none');
                $('.'+field_name).attr('style','border: #008a8a 1px solid !important');
            }
        }

        $(document).on('click','.Save_ProgramItems_Button, .Update_ProgramItems_Button',function(){
            var formval = $('#Form_ProgramItems').serializeArray(),
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
                SpinnerShow('Save_ProgramItems_Button','btnloadingIcon2');
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
                            url:"",
                            data:formval,
                            success:function(data){ 
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Saved!',
                                    text:'Your Supplier successfully Saved!',
                                    icon:'success'
                                });
                                clearObjects();   
                                ProgramItemsList();                               
                                $('#Form_ProgramItems_Modal').modal('hide');  
                                SpinnerHide('Save_ProgramItems_Button','btnloadingIcon2');
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('Save_ProgramItems_Button','btnloadingIcon2');                                
                            }
                        });
                    }else{
                        SpinnerHide('Save_ProgramItems_Button','btnloadingIcon2');                        
                    }
                });
            }
        });

        $(document).on('click','.Remove_ProgramItems_Button',function(){
            var supplier_id = $(this).data('selectedsupplierid'),
                _token = $("input[name=token]").val();
            SpinnerShow('Remove_ProgramItems_Button',supplier_id);
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
                            url:"",
                            data:{supplier_id:supplier_id,_token:_token},
                            success:function(data){ 
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Your Supplier successfully Removed!',
                                    icon:'success'
                                });
                                clearObjects();   
                                ProgramItemsList();                               
                                $('#Form_ProgramItems_Modal').modal('hide');  
                                SpinnerHide('Remove_ProgramItems_Button',supplier_id);
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('Remove_ProgramItems_Button',supplier_id);                               
                            }
                        });
                    }else{
                        SpinnerHide('Remove_ProgramItems_Button',supplier_id);                      
                    }
                });
        });

        



        $(document).on("change", ".uploadProfileInput", function () {
            var triggerInput = this;
            var currentImg = $(this).closest(".pic-holder").find(".pic").attr("src");
            var holder = $(this).closest(".pic-holder");
            var wrapper = $(this).closest(".profile-pic-wrapper");
            $(wrapper).find('[role="alert"]').remove();
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) {
                return;
            }
            if (/^image/.test(files[0].type)) {
                // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function () {
                $(holder).addClass("uploadInProgress");
                $(holder).find(".pic").attr("src", this.result);
                $(holder).append(
                    '<div class="upload-loader"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
                );

                // Dummy timeout; call API or AJAX below
                setTimeout(() => {
                    $(holder).removeClass("uploadInProgress");
                    $(holder).find(".upload-loader").remove();
                    // If upload successful
                    if (Math.random() < 0.9) {
                    $(wrapper).append(
                        '<div class="snackbar show" role="alert"><i class="fa fa-check-circle text-success"></i> Profile image updated successfully</div>'
                    );

                    // Clear input after upload
                    $(triggerInput).val("");

                    setTimeout(() => {
                        $(wrapper).find('[role="alert"]').remove();
                    }, 3000);
                    } else {
                    $(holder).find(".pic").attr("src", currentImg);
                    $(wrapper).append(
                        '<div class="snackbar show" role="alert"><i class="fa fa-times-circle text-danger"></i> There is an error while uploading! Please try again later.</div>'
                    );

                    // Clear input after upload
                    $(triggerInput).val("");
                    setTimeout(() => {
                        $(wrapper).find('[role="alert"]').remove();
                    }, 3000);
                    }
                }, 1500);
                };
            } else {
                $(wrapper).append(
                '<div class="alert alert-danger d-inline-block p-2 small" role="alert">Please choose the valid image.</div>'
                );
                setTimeout(() => {
                $(wrapper).find('role="alert"').remove();
                }, 3000);
            }
            });



    });        
</script>

@endsection

@section('content')
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="user_reg_code" value="{{session('reg_code')}}">
<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Program Items</h1>                       
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Program Items</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-xl-12 ui-sortable">       
        <div class="pull-right">                              
            <a href="javascript:;" class="btn btn-lg btn-primary Create_Supplier_Button">  
                <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                <i class="fa fa-cubes fa-2x pull-left m-r-10 text-black"></i>
                <b class="Create_ProgramItems_Title"> Create Item/Product</b><br />
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
        <table id="SupplierProgramList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="Form_ProgramItems_Modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="Form_ProgramItems" method="POST">
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Merchant/Seller/Supplier
                    <a href="javascript:;" class="btn btn-success EditDetails_ProgramItems_Button"><i class="fa fa-edit"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Edit Details</a>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                                                                                           
                                    <div class="p">
                                        <div id='profile-upload'>
                                            <div class="hvr-profile-img">                                                                   
                                                <div class="profile-pic-wrapper">
                                                                <div class="pic-holder">
                                                                    <!-- uploaded pic shown here -->
                                                                    <img id="profilePic" class="pic" src="https://source.unsplash.com/random/150x150">

                                                                    <label for="newProfilePhoto" class="upload-file-block">
                                                                    <div class="text-center">
                                                                        <div class="mb-2">
                                                                        <i class="fa fa-camera fa-2x"></i>
                                                                        </div>
                                                                        <div class="text-uppercase">
                                                                        Upload <br /> Item Photo
                                                                        </div>
                                                                    </div>
                                                                    </label>
                                                                    <Input class="uploadProfileInput" type="file" name="profile_pic" id="newProfilePhoto" accept="image/*" style="display: none;" />
                                                                </div>
                                                                </hr>
                                                                <!-- <p class="text-info text-center small">Note: Selected image will not be uploaded anywhere. </br> It's just for demonstration purposes.</p> -->
                                                                </div>
                                                <div class="icon">
                                                </div>
                                            </div>                                    
                                        </div>                                  
                                        <div class="row mb-3">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Item Name: <span class="text-danger">*</span></label>
                                                <input class="form-control txtSupplierBusinessPermit" name="txtSupplierBusinessPermit" type="text" placeholder="Enter Item Name" />
                                                <label class="form-label text-danger err-txtSupplierBusinessPermit" style="display: none; font-size:10px;"></label>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Unit of Measurement: <span class="text-danger">*</span></label>
                                                <input class="form-control txtSupplierBusinessPermit" name="txtSupplierBusinessPermit" type="text" placeholder="Enter Unit of Measurement" />
                                                <label class="form-label text-danger err-txtSupplierBusinessPermit" style="display: none; font-size:10px;"></label>    
                                            </div>     
                                            <div class="col-md-12 mb-3">                           
                                                <label class="form-label">Ceiling Amount: <span class="text-danger">*</span></label>
                                                <input class="form-control txtSupplierBusinessPermit" name="txtSupplierBusinessPermit" type="text" placeholder="Enter Ceiling Amount" />
                                                <label class="form-label text-danger err-txtSupplierBusinessPermit" style="display: none; font-size:10px;"></label> 
                                            </div>                                                
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Region: <span class="text-danger">*</span></label>
                                                <select class="form-control selectProgramRegion" name="selectProgramRegion">
                                                    <option value="" selected>-- Select --</option>
                                                </select> 
                                                <label class="form-label text-danger err-selectProgramRegion" style="display: none; font-size:10px;"></label>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Province: <span class="text-danger">*</span></label>
                                                <select class="form-control selectProgramProvince" name="selectProgramProvince">
                                                    <option value="" selected>-- Select --</option>
                                                </select> 
                                                <label class="form-label text-danger err-selectProgramProvince" style="display: none; font-size:10px;"></label>
                                            </div>
                                        </div>                                
                                </form>
                            </div>
                        </div>
                        
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success Save_ProgramItems_Button" data-actionbutton="INSERT"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Create</a>
                    <a href="javascript:;" class="btn btn-success Update_ProgramItems_Button" data-actionbutton="EDIT" style="display: none;"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Update</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection