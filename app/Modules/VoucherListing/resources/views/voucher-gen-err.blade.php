@extends('global.base')
@section('title', "Voucher Printing")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />
@endsection

{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
    <script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}"></script>

    <script type="text/javascript">

      $(function () {

        var batch_no = '{{$batch_no}}';
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ route('voucher-err-listing', $batch_no ) }}",
            columns: [
                {data: 'voucher_remarks', name: 'voucher_remarks'},
                {data: 'rsbsa_no', name: 'rsbsa_no'},
                {data: 'last_name', name: 'last_name'},
                {data: 'first_name', name: 'first_name'},
                {data: 'middle_name', name: 'middle_name'},
                {data: 'ext_name', name: 'ext_name'},
                {data: 'brgy_desc', name: 'ext_name'},
                {data: 'mun_desc', name: 'ext_name'},
                {data: 'prv_desc', name: 'ext_name'},
                {data: 'birthday', name: 'ext_name'},
                {data: 'sex', name: 'ext_name'},
                {data: 'contact_no', name: 'ext_name'},
                {
                    data: 'if_4ps', name: 'if_4ps',
                        render: function(data, type, row) {
                            if_4ps = row.seed_class;
                            if( if_4ps == "1" ){
                                return "YES";
                            }else if( if_4ps == "0" ){
                                return "NO";
                            }else if( if_4ps != "1" && if_4ps != "2" ){
                                return "";
                            }
                        }
                },
                {
                    data: 'if_ip', name: 'if_ip',
                        render: function(data, type, row) {
                            if_ip = row.seed_class;
                            if( if_ip == "1" ){
                                return "YES";
                            }else if( if_ip == "2" ){
                                return "NO";
                            }else if( if_ip != "1" && if_ip != "2" ){
                                return "";
                            }
                        }
                },
                {
                    data: 'if_pwd', name: 'if_pwd',
                        render: function(data, type, row) {
                            if_pwd = row.if_pwd;
                            if( if_pwd == "1" ){
                                return "YES";
                            }else if( if_pwd == "2" ){
                                return "NO";
                            }else if( if_pwd != "1" && if_pwd != "2" ){
                                return "";
                            }
                        }
                },
                {data: 'farm_area', name: 'farm_area'},
                {
                    data: 'seed_class', name: 'seed_class',
                        render: function(data, type, row) {
                            seed_class = row.seed_class;
                            if( seed_class == "1" ){
                                return "HYBRID";
                            }else if( seed_class == "2" ){
                                return "INBRED";
                            }else if( seed_class != "1" && seed_class != "2" ){
                                return "";
                            }
                        }
                },
                {data: 'amount', name: 'amount'},
                {data: 'fund_desc', name: 'fund_desc'},
                {data: 'voucher_season', name: 'voucher_season'},
                
            ]
        });
        
      });


      $('#CancelModal').on('show.bs.modal', function (event) {
            

            var _button = $(event.relatedTarget); // Button that triggered the modal
            var _row = _button.parents("tr");
            var _FULL_NAME = _row.find(".FULL_NAME").val();
            var _REFERENCE_NO = _row.find(".REFERENCE_NO").val();
            var _AMOUNT = _row.find(".AMOUNT").val();
            var _FARM_AREA = _row.find(".FARM_AREA").val();

            var id = _button.data('batchc') 
            $("#batch_no").val(id); 
            $("#ref_no").val(_REFERENCE_NO); 
            $(".FULL_NAME").html(_FULL_NAME); 
            $(".REFERENCE_NO").html(_REFERENCE_NO); 
            $(".FARM_AREA").html(_FARM_AREA); 
            $(".AMOUNT").html(_AMOUNT); 
  
      })

      $('#RestoreModal').on('show.bs.modal', function (event) {
            

            var _button = $(event.relatedTarget); // Button that triggered the modal
            var _row = _button.parents("tr");
            var _FULL_NAME = _row.find(".FULL_NAME").val();
            var _REFERENCE_NO = _row.find(".REFERENCE_NO").val();
            var _AMOUNT = _row.find(".AMOUNT").val();
            var _FARM_AREA = _row.find(".FARM_AREA").val();

            var id = _button.data('batchc') 
            $("#batch_no_r").val(id); 
            $("#ref_no_r").val(_REFERENCE_NO); 
            $(".FULL_NAME").html(_FULL_NAME); 
            $(".REFERENCE_NO").html(_REFERENCE_NO); 
            $(".FARM_AREA").html(_FARM_AREA); 
            $(".AMOUNT").html(_AMOUNT); 
  
      })

    </script>
@endsection

<script>
    
</script>






@section('content')

<!-- begin page-header -->
<h1 class="page-header">Voucher Generation</h1>
<!-- end page-header -->


<div class="row">
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-black" style="height:85px;">
            <div class="stats-icon stats-icon-lg"><i class="far fa-window-close fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Total Voucher with Errors</div>
                <div class="stats-number">{{$error_ttl}}</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-6 col-md-6">
            &nbsp;
    </div>

    <div class="col-lg-3 col-md-6">
        <a href='vouchererrprintbatch/{{$batch_no}}' class="edit btn bg-gradient-green btn-md float-right mb-2" style="text-decoration: none; color:#FFFFFF;"> <i class="fas fa-download pull-left m-r-10 text-black"></i>Export Voucher Errors</a>
        <a href='voucherprintbatch/{{$batch_no}}' class="edit btn bg-red btn-md float-right mb-2" style="text-decoration: none; color:#FFFFFF;"> <i class="fas fa-trash pull-left m-r-10 text-black"></i>Clear Voucher Errors</a>
    </div>
    <!-- end col-3 -->
</div>






<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Upload Result</h4>
    </div>
    <div class="panel-body">
      {{ csrf_field() }} 
        <div class="form-group">

           <div class="row">
            <div class="col-lg-12">
                

                <table class="table table-bordered yajra-datatable" style="width:100%;">
                    <thead style="background-color:#ffffff; color:#2E353C; font-size: 12px;">
                        <tr>
                            <th style="color:#2E353C;">VOUCHER REMARKS</th>
                            <th style="color:#2E353C;">RSBSA_NO</th>
                            <th style="color:#2E353C;">LAST NAME</th>
                            <th style="color:#2E353C;">FIRST NAME</th>
                            <th style="color:#2E353C;">MIDDLE NAME</th>
                            <th style="color:#2E353C;">EXT NAME</th>
                            <th style="color:#2E353C;">BARANGAY</th>
                            <th style="color:#2E353C;">MUNICIPALITY</th>
                            <th style="color:#2E353C;">PROVINCE</th>
                            <th style="color:#2E353C;">BIRTHDATE</th>
                            <th style="color:#2E353C;">SEX</th>
                            <th style="color:#2E353C;">CONTACT NUMBER</th>
                            <th style="color:#2E353C;">4Ps BENEFICIARY</th>
                            <th style="color:#2E353C;">INDIGENOUS</th>
                            <th style="color:#2E353C;">PWD</th>
                            <th style="color:#2E353C;">FARM_AREA</th>
                            <th style="color:#2E353C;">SEEDCLASS</th>
                            <th style="color:#2E353C;">VOUCHER AMOUNT</th>
                            <th style="color:#2E353C;">FUND SOURCE</th>
                            <th style="color:#2E353C;">VOUCHER SEASON</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px;">
                    </tbody>
                </table>


            </div>
          </div>


        </div>
      </form>


    </div>
</div>


<!-- end panel -->
@endsection


