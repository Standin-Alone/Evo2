@extends('global.base')
@section('title', "Download Mobile App")

{{--  import in this section your css files--}}
@section('page-css')
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />

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

<!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
<link href="assets/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet" />
<!-- <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" /> -->
<link href="assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL CSS STYLE ================== -->
<link href="assets/pgv/backend-style.css" rel="stylesheet">
    
@endsection


{{--  import in this section your javascript files  --}}
@section('page-js')
<!-- <script src="assets/plugins/gritter/js/jquery.gritter.js"></script> -->
<script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
<script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/js/demo/table-manage-default.demo.min.js"></script> 

<!-- ================== BEGIN BASE JS ================== -->
<script src="assets/plugins/jquery/jquery-3.2.1.min.js"></script>
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="assets/plugins/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/js-cookie/js.cookie.js"></script>
<script src="assets/js/theme/default.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.2/d3.min.js"></script>
<script src="assets/plugins/nvd3/build/nv.d3.js"></script>
<script src="assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js"></script>
<script src="assets/plugins/jquery-jvectormap/jquery-jvectormap-world-merc-en.js"></script>
<script src="assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
<!-- <script src="assets/plugins/gritter/js/jquery.gritter.js"></script> -->
<script src="assets/js/demo/dashboard-v2.min.js"></script>
<script src="assets/pgv/backend-script.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
        $(document).on('click','.btnDownload',function(){
            SpinnerShow('btnDownload','btnloadingIcon');
            SpinnerHide('btnDownload','btnloadingIcon');
        });
    });
</script>
@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Download Mobile App</li>
    </ol>
    <h1 class="page-header">Download Mobile App</h1>
        <div class="slider_area">
            <div class="single_slider  d-flex align-items-center slider_bg_1">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3>Voucher Management Platform <br>with Mobile Application</h3>
                            <p>Mobile application helps you to manage your transaction.</p>
                            <div class="video_service_btn">                              
                                <a href="{{ route('get.DownloadAppFiles') }}" class="btn btn-lg btn-primary btnDownload">  
                                    <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                                    <i class="fa fa-cloud-download-alt fa-2x pull-left m-r-10 text-black"></i>
                                    <b>Download Now</b><br />
                                    <small>Version 2.0</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:10px;">
                            <img src="assets/img/gallery/phone2.png" alt="" width="100%" height="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection