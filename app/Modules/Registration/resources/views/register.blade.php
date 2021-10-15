<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Voucher Management Platform Ver.2</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css" rel="stylesheet" />
	<link href="assets/plugins/animate/animate.min.css" rel="stylesheet" />
	<link href="assets/css/default/style.min.css" rel="stylesheet" />
	<link href="assets/css/default/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/default/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
	<link href="assets/plugins/ionRangeSlider/css/ion.rangeSlider.css" rel="stylesheet" />
	<link href="assets/plugins/ionRangeSlider/css/ion.rangeSlider.skinNice.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
	<link href="assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-combobox/css/bootstrap-combobox.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" />
	<link href="assets/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css" rel="stylesheet" />
    <link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css" rel="stylesheet" />
    <link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css" rel="stylesheet" />
    <link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->	
    <style>
        .prov_val{
            font-size: 12px; font-family: 'Open Sans',"Helvetica Neue",Helvetica,Arial,sans-serif;
        }
    </style>
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin register -->
        <div class="register register-with-news-feed overflow-auto">
            <!-- begin news-feed -->
            <div class="news-feed">
                <div class="news-image" style="background-image: url(assets/img/login-bg/DA22.jpg)"></div>
                <div class="news-caption">
                    <h4 class="caption-title">Voucher Management Platform Ver.2</h4>
                </div>
            </div>
            <!-- end news-feed -->
            <!-- begin right-content -->
            <div class="right-content">
                <!-- begin register-header -->
                <h1 class="register-header">
                    <img src="assets/img/logo/DA-Logo.png" alt="DA-Logo" style="height:40px; width:40px;"> Registration
                    <small>Create your Account.</small>
                </h1>
                <!-- end register-header -->
                <!-- begin register-content -->
                <div class="register-content">                    
                    <form action="signup" method="POST">
                        @csrf
                        <div class="row row-space-10">
                            <div class="col-md-6 m-b-15">
                                <label class="control-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="First_name" class="form-control txtfname" placeholder="Enter First name" value="{{ old('First_name') }}" onKeyUP="this.value = this.value.toUpperCase();"/>
                                <span class="text-danger">@error('First_name'){{ $message }} @enderror</span>
                            </div>
                            <div class="col-md-6 m-b-15">
                                <label class="control-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="Last_name" class="form-control" placeholder="Enter Last name" value="{{ old('Last_name') }}" onKeyUP="this.value = this.value.toUpperCase();" />
                                <span class="text-danger">@error('Last_name'){{ $message }} @enderror</span>
                            </div>
                            <div class="col-md-6 m-b-15">
                                <label class="control-label">Middle Name </label>
                                <input type="text" name="Middle_name" class="form-control" placeholder="Enter Middle name" value="{{ old('Middle_name') }}" onKeyUP="this.value = this.value.toUpperCase();" />
                            </div>
                            <div class="col-md-6 m-b-15">
                                <label class="control-label">Extention Name </label>
                                <input type="text" name="Extention_Name" class="form-control" placeholder="Enter Ext. name ex.(Jr, I, II, III etc.)" value="{{ old('Extention_Name') }}" onKeyUP="this.value = this.value.toUpperCase();" />
                            </div>
                        </div>

                        <label class="control-label">Location <span class="text-danger">*</span></label>
                        <input type="hidden" name="geo_code" class="geo_code">
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectregion" name="Region" data-size="10" data-style="btn-white" value="{{ old('Region') }}">
                                <option value="" selected>Select Region</option>
                                @foreach($reg_loc as $reg)
                                    <option value="{{$reg->reg_code}}">{{$reg->reg_name}}</option>
                                @endforeach
                                </select>
                                <span class="text-danger">@error('Region'){{ $message }} @enderror</span>
                            </div>                            
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectprovince" name="Province" data-size="10" data-style="btn-white" value="{{ old('Province') }}">
                                    <option value="" selected>Select Province</option>                                   
                                </select>
                                <span class="text-danger">@error('Province'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectmunicipality" name="Municipality" data-size="10" data-style="btn-white" value="{{ old('Municipality') }}">
                                    <option value="" selected>Select Municipality</option>                                   
                                </select>
                                <span class="text-danger">@error('Municipality'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectbarangay" name="Barangay" data-size="10" data-style="btn-white" value="{{ old('Barangay') }}">
                                    <option value="" selected>Select Barangay</option>                                   
                                </select>
                                <span class="text-danger">@error('Barangay'){{ $message }} @enderror</span>
                            </div>
                        </div>

                        <label class="control-label">Email <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="text" name="Email" class="form-control" placeholder="Enter Email address" value="{{ old('Email') }}" />
                                <span class="text-danger">@error('Email'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <label class="control-label">Contact Number <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="text" name="Contact_Number" class="form-control" placeholder="Enter Contact Number" value="{{ old('Contact_Number') }}" />
                                <span class="text-danger">@error('Contact_Number'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <label class="control-label">Username <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="text" name="Username" class="form-control" placeholder="Enter Username" value="{{ old('Username') }}" />
                                <span class="text-danger">@error('Username'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <label class="control-label">Password <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" name="Password" class="form-control" placeholder="Enter Password" value="{{ old('Password') }}" />
                                <span class="text-danger">@error('Password'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <label class="control-label">Re-enter Password <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" name="ReEnter_Password" class="form-control" placeholder="Re-enter Password" value="{{ old('ReEnter_Password') }}" />
                                <span class="text-danger">@error('ReEnter_Password'){{ $message }} @enderror</span>
                            </div>
                        </div>
                        <div class="register-buttons">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Sign Up</button>
                        </div>
                        <hr />
                        <p class="text-center">
                            &copy; Department of Agriculture e-Voucher System Ver.2 2021
                        </p>
                    </form>
                </div>
                <!-- end register-content -->
            </div>
            <!-- end right-content -->
        </div>
        <!-- end register -->       
        
	</div>
	<!-- end page container -->
	
		<!-- ================== BEGIN BASE JS ================== -->
        <script src="assets/plugins/jquery/jquery-3.2.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="assets/js/theme/default.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
	<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
	<script src="assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
	<script src="assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
	<script src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
	<script src="assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/moment.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/plugins/bootstrap-show-password/bootstrap-show-password.js"></script>
    <script src="assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js"></script>
    <script src="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js"></script>
    <script src="assets/plugins/clipboard/clipboard.min.js"></script>
	<script src="assets/js/demo/form-plugins.demo.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
    
    @if(Session::get('success'))
        <script>
            swal({
            title: "Done!",
            text: "{{ session('success') }}",
            icon: "success",
            button: "OK",
            }).then(function(){ 
                window.location = "login";
            });
        </script>
    @endif
    @if(Session::get('failed'))
        <script>
            swal({
            title: "Failed!",
            text: "{{ session('success') }}",
            icon: "error",
            button: "OK",
            });
        </script>
    @endif
    <script>
		$(document).ready(function() {
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

                    }
                });
            });
            


        });
    </script>
</body>
</html>
