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

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{url('assets/plugins/parsley/dist/parsley.js')}}"></script>
<script src="{{url('assets/plugins/highlight/highlight.common.js')}}"></script>
<script src="{{url('assets/js/demo/render.highlight.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {
		App.init();
	});
</script>

{{-- Login Form Validation --}}
@include('Login::components.js.login')

{{-- Send Reset Link Form Validation --}}
@include('Login::components.js.send_reset_link')

{{-- Change password form --}}
@include('Login::components.js.change_password')

{{-- Verify OTP Page --}}
@include('Login::components.js.otp_form')

{{-- Verify OTP Page --}}
@include('Login::components.js.resend_otp')