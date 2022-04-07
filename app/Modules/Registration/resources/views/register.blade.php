<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Intervention Management Platform</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/img/logo/DA-Logo.png" sizes="196x196" />
	
	@include('components.libraries.css-registration')
    @include('components.libraries.js-registration')
    @include('Registration::components.script.js')

</head>
<body class="pace-top bg-white">
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<div id="page-container" class="fade">
        <div class="register register-with-news-feed overflow-auto">
            <div class="news-feed">
                <div class="news-image" style="background-image: url(assets/img/images/IMP.jpg)"></div>
                <div class="news-caption">
                </div>
            </div>
            <div class="right-content">
                <h1 class="register-header">
                    <img src="{{url('assets/img/images/DA-LOGO-1024x1024.png')}}" alt="DA-Logo" style="height:70px; width:70px;"> Registration
                    <small>Create your Account.</small>
                </h1>
                <div class="register-content">                
                <form id="form_registration">
                        @csrf
                        <div class="row row-space-10">
                            <div class="col-md-6 m-b-15">
                                <label class="control-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="First_name" class="form-control txtfname" placeholder="Enter First name" value="{{ old('First_name') }}" onKeyUP="this.value = this.value.toUpperCase();"/>
                            </div>
                            <div class="col-md-6 m-b-15">
                                <label class="control-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="Last_name" class="form-control" placeholder="Enter Last name" value="{{ old('Last_name') }}" onKeyUP="this.value = this.value.toUpperCase();" />
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
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <label class="control-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="Company_name" class="form-control txtfname" placeholder="Enter Company Name" value="{{ old('Company_name') }}" onKeyUP="this.value = this.value.toUpperCase();"/>
                            </div>
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <label class="control-label">Company Address <span class="text-danger">*</span></label>
                                <input type="text" name="Company_address" class="form-control" placeholder="Enter Company Address" value="{{ old('Company_address') }}" onKeyUP="this.value = this.value.toUpperCase();" />
                            </div>
                        </div>
                        <label class="control-label">Location: <span class="text-danger">*</span></label>
                        <input type="hidden" name="geo_code" class="geo_code">
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectregion" name="Region" data-size="10" data-style="btn-white" value="{{ old('Region') }}">
                                <option value="" selected>Select Region</option>
                                @foreach($reg_loc as $reg)
                                    <option value="{{$reg->reg_code}}">{{$reg->reg_name}}</option>
                                @endforeach
                                </select>
                            </div>                            
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectprovince" name="Province" data-size="10" data-style="btn-white" value="{{ old('Province') }}">
                                    <option value="" selected>Select Province</option>                                   
                                </select>
                            </div>
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectmunicipality" name="Municipality" data-size="10" data-style="btn-white" value="{{ old('Municipality') }}">
                                    <option value="" selected>Select Municipality</option>                                   
                                </select>
                            </div>
                        </div>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select class="form-control selectbarangay" name="Barangay" data-size="10" data-style="btn-white" value="{{ old('Barangay') }}">
                                    <option value="" selected>Select Barangay</option>                                   
                                </select>
                            </div>
                        </div>

                        <label class="control-label">Email <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="text" name="Email" class="form-control" placeholder="Enter Email address" value="{{ old('Email') }}" />
                            </div>
                        </div>
                        <label class="control-label">Contact Number <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="text" name="Contact_Number" class="form-control" placeholder="Enter Contact Number" value="{{ old('Contact_Number') }}" />
                            </div>
                        </div>
                        <label class="control-label">Username <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="text" name="Username" class="form-control" placeholder="Enter Username" value="{{ old('Username') }}" />
                            </div>
                        </div>
                        <label class="control-label">Password <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" name="Password" class="form-control" placeholder="Enter Password" value="{{ old('Password') }}" />
                            </div>
                        </div>
                        <label class="control-label">Re-enter Password <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" name="ReEnter_Password" class="form-control" placeholder="Re-enter Password" value="{{ old('ReEnter_Password') }}" />
                            </div>
                        </div>
                        <div class="register-buttons">
                            <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Sign Up</button>
                            <div class="m-t-20 m-b-40 p-b-40 text-inverse">
                                Already Registered? Click <a href="javascript:;" class="text-success link_check_registration">here</a> to check your account activation.
                            </div>
                        </div>
                        <hr />
                        <p class="text-center">
                            &copy; Department of Agriculture ICTS 2022
                        </p>
                    </form>
                </div>
            </div>
        </div>        
	</div>

    <div class="modal fade bd-example-modal-lg" id="Form_check_registration_Modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #008a8a">
            <div class="modal-header" style="background-color: #008a8a">
                <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Registered Account Status</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
            </div>
            <div class="modal-body" style="background-color: #fff">
                {{--modal body start--}} 
                <div class="">
                    <article class="card">
                        <div class="card-body">
                            <div class="row row-space-10">
                                <div class="col-md-4 m-b-15">
                                    <label class="control-label">Registration Code <span class="text-danger">*</span></label>
                                    <input type="text" name="Regs_code" class="form-control Regs_code" placeholder="Enter Registration Code" value="{{ old('Regs_code') }}" />
                                </div>
                                <div class="col-md-4 m-b-15">
                                    <label class="control-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="Regs_Email" class="form-control Regs_Email" placeholder="Enter Email address" value="{{ old('Regs_Email') }}" />
                                </div>
                                <div class="col-md-4" style="margin-top:18px;">
                                    <button type="button" class="btn btn-success btn-block btn-lg btn-reg-checkstatus"><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i><i class="fa fa-search"></i> Check Status</button>
                                </div>
                            </div>
                            <hr>
                            <article class="">
                                <div class="row">
                                    <div class="col"> <strong>Registration Date:</strong> <br> <span class="txt-registration-date"></span> </div>
                                    <div class="col"> <strong>Registered Name:</strong> <br> <span class="txt-registration-name"></span> </div>                                    
                                    <div class="col"> <strong>Company Name:</strong> <br> <span class="txt-company-name"></span> </div>
                                </div>
                            </article>
                            <div class="track">
                                <div class="step step-registration"> <span class="icon"> <i class="fa fa-users"></i> </span> <span class="text">Registration</span> </div>
                                <div class="step step-approve"> <span class="icon"> <i class="fa fa-file-alt"></i> </span> <span class="text"> Review and Approve</span> </div>
                                <div class="step step-complete"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Complete</span> </div>
                            </div>
                            <hr>
                        </div>
                    </article>
                </div>
                {{--modal body end--}}
            </div>
        </div>
    </div>
</div>
</body>
</html>
