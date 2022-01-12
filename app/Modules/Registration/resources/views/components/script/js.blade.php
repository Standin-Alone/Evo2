    <script>
        $(document).ready(function () {
            App.init();
			FormPlugins.init();
            $('.txtfname').focus();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('change','.selectregion',function(){
                var reg_code=$(this).val();
                var div=$(this).parent();
                var _token = $("input[name=token]").val();
                var op=" ";
                    $('.geo_code').val('');            
                    $('.prov_val').remove();
                    $('.mun_val').remove();
                    $('.bgy_val').remove();
                $.ajax({
                    type:'post',
                    url:"{{ route('fetch.province') }}",
                    data:{reg_code:reg_code,_token:_token},
                    success:function(data){                           
                        // $('.geo_code').val(data[0]['geo_code']);
                        for(var i=0;i<data.length;i++){                            
                            $('.selectprovince').append($('<option>', {class:'prov_val',value:data[i].prov_code, text:data[i].prov_name}));
                        }
                    },
                    error:function(){

                    }
                });
            });

            $(document).on('change','.selectprovince',function(){
                var reg_code=$('.selectregion').val(),
                    prov_code=$('.selectprovince').val(),
                    div=$(this).parent(),
                    _token = $("input[name=token]").val(),
                    op=" ";
                    $('.mun_val').remove();
                    $('.bgy_val').remove();
                $.ajax({
                    type:'post',
                    url:"{{ route('fetch.municipality') }}",
                    data:{reg_code:reg_code,prov_code:prov_code,_token:_token},
                    success:function(data){
                        for(var i=0;i<data.length;i++){
                            $('.selectmunicipality').append($('<option>', {class:'mun_val',value:data[i].mun_code, text:data[i].mun_name}));
                        }
                    },
                    error:function(){

                    }
                });
            });
            
            $(document).on('change','.selectmunicipality',function(){
                var reg_code=$('.selectregion').val(),
                    prov_code=$('.selectprovince').val(),
                    mun_code=$('.selectmunicipality').val(),
                    div=$(this).parent(),
                    _token = $("input[name=token]").val(),
                    op=" ";
                    $('.bgy_val').remove();
                $.ajax({
                    type:'post',
                    url:"{{ route('fetch.barangay') }}",
                    data:{reg_code:reg_code,prov_code:prov_code,mun_code:mun_code,_token:_token},
                    success:function(data){
                        for(var i=0;i<data.length;i++){
                            $('.selectbarangay').append($('<option>', {class:'bgy_val',value:data[i].bgy_code, text:data[i].bgy_name}));
                        }
                    },
                    error:function(){
                        console.log('Err');
                    }
                });
            });

            $('#form_registration').validate({
                rules: {
                    First_name:'required',
                    Last_name:'required',
                    Company_name:'required',
                    Company_address:'required',
                    Region:'required',
                    Province:'required',
                    Municipality:'required',
                    Barangay:'required',
                    Email:{
                        required: true,
                        email : true
                    },
                    Contact_Number:{
                        required: true,
                        number : true,
                        minlength : 11,
                        maxlength : 11
                    },
                    Username:'required',
                    Password:{
                        required: true,
                        minlength : 8
                    },
                    ReEnter_Password:{
                        required: true,     
                        minlength : 8,
                        equalTo : "input[name=Password]" 
                    }           
                },
                submitHandler: function (form) {
                    SpinnerShow('register-buttons','btnloadingIcon1');
                    form_val = $('#form_registration').serializeArray(),
                    _token = $("input[name=token]").val();
                    $.ajax({
                        type:'post',
                        url:"{{ route('save.registration') }}",
                        data:form_val,
                        success:function(data){
                            if(data == "ExistName"){
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Invalid!',
                                    text:'Your name is already registered!',
                                    icon:'error'
                                });                            
                            }else if(data == "ExistEmail"){
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Invalid!',
                                    text:'Your Email is already registered!',
                                    icon:'error'
                                });
                            }else if(data == "ExistCompany"){
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Invalid!',
                                    text:'Your Company is already registered!',
                                    icon:'error'
                                });
                            }else if(data == "ExistUsername"){
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Invalid!',
                                    text:'Your Username is already registered!',
                                    icon:'error'
                                });
                            }else{
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Registered!',
                                    text:'Your account successfully Registered! Please wait for the Account Activation.',
                                    icon:'success'
                                });
                                location.reload();
                            }
                            
                            SpinnerHide('register-buttons','btnloadingIcon1');
                        },
                        error:function(){
                            console.log('Err');
                        }
                    });
                }
            });

            $(document).on('click','.register-buttons',function(){
                $('.form-control').css('text-align','left');
            });

            $(document).on('click','.link_check_registration',function(){
                $('#Form_check_registration_Modal').modal('toggle');
            });

            $(document).on('click','.btn-reg-checkstatus',function(){
                SpinnerShow('btn-reg-checkstatus','btnloadingIcon2');
                var regs_code = $('.Regs_code').val(),
                    regs_email = $('.Regs_Email').val(),
                    _token = $("input[name=token]").val();
                $.ajax({
                    type:'post',
                    url:"{{ route('get.regs-status') }}",
                    data:{regs_code:regs_code,regs_email:regs_email,_token:_token},
                    success:function(data){
                        if(data.length > 0){
                            for(var i=0;i<data.length;i++){
                                $('.txt-registration-date').html(data[i].date_created);
                                $('.txt-registration-name').html(data[i].regs_name);
                                $('.txt-company-name').html(data[i].company_name);
                                $('.txt-registration-date, .txt-registration-name, .txt-company-name').removeClass('text-danger');
                                if(data[i].status == 0){                                
                                    $('.step-registration').addClass("active");
                                    $('.step-approve, .step-complete').removeClass("active");
                                }else{
                                    $('.step-registration').removeClass("active");
                                    $('.step-approve, .step-complete').addClass("active");
                                }
                            }
                        }else{
                            $('.txt-registration-date, .txt-registration-name, .txt-company-name').html('No Record!');
                            $('.txt-registration-date, .txt-registration-name, .txt-company-name').addClass('text-danger');
                            $('.step-registration, .step-approve, .step-complete').removeClass("active");
                        }
                        
                        SpinnerHide('btn-reg-checkstatus','btnloadingIcon2');
                    },
                    error:function(){
                        console.log('Err');
                    }
                });
            });
            
            

        });

    </script>