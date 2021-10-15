@extends('global.base')
@section('title', "Voucher Transaction Monitoring")

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
    $(document).ready(function() {

        // CALL DATATABLE
        getTotalVoucherTrans();

        // DISPLAY DATATABLE
        function getTotalVoucherTrans(){
            $('#VoucherTrans-datatable').unbind('click');
            var table = $('#VoucherTrans-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.VoucherTransList') }}",
                dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                columns: [
                    {data: 'program', name: 'program', title:'PROGRAM'},
                    {data: 'transac_date', name: 'transac_date', title:'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title:'REFERENCE NO.'},
                    {data: 'last_name', name: 'last_name', title:'LAST NAME'},
                    {data: 'first_name', name: 'first_name', title:'FIRST NAME'},
                    {data: 'middle_name', name: 'middle_name', title:'MIDDLE NAME'},
                    {data: 'quantity', name: 'quantity', title:'QUANTITY'},
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                    {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},    
                    {data: 'action',name: 'action', orderable: true,searchable: true, title:'ACTION'},                    
                ],
                columnDefs: [
                            { "visible": false, "targets": 0,}
                        ],
                order: [[0, 'asc']],
                rowGroup: {
                    dataSrc: function (data) {
                            return '<span>'+data.program+'</span>';
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
                    },
                footerCallback: function (row, data, start, end, display) { 
                    var TotalAmount = 0;                                        
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['grandtotalamount'];
                            TotalAmount = parseInt(dataval);
                        }
                        $('.totalclaimedvoucheramt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                }
            }).ajax.reload();                         
        }

        // CALL VIEW ATTACHMENT ACTION UPON CLICK LINK PER ROWS OF THE DATATABLE
        $(document).on('click','.btnViewVoucherAttachments',function(){
                var _token = $("input[name=token]").val(),
                    voucher_id = $(this).data('selectvoucherid');
                $.ajax({
                    type:'get',
                    url:"{{ route('get.VoucherListAttachments') }}",
                    data:{voucher_id:voucher_id,_token:_token},
                    success:function(data){
                        $('.voucherattachmentsimg').html(data);
                        $('#ViewAttachmentsModal').modal('toggle');
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                        }
                });          
        });            
        
        $(document).on('click','.linkVoucherPendingPayout',function(){
            SpinnerShow('linkVoucherPendingPayout','btnloadingIcon1');
            window.location.href = "{{ route('Voucherspendingpayout.index') }}";
            SpinnerHide('linkVoucherPendingPayout','btnloadingIcon1');
        });

        $(document).on('click','.linkVoucherApprovedPayout',function(){
            SpinnerShow('linkVoucherApprovedPayout','btnloadingIcon2');
            window.location.href = "{{ route('Vouchersapprovedpayout.index') }}";
            SpinnerHide('linkVoucherApprovedPayout','btnloadingIcon2');
        });

        $(document).on('click','.linkVoucherHoldTransaction',function(){
            SpinnerShow('linkVoucherHoldTransaction','btnloadingIcon3');
            window.location.href = "{{ route('Vouchersholdtransaction.index') }}";
            SpinnerHide('linkVoucherHoldTransaction','btnloadingIcon3');
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

<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Voucher Transaction Monitoring</h1>                                  
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Voucher Transaction Monitoring</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-line fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL RECEIVED VOUCHERS</div>
                <div class="stats-number totalclaimedvoucheramt">₱ 0.00</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-pie fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">VOUCHERS PENDING PAYOUT</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkVoucherPendingPayout" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> View Details <i class="fa fa-angle-double-right"></i></a>                
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-bar fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">VOUCHERS APPROVED PAYOUT</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkVoucherApprovedPayout" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> View Details <i class="fa fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-black">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-area fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title"><h5 style="color:#f8f9fa;">VOUCHERS HOLD TRANSACTION</h5></div>
                <div class="stats-progress progress"></div>
                <a href="javascript:;" class="pull-right linkVoucherHoldTransaction" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> View Details <i class="fa fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Voucher Details:</h4>
    </div>
    <div class="panel-body">
        <table id="VoucherTrans-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>
<!-- end panel -->

<div class="modal fade" id="ViewAttachmentsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white;"><i class="fa fa-file-image"></i> Attachments</h4>
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

@endsection