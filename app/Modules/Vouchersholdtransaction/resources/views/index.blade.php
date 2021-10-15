@extends('global.base')
@section('title', "Vouchers Hold Transaction")

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
            getHoldTransacionDetails();

            // DISPLAY DATATABLE
            function getHoldTransacionDetails(){
                    var table = $('#VoucherHoldTransaction-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.VoucherHoldTransactionList') }}",
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
                                $('.totalvoucherholdtransamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                        }
                    }).ajax.reload();

                    // DRAW DATATABLE BASED ON THE DEFAULT PROGRAM
                    var SessionId = $('#selectedProgramDesc').val();
                    table.column(0).search(SessionId).draw();          

            } 

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
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Vouchers Hold Transaction</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li> 
            <li class="breadcrumb-item"><a href="{{route('VoucherTrans.index')}}">Voucher Transaction Monitoring</a></li>
            <li class="breadcrumb-item active">Vouchers Hold Transaction</li>
        </ol>   
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="note note-success">
            <div class="note-icon"><i class="fas fa-chart-area"></i></div>
            <div class="note-content">
                <h4><b>Total Amount</b></h4>
                <h3><span class="totalvoucherholdtransamt">₱ 0.00</span></h3>
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
    <table id="VoucherHoldTransaction-datatable" class="table table-striped display nowrap" style="width: 100%;">
        <thead style="background-color: #008a8a;"></thead>
    </table>
    </div>
</div>
<!-- end panel -->
@endsection