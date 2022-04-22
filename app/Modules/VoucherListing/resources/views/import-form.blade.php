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
    <li class="breadcrumb-item active">Upload Module</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Voucher Generation</h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Upload Module</h4>
    </div>
    <div class="panel-body">
        
      <h4>Import Cleaned CSV File</h4>
      <form method="post" enctype="multipart/form-data" action="{{route('voucher.import')}}">
       {{ csrf_field() }}
        <div class="form-group">

           <div class="row mt-3">
            <div class="col-lg-6">
              <label for="title">Program</label>
              <select name="program_id" id="program_id" class="form-control input-sm dynamic" data-dependent="fund_id">
                <option value="">
                  Select Program
                </option>  
                @foreach($prog_data as $program)
                
                <option value="{{$program->program_id}}">
                  {{$program->title}}
                </option>
                @endforeach
              </select>
            </div>

            <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
            <script type="text/javascript">
              $(document).ready(function() {
              $('#program_id').on('change',function(){
              let id = $(this).val();
              $('#fund_id').empty();
              $('#fund_id').append("<option value='0' diabled selected>Processing..</option>");
              $.ajax({
                  type: 'GET',
                  url: 'GetSubCatAgainstMainCatEdit/'+id,
                  success: function (response) {
                  var response = JSON.parse(response);
                  console.log(response);
                  $('#fund_id').empty();
                  $('#fund_id').append("<option value='0' diabled selected>Select FundSource</option>");
                  response.forEach(element=> {
                   $('#fund_id').append('<option value="'+element['fund_id']+'">'+element['particulars']+'</option>');
                  });
                  }
              });
              });
              });
            </script>

            <div class="col-lg-6">
              <label for="title">Fund Source:</label>
              <select name="fund_id" id="fund_id" class="form-control input-sm dynamic" required>
              </select>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-lg-6">
              <label for="title"> Batch Description</label>
              <input type="text" name="batch_desc" id="batch_desc" class="form-control input-sm dynamic">
              <input type="hidden" name="batch_code" id="batch_code" value="{{$batch_code}}">
            </div>
            <div class="col-lg-6">
              <label for="title"> Choose CSV</label>
              <input type="file" name="csv_f" accept=".csv" class="form-control mb-2" />
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-lg-12">
                  <button type="submit" class="btn btn-md btn-success mt-0 ml-1">
                      <i class="fas fa-upload pull-left m-r-10 text-black"></i><b>Upload CSV</b>
                  </button>
            </div>
          </div>





        </div>
      </form>


    </div>
</div>
<!-- end panel -->
@endsection

