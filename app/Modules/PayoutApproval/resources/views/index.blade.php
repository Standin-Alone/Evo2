@extends('global.base')
@section('title', "Payout Approval")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css" rel="stylesheet">
    <link href="assets/pgv/backend-style.css" rel="stylesheet">
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
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/pgv/backend-script.js"></script>

    <script type="text/javascript">
       $(document).ready(function (){
            PayoutApprovalList();

           function PayoutApprovalList(){
                $('#PayoutApprovalList-datatable').unbind('click');
                var table = $('#PayoutApprovalList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    paging: false,
                    ordering:false,
                    ajax: "{{ route('get.PayoutApprovalList') }}",
                    columns: [
                        {data: 'program', name: 'program'}, 
                        {data: 'checkbox', name: 'checkbox'},  
                        {data: 'transac_date', name: 'transac_date'},
                        {data: 'application_number', name: 'application_number'},
                        {data: 'description', name: 'description'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},    
                        {data: 'action', name: 'action', orderable: false, searchable: false}, 
                    ],
                    columnDefs: [
                        { "visible": false, "targets": 0,}
                    ]
                }).ajax.reload();
           }

           $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                $('.PayoutApproval_totalselectedamt').html("0.00"); 
                $('#PayoutAppval_selectedamt').val("0.00");
            });

            // Handle click on checkbox
            $('#PayoutApprovalList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });
            
            $('#PayoutApprovalList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var total_amount = $(this).data('selectedbatchamt');
                var $row = $(this).closest('tr');
                if(this.checked){
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#PayoutAppval_selectedamt').val()); 
                    var total = selectedamt + total_amount;
                    $('#PayoutAppval_selectedamt').val(total);
                    var totalamt = '₱ '+addCommas(total);
                    $('.PayoutApproval_totalselectedamt').html(totalamt);
                    $row.addClass("selected");
                }else{                 
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#PayoutAppval_selectedamt').val()); 
                    var total = selectedamt - total_amount;
                    $('#PayoutAppval_selectedamt').val(total);
                    var totalamt = '₱ '+addCommas(total);
                    $('.PayoutApproval_totalselectedamt').html(totalamt);    
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });

            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#PayoutApprovalList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#PayoutApprovalList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            function PayoutApprovalDetails(batch_id){ 
                $('#viewPayoutApprovalDetails-datatable').unbind('click');
                var table = $('#viewPayoutApprovalDetails-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.PayoutApprovalDetails') }}" + '?batch_id=' + batch_id,
                    columns: [                     
                        {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                        {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO'},
                        {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                        {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'option', name: 'option', orderable: false, searchable: false, title: 'OPTION'}, 
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                    ],
                    rowGroup: {
                        dataSrc: function (data) {
                                return "<span>{{ session('Default_Program_Desc') }}</span>";
                            },
                        starRender:null,
                        endRender: function(rows){
                                var total_amount_claim = rows
                                .data()
                                .pluck('total_amount')
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;
                                }, 0 );
                                return '<span>Page Total: '+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim )+'</span>';
                            },                    
                        }            
                }).ajax.reload();
                $('#ViewDetailsModal').modal('toggle');
                
            }
            
            $(document).on('click','.btnVoucherHold',function(){
                var batch_id = $(this).data('selectbatchid'),
                    voucher_id = $(this).data('selectvoucherid');
                SpinnerShow('btnVoucherHold',voucher_id);
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Hold this Voucher transaction?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hold',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $('#PayoutAppval_selectedbatchid').val(batch_id);
                        $('#PayoutAppval_selectedvoucherid').val(voucher_id);
                        $('.PayoutAppval_holdremarks').val('');
                        $('#AddRemarksModal').modal('toggle'); 
                        SpinnerHide('btnVoucherHold',voucher_id);             
                    }else{
                        SpinnerHide('btnVoucherHold',voucher_id);
                    }
                });
            });

            $(document).on('click','.PayoutApproval_submitholdremarks',function(){
                SpinnerShow('PayoutApproval_submitholdremarks','btnloadingIcon1');
                var remarks = $('.PayoutAppval_holdremarks').val(),
                _token = $("input[name=token]").val(),
                voucher_id = $('#PayoutAppval_selectedvoucherid').val(),
                batch_id = $('#PayoutAppval_selectedbatchid').val();
                if (remarks != "") {        
                        $.ajax({
                            type:'post',
                            url:"{{ route('hold.SelectedVoucher') }}",
                            data:{batch_id:batch_id,voucher_id:voucher_id,remarks:remarks,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Hold!',
                                    text:'Your Selected Voucher successfully Hold!',
                                    icon:'success'
                                });
                                $('#AddRemarksModal').modal('hide'); 
                                $('.errormsg_holdremarks').css('display','none');                       
                                PayoutApprovalDetails(batch_id);
                                SpinnerHide('PayoutApproval_submitholdremarks','btnloadingIcon1');
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('PayoutApproval_submitholdremarks','btnloadingIcon1');
                            }
                        });
                }else{                
                    $('.errormsg_holdremarks').css('display','block');
                    $(".errormsg_holdremarks").html("Please enter value in required field!");
                    SpinnerHide('PayoutApproval_submitholdremarks','btnloadingIcon1');
                }
            });

            $(document).on('click','.btnViewPayoutApprovalDetails',function(){                              
                var  batch_id = $(this).data('selectedbatchid');
                SpinnerShow('btnViewPayoutApprovalDetails',batch_id);  
                $('#PayoutAppval_selectedbatchid').val(batch_id);                
                PayoutApprovalDetails(batch_id);
                SpinnerHide('btnViewPayoutApprovalDetails',batch_id);
                
            });
            
            $(document).on('click','.btnApprovalBatchPayout',function(){
                SpinnerShow('btnApprovalBatchPayout','btnloadingIcon2');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Approve the selected Batch Payout?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Approve',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        batchid = $('.selectedbatch:checked').map(function(){
                        return $(this).data('selectedbatchid');}).get().join(',');
                        if(batchid != ""){
                            $('#PayoutAppval_selectedbatches').val(batchid);
                            $('.errormsg_approval').css('display','none');
                            $('.PayoutAppval_appdescription').val('');
                            $('#AddDescriptionModal').modal('toggle');
                            SpinnerHide('btnApprovalBatchPayout','btnloadingIcon2');
                        }else{
                            $('.errormsg_approval').css('display','block');
                            $(".errormsg_approval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select Batch for approval ! Please try again.");
                            SpinnerHide('btnApprovalBatchPayout','btnloadingIcon2');
                        }
                        
                    }else{
                        SpinnerHide('btnApprovalBatchPayout','btnloadingIcon2');
                    }
                });
            });

            $(document).on('click','.PayoutApproval_submitappdescription',function(){
                var _token = $("input[name=token]").val(),
                batchid = $('#PayoutAppval_selectedbatches').val(),
                description = $('.PayoutAppval_appdescription').val();
                SpinnerShow('PayoutApproval_submitappdescription','btnloadingIcon3');
                if(description != ""){
                    $.ajax({
                        type:'post',
                        url:"{{ route('approve1.SelectedBatch') }}",
                        data:{batchid:batchid,description:description,_token:_token},
                        success:function(data){ 
                            if(data == "Hold"){
                                $('.errormsg_approval').css('display','block');
                                $(".errormsg_approval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please settle the <b>Hold</b> voucher transaction to countinue the approval!");
                                $('#AddDescriptionModal').modal('hide');
                                $('.PayoutAppval_appdescription').val('');
                                SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                            }else{
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Approved!',
                                    text:'Your Batch Payout successfully Approved!',
                                    icon:'success'
                                });
                                $('.PayoutAppval_appdescription').val('');                                    
                                $('.errormsg_approval').css('display','none');
                                $('#AddDescriptionModal').modal('hide');
                                PayoutApprovalList();
                                SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                            }  
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                        }
                    });
                    $('.errormsg_appdescription').css('display','none');
                    SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                }else{
                    $('.errormsg_appdescription').css('display','block');
                    $(".errormsg_appdescription").html("Please enter value in required field!");
                    SpinnerHide('PayoutApproval_submitappdescription','btnloadingIcon3');
                }

            });

            $(document).on('click','.btnViewVoucherAttachments',function(){                
                var _token = $("input[name=token]").val(),
                    voucher_id = $(this).data('selectvoucherid');
                    SpinnerShow('btnViewVoucherAttachments',voucher_id);
                $.ajax({
                    type:'get',
                    url:"{{ route('get.VoucherAttachmentsImg') }}",
                    data:{voucher_id:voucher_id,_token:_token},
                    success:function(data){
                        $('.voucherattachmentsimg').html(data);
                        $('#ViewAttachmentsModal').modal('toggle');
                        SpinnerHide('btnViewVoucherAttachments',voucher_id);
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('btnViewVoucherAttachments',voucher_id);
                        }
                });  
                          
            });

           $(document).on('click','.linkApprovedPayoutHistory',function(){
                SpinnerShow('linkApprovedPayoutHistory','btnloadingIcon5');
                window.location.href = "{{ route('ApprovedPayoutHistory.index') }}";
                SpinnerHide('linkApprovedPayoutHistory','btnloadingIcon5');
            });

            $(document).on('click','.linkHoldVoucherHistory',function(){
                SpinnerShow('linkHoldVoucherHistory','btnloadingIcon6');
                window.location.href = "{{ route('HoldVoucherHistory.index') }}";
                SpinnerHide('linkHoldVoucherHistory','btnloadingIcon6');
            });

            $(document).on('keyup','.PayoutAppval_appdescription, .PayoutAppval_holdremarks',function(){
                var str = $(this).val();
                var res = str.toUpperCase();
                $(this).val(res);
            });           

        });
    </script>
@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="PayoutAppval_selectedbatches" value="0">
<input type="hidden" id="PayoutAppval_selectedbatchid" value="0">
<input type="hidden" id="PayoutAppval_selectedvoucherid" value="0">
<input type="hidden" id="PayoutAppval_selectedamt" value="0">
<input type="hidden" id="selectedProgramId" value="">
<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Payout Approval</h1>                                  
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Payout Approval</li>
        </ol>   
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-pie fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">APPROVED PAYOUT HISTORY</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkApprovedPayoutHistory" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon5 pull-left m-r-10" style="display: none;"></i> View Details</a>                
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-bar fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">HOLD VOUCHER HISTORY</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkHoldVoucherHistory" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon6 pull-left m-r-10" style="display: none;"></i> View Details</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-info btnApprovalBatchPayout"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Approve Payout</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title btn btn-xs" style="font-weight:normal !important;">Payout Details:</h4> 
    </div>    
    <div class="panel-body">
        <div class="alert alert-danger errormsg_approval" role="alert" style="display: none;"></div>
        <table id="PayoutApprovalList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a">
                <tr>
                    <th></th>
                    <th scope="col" style="color: white">
                        <div class="checkbox checkbox-css">
                            <input type="checkbox" id="cssCheckbox1" class="selectedbatchall" name="select_all" value="1">
                            <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                        </div>
                    </th>
                    <th scope="col" style="color: white">TRANSACTION DATE</th>
                    <th scope="col" style="color: white">APPLICATION NO.</th>
                    <th scope="col" style="color: white">DESCRIPTION</th>
                    <th scope="col" style="color: white">TOTAL AMOUNT</th>
                    <th scope="col" style="color: white">ACTION</th>                                
                </tr>
            </thead>
        </table>
        <label class="pull-right">
            <div class="input-group mb-3 ">
                <div class="input-group-prepend">
                <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                </div>
                <h3 class="alert alert-primary PayoutApproval_totalselectedamt" role="alert">0.00</h3>
            </div>
        </label>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="ApprovedHistoryList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Batch Payout Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff; ">
                    {{--modal body start--}}
                    <table id="viewPayoutApprovalDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewApprovedHistoryDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved Payout Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                            <table id="viewApprovalHistoryDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="HoldTransactionHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Hold Transaction Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                            <table id="viewHolTransactionDetails-datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary holdtransactiontotalamt" role="alert"></h3>
                                    </div>
                                </label>

                                
                                
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="ViewAttachmentsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-file-image"></i> Attachments</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff;">
                    {{--modal body start--}}

                    <div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
                        <div class="carousel-inner voucherattachmentsimg"></div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>                    
                    
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="AddRemarksModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Note/Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control PayoutAppval_holdremarks " rows="3" placeholder="Enter Description" required></textarea>
                                            <span class="text-danger errormsg_holdremarks"></span>
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success PayoutApproval_submitholdremarks"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="AddDescriptionModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Description</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <textarea class="form-control PayoutAppval_appdescription " rows="3" placeholder="Enter Description" required></textarea>
                                            <span class="text-danger errormsg_appdescription"></span>
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success PayoutApproval_submitappdescription"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection