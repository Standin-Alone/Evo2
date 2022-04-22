
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
	<title> @yield('title','Interventions Management Platform')</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="{{url('assets/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/plugins/bootstrap/4.0.0/css/bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/plugins/animate/animate.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/css/default/style.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/css/default/style-responsive.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/css/default/theme/default.css')}}" rel="stylesheet" id="theme" />
	<link href="{{url('assets/css/farmer-module/style.css')}}" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
    
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="{{url('assets/plugins/pace/pace.min.js')}}"></script>


	<style>
		input.form-control.error{
			text-align: left;
		}

		.gradient-bg{			
			background-color: #89d8d3;
			background-image: linear-gradient(315deg, #89d8d3 0%, #03c8a8 74%);
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
								<input type="password"  name="new_pass"  id="new_pass" class="form-control"  placeholder="Enter your new password"  required="true">
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
				<a href="{{Request::url()}}" class="navbar-brand"> <img src="{{url('assets/img/images/DA-LOGO-1024x1024.png')}}" width="30" height="30" style="display: inline-block"  /> <b> Interventions Management Platform</b> </a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header -->
			
			<!-- begin header-nav -->
			<ul class="navbar-nav navbar-right">	
				<li class="btn-group" role="group" style="margin-top:7px;">
					<button type="button" class="btn btn-success dropdown-toggle no-caret ">
						<span class="showSupplierProgram">{{session('Default_Program_Desc')}}</span>
					</button>
					<ul class="dropdown-menu">
						<li class="text-muted" style="text-align:center;">Select Program</li>     
						@php
							if (!empty(session('user_id'))) {                                                                                                               
								for($i = 0 ; $i < count(session('Supplier_programs')) ; $i++) {
								echo '<li><a href="javascript:void(0)" data-selectedprogramid="'.session('Supplier_programid')[$i].'" class="selectedSupplierProgram">'.session('Supplier_programs')[$i].'</a></li>';
								}
							}else{
								return redirect('/login');
							} 
							
						@endphp
					</ul>
					<button type="button" class="btn btn-success" data-toggle="dropdown">
						<span class="caret"></span>
					</button>
				</li>	
				
						
				<li class="dropdown  dropdown-menu-right ">
					<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14 open-notif-btn">
						<i class="fa fa-bell"></i>
						<span class="label label-danger notif-count" style="background:#ff5b57">{{session('notification_count') }}</span>
					</a>
					
					<ul class="dropdown-menu media-list dropdown-menu-right" id="notif-dropdown">
						<li class="dropdown-header notification-count-label">NOTIFICATIONS ({{session('notification_count') }})</li>
						<li class="media loading justify-content-center"> <i class="fas fa-spinner fa-spin  fa-5x" style="color:#03c8a8;"></i></li>

						<li class="media no-notif justify-content-center" style="display:none" > 
									<div class="media-body justify-content-center">  	
										<h6 class="media-heading justify-content-center" style="text-align:center">You have no current notifications.</h6> 
									</div>
						</li>
						
						<li class="dropdown-footer text-center">
							<a href="javascript:;">View more</a>
						</li>
					</ul>
				</li>






















				<li class="dropdown navbar-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{url('assets/img/images/profile/profile-user-black.png')}} "  alt="" /> 
						<span class="d-none d-md-inline" >{{session('first_name')}} {{session('last_name')}}</span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{route('user.profile')}}" class="dropdown-item">Edit Profile</a>																		
						<div class="dropdown-divider"></div>
						<a href="{{ route('user.logout') }}" class="dropdown-item">Log Out</a>
					</div>
				</li>
			</ul>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<a href="javascript:;" data-toggle="nav-profile">
							<div class="cover with-shadow" style="background-image: url({{ url('assets/img/cover/profile-cover.jpg')}})"></div>
							<div class="image">
								<img src="{{url('assets/img/images/profile/profile-user.png')}}" alt="" style="width:500pt; height:500pt;"/>
							</div>
							<div class="info" style="text-transform: uppercase">
								<b class="caret pull-right"></b>
								{{session('first_name')}} {{session('last_name')}}
								<small>
									{{session('user_region_name')}}
								</small>
								<br/>
							</div>
						</a>
					</li>
					<li>
						<ul class="nav nav-profile">
							<li class="has-sub {{Route::currentRouteName() == 'user.profile' ? 'active' : null}}">
								<a href="{{route('user.profile')}}">
									<span>User Profile</span> 
								</a>
							</li>
                        </ul>
					</li>
				</ul>
				<!-- end sidebar user -->				

				{{-- @if(session('role') == 'supplier')
					@include('sidebar.supplier')				
				@else
					@include('sidebar.amas')
				@endif --}}
				@include('sidebar.sidebar')
						
			</div>
			
			<!-- end sidebar scrollbar -->
			<div class="sidebar-bg" style="background-image: url({{url('assets/img/cover/farmer.jpg')}});opacity:0.1;z-index:-1; " ></div>
		</div>
		
		{{-- <div class="sidebar-bg" style="background-image: url('{{url('assets/img/cover/farmer.jpg')}}');background-position: center;" ></div> --}}
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
	<script src="{{url('assets/plugins/jquery/jquery-3.2.1.min.js')}}"></script>
	<script src="{{url('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
	<script src="{{url('assets/plugins/bootstrap/4.0.0/js/bootstrap.bundle.min.js')}}"></script>
	<!--[if lt IE 9]>
		<script src="../assets/crossbrowserjs/html5shiv.js"></script>
		<script src="../assets/crossbrowserjs/respond.min.js"></script>
		<script src="../assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="{{url('assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
	<script src="{{url('assets/plugins/js-cookie/js.cookie.js')}}"></script>
	<script src="{{url('assets/js/theme/default.min.js')}}"></script>
	<script src="{{url('assets/js/apps.min.js')}}"></script>
	<!-- ================== END BASE JS ================== -->
        
    @section('page-js')	
    @show
    
	


	
	<script>
		$(document).ready(function() {
			App.init();
			TableManageDefault.init();
			// FormPlugins.init();

			// CALL SELECT PROGRAM UPON CLICK THE SELECTED PROGRAM TO FILTER ON DATATABLE
			$(document).on('click','.selectedSupplierProgram',function(){
				var selectedProgramDesc =  $(this).html(),
					selectedProgramId =  $(this).data('selectedprogramid');
					$('.showSupplierProgram').html(selectedProgramDesc);
					// alert(selectedProgramId);
					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type:'post',
						url:"{{ route('update.ProgramDefault') }}",
						data:{selectedProgramDesc:selectedProgramDesc,selectedProgramId:selectedProgramId},
						success:function(data){                         
							location.reload();
						},
						error: function (textStatus, errorThrown) {
							console.log('Err');
						}
					});
			});	
		});
	</script>



{{-- Change Password First Log in  --}}
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
	<script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
	<script src="{{url('assets/js/socket.js')}}"></script>
	<script>



		var get_width = 0;          
        // start websocket   
        //  connect to websocket                		
        socket().on("connect", function() {            
                //  get progress of uploading
                console.warn('connected');			

				socket().emit('join-room',{room:{from:"{{ session('uuid') }}"}})
		});


		// GET  NOTIFICATION
		socket().on("notification", function(notif) {            
                //  get progress of uploading
			console.warn(notif);
			let notif_sum = $(".notif-count").text();
			
				if(notif.room.to == "{{ session('uuid') }}"){
					 $.gritter.add({
							title: 'Notification from <b>'+notif.room.senderName+'</b>',
							text: '<p style="color:white">'+notif.message+'</p>',
							sticky: true
					});

					count_notif  = parseInt(notif_sum)+1;
					 $(".notification-count-label").text('NOTIFICATIONS ('+count_notif+')');
					$(".notif-count").text(count_notif);
				}			
		});



		
		function dateSort(a, b) {
				
    			return new Date(b.date).getTime() - new Date(a.date).getTime();
		}


		function getNotification(element){
			$(".notif-data").remove();
				$(".no-notif").hide();
				if(element.attr('aria-expanded') == 'false' || element.attr('aria-expanded') == undefined ){
					
					$.ajax({
						url:"{{route('get-notifications')}}",
						type:'post',                    
						data:{_token:'{{ csrf_token() }}'},
						success:function(response){             
							let responseClean = JSON.parse(response);
							
							if(responseClean.length != 0){
								// hide loader
								sortedResponse = responseClean.sort(dateSort);
								console.warn(sortedResponse);
								$(".loading").hide();
								
								// append notification
								sortedResponse.map((item,index)=>{
									
									if(index <= 5){
										$(".dropdown-footer").before(
											'<li class="media notif-data ">'+
												// ${item.link ? item.link : '#'}
												`<a  href="#" class="read-btn" notification_id="${item.notif_id}">`+
													'<div class="media-left">'+												
														'<i class="fa fa-bell media-object bg-warning"></i>'+
													'</div>'+
													'<div class="media-body">'+
														`<h6 class="media-heading">${item.senderName}</h6>`+
														`<p style="font-weight:${item.status == 'unread' ? 'bold' : 'regular'};">${item.message}</p>`+
														'<div class="text-muted f-s-11">'+item.date+'</div>'+
													'</div>'+
												'</a>'+
											'</li>')
									}
								});
							}else{

								$(".no-notif").show();
								$(".loading").hide();
							}
						},
						error:function(error){
							console.warn(error)
						}                                            
					});
				}else{
					$(".loading").show();
					$(".notif-data").remove();
				}
		}

		
			

		$(document).ready(function(){

			// OPEN NOTIFICATION
			$(".open-notif-btn").on('click',function(){
				getNotification($(this));
			});


			//READ NOTIFICATION 
			$("#notif-dropdown").on('click','.read-btn',function(e){
				e.preventDefault();
		
				notificationId = $(this).attr('notification_id');
				
				$.ajax({
						url:"{{route('read-notification')}}",
						type:'post',                    
						data:{_token:'{{ csrf_token() }}',notification_id:notificationId},
						success:function(response){     
							let parseResponse = JSON.parse(response);
							if(parseResponse['message'] == true){
								// get updated notification
								getNotification($(".open-notif-btn"));
								// redirect to link after clicking the notification
								console.warn( parseResponse['link']);
								location.href =  parseResponse['link'] ? parseResponse['link'] : '#';
								
							}else{
								console.warn(parseResponse['error']);
							}
							
						},
						error:function(error){
							console.warn(error)
						}                                            
					});

			});




			jQuery.validator.addMethod("password_pattern", function(value,element,param){		
				return value.match(/^(?=.*[A-Za-z])(?=.*[\W])/);
				
			},'<div class="text-danger">*Your password is atleast one uppercase,one lowercase and one special character.</div>');



			$("#ChangePassForm").validate({

				rules:{
					default_pass:{
						required:true,
						remote:{
							url:"{{route('check-default-pass')}}",
							type:'get'
						},
						
					},
					new_pass:{
						required:true,																
						minlength: 8
					},
					confirm_new_pass:{
						required:true,
						equalTo:'#new_pass'										
					},
				},
				messages:{
					default_pass:{
						required:'<div class="text-danger">*This field is required</div>',
						remote: "<div class='text-danger'>*Your password doesn't match to your default password.</div>",						
					},
					new_pass:{
						required:'<div class="text-danger">This field is required.</div>',						
						minlength: jQuery.validator.format("<div class='text-danger'>*Enter at least {0} characters</div>")
					},
					confirm_new_pass:{
						required:true,
						equalTo: "<div class='text-danger'>*Your confirm password doesn''t match to your new password.</div>"
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
									swal("Welcome To Interventions Management Platform", {
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

		
	</script>


	@php
	$check_first_login = DB::table('users')->where('user_id',session('uuid'))->first();	
	@endphp

	@if($check_first_login->first_login == '1')
			<script>
				$("#ChangePassModal").modal('show');
			</script>	
	@endif
</body>
</html>
