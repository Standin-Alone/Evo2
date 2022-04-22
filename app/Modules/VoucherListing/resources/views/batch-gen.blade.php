@extends('global.base')
@section('title', "Batch Generation")




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
    <style type="text/css">
       table.dataTable thead th {
            border-left: none;
            border-right: none;
            border-top: none;
            background-color: #203239;
        }
         
        table.dataTable tbody td {
            border-left: none;
            border-right: none;
        }

        table.dataTable {
            border: none;
        }

    </style>
    <script type="text/javascript">
      $(function () {
        
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('batch-listing') }}",
            columns: [
                {
                    data: 'application_number', name: 'application_number',
                        render: function(data, type, row) {
                            submitted_tag = "";
                            clm_v = row.CLAIMED_VOUCH;
                            unclm_v = row.UNCLAIMED_VOUCH;
                            cancel_v = row.CANCELLED_VOUCH;
                            error_v = row.WITH_ERROR;
                            total_v = parseInt(clm_v)+parseInt(unclm_v);
                            return submitted_tag = '<center><img class="result-image float-left ml-3 mr-3 mt-3" src="assets/img/images/icon-gold-note.png" height="60"/></center><div class="mt-3"><span class="text-inverse"><h5>'+row.batch_desc+'</h5></span><i class="fas fa-calendar fa-xs mr-1 mb-2"></i>Date Uploaded: '+row.uploaded_date+'<br><i class="fas fa-info-circle fa-xs mr-1"></i>Total Active Vouchers: '+total_v+'<i class="fas fa-info-circle fa-xs mr-1 ml-3"></i>Cancelled: '+cancel_v+'<i class="fas fa-info-circle fa-xs mr-1 ml-3"></i>Errors: '+error_v+'</span></div>';
                        }
                },
                {
                    data: 'application_number', name: 'application_number',
                        render: function(data, type, row) {
                            submitted_tag = "";
                            clm_v = row.CLAIMED_VOUCH;
                            unclm_v = row.UNCLAIMED_VOUCH;
                            cancel_v = row.CANCELLED_VOUCH;
                            total_v = row.TOTAL_VOUCH;
                            total_targ = row.total_rows;
                            stat_upload = parseFloat((total_v/total_targ)*100).toFixed(2);
                            unclm_v_p = 0;
                            clm_v_p = 0;
                            clm_v_p = parseFloat((clm_v/total_v)*100).toFixed(2);
                            unclm_v_p = parseFloat((unclm_v/total_v)*100).toFixed(2);
                            cancel_v_p = parseFloat((cancel_v/total_v)*100).toFixed(2);

                            if( total_v == total_targ ) {

                                return submitted_tag = '<div class="mt-4"><center>Claimed / Unclaimed / Cancelled<div class="progress rounded-corner mb-1 mt-1" style="height:10px; width:60%;"><div class="progress-bar bg-lime" style="width: '+clm_v_p+'%"></div><div class="progress-bar bg-secondary" style="width: '+unclm_v_p+'%"></div><div class="progress-bar bg-red" style="width: '+cancel_v_p+'%"></div></div>'+clm_v_p+'% / '+unclm_v_p+'% / '+cancel_v_p+'%</center></div>';
                                                
                            }else{
                                
                                return submitted_tag = '<div class="mt-4"><center>Status of Upload<div class="progress rounded-corner mb-1 mt-1" style="height:10px; width:60%;"><div class="progress-bar bg-lime" style="width: '+stat_upload+'%"></div></div>'+total_v+' / '+total_targ+'</center></div>';
                                                
                            }


                        }
                },
                {
                    data: 'application_number', name: 'application_number',
                        render: function(data, type, row) {
                            submitted_tag = "";

                            if( total_v == total_targ ) {

                                return submitted_tag = '<a href="vouchergen/'+row.batch_code+'" class="edit btn bg-gradient-green btn-sm mr-2"  style="color: #ffffff; text-decoration: none;"><i class="fas fa-eye fa-xs mr-1 ml-1"></i> View All Vouchers</a><br><button type="button" data-batchc="'+row.batch_code+'" data-toggle="modal" data-target="#ViewApprovedHistoryDetailsModal" class="btn btn-danger btn-sm revisar mt-1" id="getActualizaId"><i class="fas fa-trash fa-xs mr-1 ml-1"></i> Cancel Batch</button><input type="hidden" class="batch_desc" value="'+row.batch_desc+'"><input type="hidden" class="CLAIMED_VOUCH" id="CLAIMED_VOUCH" value="'+row.CLAIMED_VOUCH+'"><input type="hidden" class="UNCLAIMED_VOUCH" value="'+row.UNCLAIMED_VOUCH+'"  id="UNCLAIMED_VOUCH"><input type="hidden" class="uploaded_date" value="'+row.uploaded_date+'"  id="uploaded_date"><a href="vouchererrgen/'+row.batch_code+'" class="edit btn btn-secondary btn-sm mr-2 mt-1"  style="color: #ffffff; text-decoration: none;"><i class="fas fa-ban fa-xs mr-1 ml-1"></i> View Errors</a>';                
                            
                            }else{

                                return submitted_tag = '<a href="#" class="edit btn bg-secondary btn-sm mr-2"  style="color: #ffffff; text-decoration: none;"><i class="fas fa-eye fa-xs mr-1 ml-1" disabled></i> View All Vouchers</a><br><button type="button" data-batchc="'+row.batch_code+'" class="btn btn-secondary btn-sm revisar mt-1" id="getActualizaId" disabled><i class="fas fa-trash fa-xs mr-1 ml-1"></i> Cancel Batch</button><input type="hidden" class="batch_desc" value="'+row.batch_desc+'"><input type="hidden" class="CLAIMED_VOUCH" id="CLAIMED_VOUCH" value="'+row.CLAIMED_VOUCH+'"><input type="hidden" class="UNCLAIMED_VOUCH" value="'+row.UNCLAIMED_VOUCH+'"  id="UNCLAIMED_VOUCH"><input type="hidden" class="uploaded_date" value="'+row.uploaded_date+'"  id="uploaded_date"><a href="#" class="edit btn btn-secondary btn-sm mr-2 mt-1"  style="color: #ffffff; text-decoration: none;"><i class="fas fa-ban fa-xs mr-1 ml-1" disabled></i> View Errors</a>';                
                            }
                            
                        }
                },
            ]
        });
        
      });

      $('#ViewApprovedHistoryDetailsModal').on('show.bs.modal', function (event) {
            

            var _button = $(event.relatedTarget); // Button that triggered the modal
            var _row = _button.parents("tr");
            var _CLAIMED_VOUCH = _row.find(".CLAIMED_VOUCH").val();
            var _UNCLAIMED_VOUCH = _row.find(".UNCLAIMED_VOUCH").val();
            var _batch_desc = _row.find(".batch_desc").val();
            var _uploaded_date = _row.find(".uploaded_date").val();

            var id = _button.data('batchc') 
            $("#batch_code").val(id); 
            $(".unclaimed_vouch").html(_UNCLAIMED_VOUCH); 
            $(".claimed_vouch").html(_CLAIMED_VOUCH); 
            $(".uploaded_date").html(_uploaded_date); 
            $(".batch_desc").html(_batch_desc); 
  
      })

    </script>

@endsection


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript:;">Voucher Generation</a></li>
    <li class="breadcrumb-item active">Batch View</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Batch View</h1>
<!-- end page-header -->
{{ csrf_field() }}

<div class="row">
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-check fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Claimed Vouchers</div>
                <div class="stats-number">{{$claimed_ttl}}</div>
                <div class="stats-desc">Vouchers that cant be cancelled</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="far fa-window-close fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Unclaimed Vouchers</div>
                <div class="stats-number">{{$unclaimed_ttl}}</div>
                <div class="stats-desc">Vouchers that can be cancelled</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="far fa-address-card fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Total Active Vouchers</div>
                <div class="stats-number">{{$ttl_vch}}</div>
                <div class="stats-desc">Vouchers that are active and can be scanned</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-black">
            <div class="stats-icon stats-icon-lg"><i class="far fa-eye-slash fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Total Cancelled Vouchers</div>
                <div class="stats-number">{{$ttl_cncl}}</div>
                <div class="stats-desc">Inactive Vouchers and cant be scanned</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
</div>
<!-- end row -->
<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Voucher Database</h4>
    </div>
    <div class="panel-body">
        
        <div class="form-group">

                <table class="table table-bordered yajra-datatable" style="width:100%;">
                    <thead style="background-color:#FFFFFF; font-size: 12px;">
                        <tr>
                            <th style="color:#242A30; background-color:#FFFFFF;" width="50%">Batch Management</th>
                            <th style="color:#242A30; background-color:#FFFFFF;" width="30%"></th>
                            <th style="color:#242A30; background-color:#FFFFFF;" width="20%"></th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px;">
                    </tbody>
                </table>

        </div>
      </form>


    </div>
</div>



<div class="modal fade bd-example-modal-md" id="ViewApprovedHistoryDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
            <div class="modal-content" style="background-color: #FFFFFF">
                <div class="modal-header" style="background-color: #2E353C">
                    <h5 class="modal-title" style="color: white"><i class="fa fa-info-circle mr-1"></i> Confirm Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                <form method="post" enctype="multipart/form-data" action="{{route('delete-batch')}}">
                @csrf
                    <!-- begin right-content -->
                   <div class="row">
                        <div class="col-lg-2">
                            <span style="font-size: 60px;"><i class="fas fa-trash fa-xs mr-1 ml-4"></i></span>
                        </div>
                        <div class="col-lg-10 pt-3 pb-3 pl-4" style="font-size;50px;">
                            <input type="hidden" name="batch_code" class="batch_code" id="batch_code">
                            Batch Description: <b><span class="batch_desc"></span></b><br>
                            Upload Date: <b><span class="uploaded_date"></span></b><br>
                            Total Vouchers to be Cancelled (Unclaimed): <b><span class="unclaimed_vouch" style="color:#8E0F0F"></span></b><br>
                            Total Vouchers to Keep (Claimed): <b><span class="claimed_vouch" style="color:#17492E"></span></b><br>
                        </div>
                    </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <button type='submit' class="btn btn-danger" ><i class="fa fa-check-circle"></i> Confirm Cancellation</button>
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>

                </form>
            </div>
        </form>
    </div>
</div>
<!-- end panel -->
@endsection


