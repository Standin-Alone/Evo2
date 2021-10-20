@extends('Login::layouts.template')
@section('title', "Interventions Management Platform | OTP")

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
                 <div class="news-image" style="background-image: url({{url('assets/img/images/IMP.jpg')}})"></div>
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
                    <form id="otp_form" method="POST" action="{{route('form.check_otp_verification')}}" class="margin-bottom-0">
                        {{ csrf_field() }}
                        
                        @foreach ($users_otp as $u_id)
                            <input type="hidden" id="user_uuid" name="user_uuid" value={{$u_id->user_id}}>
                        @endforeach
                        <span class="error_otp"></span>
                        <div class="form-group m-b-15">
                            <input type="number" name="otp" class="form-control form-control-lg" placeholder="Input OTP" />
                            <span class="error_msg"></span>
                        </div>
                        <div class="login-buttons">
                            <button type="submit" id="submit-btn" class="btn btn-success btn-block btn-lg">SUBMIT</button>
                        </div>
                    </form>
                    <br>
                    <hr>
                    <form id="reset_otp_form" action="{{route('reset_otp_link')}}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        @foreach ($users_otp as $u_id)
                            <input type="hidden" id="user_uuid" name="user_uuid" value={{$u_id->user_id}}>
                         @endforeach
                        <div class="col-md-12">
                            <p>If you haven't received the OTP in your email, resend the code.</p>
                            <button type="submit" id="submit-btn" class="btn btn-outline-success btn-block btn-lg btn-resend-otp">RESEND OTP</button>
                            <small class="form-text">*Note: The OTP pin will be expire on 24hrs.</small>
                        </div>
                    </form>
                    <br>
                    <br>
                    <hr />
                    <p class="text-center text-grey-darker">
                        &copy; Department of Agriculture 2021 
                    </p>
                </div>
                 <!-- end login-content -->
             </div>
             <!-- end right-container -->
         </div>
         <!-- end login -->
                 
     </div>
     <!-- end page container -->   
@endsection
