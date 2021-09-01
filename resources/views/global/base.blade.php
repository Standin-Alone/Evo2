{{-- @if(!(session('main_modules')->where('routes', Route::currentRouteName())->all() || session('sub_modules')->where('routes', Route::currentRouteName())->all()) )	    
	<script>window.location.href = "{{route('error.index')}}"</script>
@endif --}}

<?php echo
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title> @yield('title','Voucher Management Platform')</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
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
    
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<style>
		input.form-control.error{
			text-align: left;
		}
	</style>

	

    @section('page-css')
    @show

</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->

	{{-- Change Password --}}
	<!-- #modal-Change Password-->
	<div class="modal fade" id="ChangePassModal"  data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" style="max-width: 30%">
			<form id="ChangePassForm" method="POST" >
				@csrf
				<div class="modal-content">
					<div class="modal-header" style="background-color: #49b6d6">
						<h4 class="modal-title" style="color: white">Change your Default Password</h4>
						{{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button> --}}
					</div>
					<div class="modal-body">
						{{--modal body start--}}
						<label class="form-label hide"> ID</label>
						<input name="id" type="text" class="form-control hide" />

						<div class="col-lg-12">
							<div class="form-group">
								<label>Default Password</label>
								<input type="password"  name="default_pass" class="form-control"  placeholder="Enter your default password"  required="true">								
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">

								<label>New Password</label>
								<input type="password"  name="new_pass"  id="new_pass" class="form-control"  placeholder="Enter your new password" required="true">
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">
								<label>Confirm New Password</label>
								<input  type="password" name="confirm_new_pass" class="form-control"  placeholder="Enter your confirm new password" required="true">
							</div>
						</div>


								
					
						{{--modal body end--}}
					</div>
					<div class="modal-footer">
						
						<button type="submit" class="btn btn-info">Save Changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>


	<!-- begin #page-container -->
	<div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<div class="navbar-header">
				<a href="{{Request::url()}}" class="navbar-brand"> <img src="assets/img/logo/DA-Logo.png" width="30" height="30" style="display: inline-block"  /> <b> Voucher Management Platform</b> | @yield('title')</a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header -->
			
			<!-- begin header-nav -->
			<ul class="navbar-nav navbar-right">							
				<li class="dropdown navbar-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/img/user/user-13.jpg" alt="" /> 
						<span class="d-none d-md-inline">John Doe 
							
				</span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="javascript:;" class="dropdown-item">Edit Profile</a>																		
						<div class="dropdown-divider"></div>
						<a href="javascript:;" class="dropdown-item">Log Out</a>
					</div>
				</li>
			</ul>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar sidebar-transparent gradient-enabled" >
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile" >
						<a href="javascript:;" data-toggle="nav-profile">
							<div class="cover with-shadow"></div>
							<div class="image">
								<img src="assets/img/user/user-13.jpg" alt="" />
							</div>
							<div class="info">
							
								John Doe
                                 {{-- {{session('session_name')}} --}}
								<small>
                                    Admin
                                    {{-- {{session('position')}} --}}
                                </small>
							</div>
						</a>
					</li>					
				</ul>
				<!-- end sidebar user -->				
					@include('sidebar.sidebar')								
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg" style="background-image: url('assets/img/cover/farmer.jpg');background-position: center;" ></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
            @yield('content')            
		</div>
		<!-- end #content -->
		    
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-3.2.1.min.js"></script>
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
        
    @section('page-js')	
    @show
    
	


	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageDefault.init();
			
		});
	</script>



{{-- Change Password First Log in  --}}
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
	<script>

		$(document).ready(function(){
			$("#ChangePassForm").validate({

				rules:{
					default_pass:{
						required:true,
						remote:{
							url:"{{route('check-default-pass')}}",
							type:'get'
						}						
					},
					new_pass:{
						required:true,																
					},
					confirm_new_pass:{
						required:true,
						equalTo:'#new_pass'										
					},
				},
				messages:{
					default_pass:{
						required:'<div class="text-danger">This field is required</div>',
						remote: "<div class='text-danger'>Your password doesn't match to your default password.</div>"
					},
					new_pass:{
						required:'<div class="text-danger">This field is required.</div>',						
					},
					confirm_new_pass:{
						required:true,
						equalTo: "<div class='text-danger'>Your confirm password doesn''t match to your new password.</div>"
					}
				},
				submitHandler:function(){
					$.ajax({
						url:"{{route('change-default-pass')}}",
						type:'post',
						data:$("#ChangePassForm").serialize(),
						success:function(data){
							if(Boolean(data) == true){
								swal("Successfully change your Password!", {
                                    icon: "success",
                                }).then(()=>{                                                        
									swal("Welcome To Voucher Management System", {
                                 	   icon: "success",
                                	}).then(()=>{
										$("#ChangePassModal").modal('hide');
									})
                                    
                                });						
								
							}else{
								swal("Something went wrong.", {
									icon: "error",
								});
							}
						},
						error:function(){
							swal("Something went wrong.", {
									icon: "error",
								});
						}
					})
				}				
			});




		});

		
		// $("#ChangePassModal").modal('show');
		// $.ajax({
		
		// 	type:'post',
		// 	data:{_token:'{{csrf_token()}}'},
		// 	success:function(data){
				
		// 	},
		// 	error:function(){

		// 	}
		// })

	</script>

</body>
</html>
