@extends('global.base')
@section('title', "Supplier Registration")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
  <script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
  <script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
  <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
  <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/js/demo/table-manage-default.demo.min.js"></script>
@endsection




<script>
    
</script>






@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript:;">Voucher Generation</a></li>
    <li class="breadcrumb-item active">Upload Result</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Voucher Generation</h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Upload Result</h4>
    </div>
    <div class="panel-body">
        
	  <h4>Voucher Upload Result</h4>
      <form method="post" enctype="multipart/form-data" action="{{route('voucher.import')}}">
       {{ csrf_field() }}

       @if($tmp_status=='valid')         
       
       <div class="form-group">

           <div class="row mt-3">
            <div class="col-lg-12">
				              
				<div class="progress rounded-corner mb-3">
				  <div class="progress-bar bg-lime" style="width: {{$cln_perc}}%">
				  	  {{$cln_perc}}%
				  </div>
				  <div class="progress-bar bg-red" style="width: {{$err_perc}}%">
				      {{$err_perc}}%
				  </div>
				</div>
				<h5><i class="fas fa-archive pull-left m-r-10 text-black"></i>Total Vouchers Uploaded : {{$total_rows}}</h5>
				<h5><i class="fas fa-exclamation-circle pull-left m-r-10 text-black"></i>Total Vouchers with Error : {{$err_rows}}</h5>
				<h5><i class="fas fa-check pull-left m-r-10 text-black"></i>Total Clean Vouchers : {{$cln_rows}}</h5>

				<a href="{{ url('/import-form') }}" class="btn btn-md btn-success mt-3 ml-1"><i class="fas fa-upload pull-left m-r-10 text-black"></i>
                  <b>Upload Another CSV</b>
              	</a>
				<a href="{{ url('/vouchergen/'.$batch_code) }}" class="btn btn-md btn-primary mt-3 ml-1"><i class="fas fa-address-card pull-left m-r-10 text-black"></i>
                  <b>Print Generated Vouchers</b>
              	</a>
                <a href="{{ url('/batchgen') }}" class="btn btn-md btn-secondary mt-3 ml-1"><i class="fas fa-list pull-left m-r-10 text-black"></i>
                  <b>Batch Lists</b>
                </a>
            </div>
          </div>


        </div>

        @else
        
        <div class="form-group">

        <div class="row mt-3">
          <div class="col-lg-12">
                  
            <h4><i class="fas fa-ban pull-left m-r-10 text-black"></i>Invalid Template Uploaded</h4>
            <h5>Kindly reupload the correct tempalte to proceed. Thank you</h5>
            <a href="{{ url('/import-form') }}" class="btn btn-md btn-success mt-3 ml-1"><i class="fas fa-upload pull-left m-r-10 text-black"></i>
                  <b>Back to Upload CSV</b>
            </a>
        
          </div>
        </div>


        </div>
        
        
        @endif


      </form>


    </div>
</div>
<!-- end panel -->
@endsection


