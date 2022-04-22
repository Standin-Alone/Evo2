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
            ajax: "{{ route('voucher-listing', $batch_no ) }}",
            columns: [
                {data: 'rsbsa_no', name: 'rsbsa_no'},
                {data: 'first_name', name: 'first_name'},
                {data: 'middle_name', name: 'middle_name'},
                {data: 'last_name', name: 'last_name'},
                {data: 'voucher_status', name: 'ext_name'},
                {data: 'farm_area', name: 'farm_area'},
                {data: 'amount', name: 'amount'},
                {data: 'reference_no', name: 'reference_no'},
                {
                    data: 'reference_no', name: 'reference_no',
                        render: function(data, type, row) {
                            submitted_tag = "";
                            status = row.voucher_status;

                                submitted_tag = "<a href='voucherprint/"+row.reference_no+"' class='edit btn bg-gradient-green btn-sm mr-2' style='color:#ffffff;'><i class='fas fa-print fa-xs mr-1 ml-1'></i>Print</a>";

                            if( status == "NOT YET CLAIMED" ) {

                                submitted_tag += "<button type='button' data-batchc='"+row.batch_code+"' data-toggle='modal' data-target='#CancelModal' class='btn btn-danger btn-sm revisar mt-1' id='getActualizaId' style='color:#ffffff;'><i class='fas fa-trash fa-xs mr-1 ml-1'></i> Cancel</button><input type='hidden' class='FULL_NAME' id='FULL_NAME' value='"+row.first_name+" "+row.middle_name+" "+row.last_name+""+row.ext_name+"'><input type='hidden' class='REFERENCE_NO' id='REFERENCE_NO' value='"+row.reference_no+"'><input type='hidden' class='AMOUNT' value='"+row.amount+"' id='AMOUNT'><input type='hidden' class='FARM_AREA' value='"+row.farm_area+"'  id='FARM_AREA'>";

                            }else if( status == "CANCELLED" ){

                                submitted_tag += "<button type='button' data-batchc='"+row.batch_code+"' data-toggle='modal' data-target='#RestoreModal' class='btn btn-secondary btn-sm revisar mt-1' id='getActualizaId' style='color:#ffffff;'><i class='far fa-address-card fa-xs mr-1 ml-1'></i> Restore</button><input type='hidden' class='FULL_NAME' id='FULL_NAME' value='"+row.first_name+" "+row.middle_name+" "+row.last_name+""+row.ext_name+"'><input type='hidden' class='REFERENCE_NO' id='REFERENCE_NO' value='"+row.reference_no+"'><input type='hidden' class='AMOUNT' value='"+row.amount+"' id='AMOUNT'><input type='hidden' class='FARM_AREA' value='"+row.farm_area+"'  id='FARM_AREA'>";

                            }else if( status == "FULLY CLAIMED" || status == "PARTIALLY CLAIMED" ){

                                submitted_tag += "<button type='button' data-batchc='"+row.batch_code+"' data-toggle='modal' data-target='#ViewApprovedHistoryDetailsModal' class='btn bg-gradient-blue btn-sm revisar mt-1' id='getActualizaId' style='color:#ffffff;'><i class='fas fa-first-aid fa-xs mr-1 ml-1'></i> View Details</button><input type='hidden' class='batch_desc' value='"+row.batch_desc+"'><input type='hidden' class='CLAIMED_VOUCH' id='CLAIMED_VOUCH' value='"+row.CLAIMED_VOUCH+"'><input type='hidden' class='UNCLAIMED_VOUCH' value='"+row.UNCLAIMED_VOUCH+"' id='UNCLAIMED_VOUCH'><input type='hidden' class='uploaded_date' value='"+row.uploaded_date+"'  id='uploaded_date'>";

                            }



                            return submitted_tag;
                    
                        }
                },
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
        <div class="widget widget-stats bg-gradient-green" style="height:85px;">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-check fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Claimed Vouchers</div>
                <div class="stats-number">{{$claimed_ttl}}</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-black" style="height:85px;">
            <div class="stats-icon stats-icon-lg"><i class="far fa-window-close fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">Unclaimed Vouchers</div>
                <div class="stats-number">{{$unclaimed_ttl}}</div>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
            &nbsp;
    </div>

    <div class="col-lg-3 col-md-6">
        <a href='voucherprintbatch/{{$batch_no}}' class="edit btn bg-gradient-green btn-md float-right mb-2" style="text-decoration: none; color:#FFFFFF;"> <i class="fas fa-print pull-left m-r-10 text-black"></i>Print All Vouchers</a>
        <button class="edit btn btn-secondary btn-md float-right" data-toggle='modal' data-target='#BatchCancelModal'> <i class="fas fa-trash pull-left m-r-10 text-black"></i>Cancel Unclaimed Vouchers</button>
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
                            <th style="color:#2E353C;">RSBSA_NO</th>
                            <th style="color:#2E353C;">FIRST NAME</th>
                            <th style="color:#2E353C;">MIDDLE NAME</th>
                            <th style="color:#2E353C;">LAST NAME</th>
                            <th style="color:#2E353C;">EXT NAME</th>
                            <th style="color:#2E353C;">FARM AREA</th>
                            <th style="color:#2E353C;">AMOUNT</th>
                            <th style="color:#2E353C;">REFERENCE NO</th>
                            <th></th>
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


<div class="modal fade bd-example-modal-md" id="CancelModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
            <div class="modal-content" style="background-color: #FFFFFF">
                <div class="modal-header" style="background-color: #2E353C">
                    <h5 class="modal-title" style="color: white"><i class="fa fa-info-circle mr-1"></i> Confirm Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                <form method="post" enctype="multipart/form-data" action="{{route('vouchergen-delete')}}">
                @csrf
                    <!-- begin right-content -->
                   <div class="row">
                        <div class="col-lg-2">
                            <span style="font-size: 60px;"><i class="fas fa-trash fa-xs mr-1 ml-4 mt-4"></i></span>
                        </div>
                        <div class="col-lg-10 pt-3 pb-3 pl-4" style="font-size;50px;">
                            <input type="hidden" name="ref_no" class="ref_no" id="ref_no">
                            <input type="hidden" name="batch_no" class="batch_no" id="batch_no">
                            Reference No: <b><span class="REFERENCE_NO"></span></b><br>
                            Full Name: <b><span class="FULL_NAME"></span></b><br>
                            Farm Area: <b><span class="FARM_AREA" style="color:#8E0F0F"></span></b><br>
                            Voucher Amount: <b><span class="AMOUNT" style="color:#17492E"></span></b><br>
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


<div class="modal fade bd-example-modal-md" id="RestoreModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
            <div class="modal-content" style="background-color: #FFFFFF">
                <div class="modal-header" style="background-color: #2E353C">
                    <h5 class="modal-title" style="color: white"><i class="fa fa-info-circle mr-1"></i> Confirm Restoration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                <form method="post" enctype="multipart/form-data" action="{{route('vouchergen-restore')}}">
                @csrf
                    <!-- begin right-content -->
                   <div class="row">
                        <div class="col-lg-2">
                            <span style="font-size: 60px;"><i class="far fa-address-card fa-xs mr-1 ml-4 mt-4"></i></span>
                        </div>
                        <div class="col-lg-10 pt-3 pb-3 pl-4" style="font-size;50px;">
                            <input type="hidden" name="ref_no" class="ref_no_r" id="ref_no_r">
                            <input type="hidden" name="batch_no" class="batch_no_r" id="batch_no_r">
                            Reference No: <b><span class="REFERENCE_NO"></span></b><br>
                            Full Name: <b><span class="FULL_NAME"></span></b><br>
                            Farm Area: <b><span class="FARM_AREA" style="color:#8E0F0F"></span></b><br>
                            Voucher Amount: <b><span class="AMOUNT" style="color:#17492E"></span></b><br>
                        </div>
                    </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <button type='submit' class="btn btn-secondary" ><i class="fa fa-check-circle"></i> Confirm Restoration</button>
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>

                </form>
            </div>
        </form>
    </div>
</div>



<div class="modal fade bd-example-modal-md" id="BatchCancelModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
            <div class="modal-content" style="background-color: #FFFFFF">
                <div class="modal-header" style="background-color: #2E353C">
                    <h5 class="modal-title" style="color: white"><i class="fa fa-info-circle mr-1"></i> Confirm Batch Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                <form method="post" enctype="multipart/form-data" action="{{route('delete-batch')}}">
                @csrf
                    <!-- begin right-content -->
                   <div class="row">
                        <div class="col-lg-2">
                            <span style="font-size: 60px;"><i class="fas fa-trash fa-xs mr-1 ml-4 mt-4"></i></span>
                        </div>
                        <div class="col-lg-10 pt-3 pb-3 pl-4" style="font-size;50px;">
                            <input type="hidden" name="batch_code" class="batch_code" id="batch_code">
                            <h5>Are you sure you want to Cancel this Batch?</h5>
                            Total Vouchers to be Cancelled (Unclaimed): <b><span class="unclaimed_vouch" style="color:#8E0F0F">{{$unclaimed_ttl}}</span></b><br>
                            Total Vouchers to Keep (Claimed): <b><span class="claimed_vouch" style="color:#17492E">{{$claimed_ttl}}</span></b><br>
                        </div>
                    </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href='voucherdeletebatch/{{$batch_no}}' class="btn btn-danger" ><i class="fa fa-check-circle"></i> Confirm Cancellation</a>
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>

                </form>
            </div>
        </form>
    </div>
</div>

<!-- end panel -->
@endsection


