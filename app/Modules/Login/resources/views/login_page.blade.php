@extends('Login::layouts.template')
@section('title', "Interventions Management Platform | Login")

@section('content')
 	<!-- begin #page-loader -->
     <div id="page-loader" class="fade show"><span class="spinner"></span></div>
     <!-- end #page-loader -->
     
     <!-- begin #page-container -->
     <div id="page-container" class="fade">
         <!-- begin login -->
         <div class="login login-with-news-feed">
             <!-- begin news-feed -->
             <div class="news-feed">
                 <div class="news-image" style="background-image: url(assets/img/images/IMP.jpg)"></div>
                 {{-- <div class="news-caption">
                     <h4 class="caption-title"><b>Interventions Management Platformssss</b> </h4>
                     <p>
                         
                     </p>
                 </div> --}}
             </div>
             <!-- end news-feed -->
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
                 <!-- end login-header -->
                 <!-- begin login-content -->
                 <div class="login-content">
                     <form id="login_form" method="POST" action="{{ route('user.login') }}" class="margin-bottom-0">
                         @csrf
                         <span class="error_email_pass"></span>
                         <div class="form-group m-b-15">
                            <input id="user_email" type="text" name="email" class="form-control form-control-lg" placeholder="Email Address"/>
                            <span class="error_msg"></span>
                         </div>
                         <div class="form-group m-b-15">
                            <input id="user_password" type="password" name="password" class="form-control form-control-lg" placeholder="Password" />
                            <span class="error_msg"></span>
                         </div>
                         {{-- <div class="checkbox checkbox-css m-b-30">
                             <input type="checkbox" id="remember_me_checkbox" value="" />
                             <label for="remember_me_checkbox">
                                 Remember Me
                             </label>
                         </div> --}}
                         <div class="col-md-12 text-center ">
                             <a class="btn btn-link" href="{{ route('form.confirmation.reset.password') }}">
                                 {{ __('Forgot Your Password?') }}
                             </a>
                         </div>
 
                         <div class="login-buttons">
                             <button type="submit" id="submit-btn" class="btn btn-success btn-block btn-lg btn-log"><i></i> SIGN ME IN</button>
                         </div>
                         {{-- <div class="m-t-20 m-b-40 p-b-40 text-inverse">
                             Not a member yet? Click <a href="register_v3.html" class="text-success">here</a> to register.
                         </div> --}}
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
