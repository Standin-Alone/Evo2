@extends('Login::layouts.template')
@section('title', "Reset Password")

@section('content')
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin login -->
        <div class="login login-with-news-feed">
            <!-- begin news-feed -->
            <div class="news-feed">
                <div class="news-image" style="background-image: url({{url('assets/img/images/IMP.jpg')}})"></div>
                <div class="news-caption">
                    {{-- <h4 class="caption-title"><b>Interventions Management Platform</b> </h4>
                    <p>
                        
                    </p> --}}
                </div>
            </div>
            <!-- begin right-content -->
            <div class="right-content">
                <!-- begin login-header -->
                <div class="login-header">
                    <div class="brand">
                        <img src="{{url('assets/img/logo/DA-Logo.png')}}" width="30" height="30" style="display: inline-block"  /> <b>Interventions Management Platform</b>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>
                </div>
                <!-- begin login-content -->
                <div class="login-content">
                    <form id="change_password_form" method="POST" action="{{route('update.password', ['uuid' => $user->user_id])}}" class="margin-bottom-0">
                        {{ csrf_field() }}
                        {{ method_field("PATCH") }}

                        <span class="error_password"></span>
                        {{-- <div class="form-group m-b-15">
                            <input type="password" name="old_password" class="form-control form-control-lg" placeholder="Old Password" />
                            <span class="error_msg"></span>
                        </div> --}}
                        <div class="form-group m-b-15">
                            <input type="password" name="new_password" class="form-control form-control-lg" placeholder="New Password" />
                            <span class="error_msg"></span>
                        </div>
                        <div class="login-buttons">
                            <button type="submit" id="submit-btn" class="btn btn-success btn-block btn-lg btn-change-pass">SAVE NEW PASSWORD</button>
                        </div>
                        <hr />
                        <p class="text-center text-grey-darker">
                            &copy; Department of Agriculture 2021 
                        </p>
                    </form>
                </div>
                <!-- end login-content -->
            </div>
            <!-- end right-container -->
        </div>
        <!-- end login -->     
	</div>
	<!-- end page container -->        
@endsection