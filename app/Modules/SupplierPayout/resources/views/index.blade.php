@extends('global.base')
@section('title', "Supplier Payout")

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
        
        supplierpayoutlist();

        // DATATABE CONTENT
        function supplierpayoutlist(){
            $('#supplierpayout-datatable').unbind('click');
            var table = $('#supplierpayout-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.SupplierPayoutList') }}",
                dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                columns: [
                    {data: 'program', name: 'program', title: 'PROGRAM'},
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACITON DATE'},
                    {data: "application_number",
                        render: function(data) {
                            data = '<a href="javascript:void(0);" data-statuspayoutappnum="' + data + '" class="statuspayoutlink"><i class="fas fa-spinner fa-spin '+ data +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                            return data;
                        }, title: 'APPLICATION NO.'},
                    {data: 'description', name: 'description', title: 'DESCRIPTION'},
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}
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
                            .pluck('amount')
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;
                            }, 0 );
                            return '<span>Page Total: '+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim )+'</span>';
                        },                    
                    },
                footerCallback: function (row, data, start, end, display) {
                    var TotalCreatedAmount = 0;                    
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['grandtotalamount'];
                        TotalCreatedAmount = parseInt(dataval);
                    }
                    $('.batchpayoutsum').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalCreatedAmount ));

                    var TotalPendingPayoutAmount = 0;                    
                    for (var i = 0; i < data.length; i++) {
                        var amount = data[i]['totalpending'];
                        TotalPendingPayoutAmount = parseInt(amount);                    
                    }                    
                    $('.BatchPayout_PendingSum').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalPendingPayoutAmount ));
                }
            }).ajax.reload(); 
        }

        $(document).on('click','.SupplierPayout_btn_Create',function(){
            SpinnerShow('SupplierPayout_btn_Create','btnloadingIcon');
            $.ajax({
                type:'get',
                url:"{{ route('get.BatchPayoutDropList') }}",
                success:function(data){                    
                    $('.option_batch_id').remove();
                    for(var i=0;i<data.length;i++){                   
                            $('.selectbatchpayout').append($('<option>', {class:'option_batch_id',value:data[i].batch_id, text:data[i].application_number}));
                        }                    
                    $('.selectedvoucher, .selectedvoucherall').prop("checked", false);
                    $('.SupplierPayout_totalselectedamt').html('0.00');
                    $('#selected_voucheramt').val("0.00");
                    $('.errormsg').css('display','none');
                    $('#selected_batchid').val('');
                    $('#CreatePayoutModal').modal('toggle');                    
                    datatablecheckbox("");    
                    SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');                
            },
            error: function (textStatus, errorThrown) {
                    console.log('Err');
                    SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');
                }
            });  
            SpinnerHide('SupplierPayout_btn_Create','btnloadingIcon');          
        });

        function datatablecheckbox(batch_id){ 
            $('#ClaimedVoucher-datatable').unbind('click');             
            var table = $('#ClaimedVoucher-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                scrollY: "300px",
                // responsive: true,
                paging: false,
                ordering: false,
                ajax: "{{ route('get.SupplierPayout_ClaimedVoucherDetails') }}" + '?batch_id=' + batch_id,
                columns: [
                    {data: 'checkbox', name: 'checkbox'},                        
                    {data: 'transac_date', name: 'transac_date'},
                    {data: 'reference_no', name: 'reference_no'},
                    {data: 'item_name', name: 'item_name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                ]                
            }).ajax.reload(); 
                        
            // RETURN INTO ZERO WHEN USE SEARCH BOX
            $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                $('.SupplierPayout_totalselectedamt').html("0.00"); 
                $('#selected_voucheramt').val("0.00");
            });
        }

        // Handle click on checkbox
        $('#ClaimedVoucher-Datatable').on('click', 'td:first-child', function(e){
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });                  

        // Handle click on checkbox
        $('#ClaimedVoucher-Datatable').on('click', 'input[type="checkbox"]', function(e){
            var total_amount = parseInt($(this).data('selectedvoucheramt'));  
            var selected_voucheramt = parseInt($('#selected_voucheramt').val());  
            var $row = $(this).closest('tr');
            if(this.checked){
                var total = selected_voucheramt + total_amount;
                $('#selected_voucheramt').val(total);
                var totalamt = addCommas(total);
                $('.SupplierPayout_totalselectedamt').html(totalamt); 
                $row.addClass('selected');
            }else{                    
                var total = selected_voucheramt - total_amount;
                $('#selected_voucheramt').val(total);
                var totalamt = addCommas(total);                
                $('.SupplierPayout_totalselectedamt').html(totalamt);  
                $row.removeClass('selected');
            }                    
            e.stopPropagation();
        });

        // Handle click on "Select all" control
        $('.selectedvoucherall').on('click', function(e){
            if(this.checked){
                $('#ClaimedVoucher-Datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#ClaimedVoucher-Datatable tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        }); 

        $(document).on('click','.btnCreate',function(){
            SpinnerShow('btnCreate','btnloadingIcon1');
            Swal.fire({
            title: 'Are you sure',
            text: "You want to Create Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Create',
            allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    selectedvoucherid = $('.selectedvoucher:checked').map(function(){
                    return $(this).data('selectedvoucherid');}).get().join(',');

                    selectedvoucheramt = $('.selectedvoucher:checked').map(function(){
                    return $(this).data('selectedvoucheramt');}).get().join(',');
                    
                    batch_id = $('.selectbatchpayout').val();

                    if (batch_id == '' || selectedvoucherid == '') {
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please Select value in both field!");
                            SpinnerHide('btnCreate','btnloadingIcon1');
                        }else{                            
                            var _token = $("input[name=token]").val();
                            $.ajax({
                                type:'post',
                                url:"{{ route('save.SupplierPayout') }}",
                                data:{selectedvoucherid:selectedvoucherid,selectedvoucheramt:selectedvoucheramt,batch_id:batch_id,_token:_token},
                                success:function(data){                         
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        title:'Created!',
                                        text:'Your Supplier Payout successfully Created!',
                                        icon:'success'
                                    });
                                    supplierpayoutlist();
                                    document.getElementById("default_BatchPayout").value = "";
                                    $('.selectedvoucher, .selectedvoucherall').prop("checked", false);
                                    $('.errormsg').css('display','none');
                                    $('.totalselected ').html('0.00');
                                    $('#CreatePayoutModal').modal('hide');
                                    $('.SupplierPayout_totalselectedamt').html("0.00"); 
                                    $('#selected_voucheramt').val("0.00");
                                    SpinnerHide('btnCreate','btnloadingIcon1');
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('btnCreate','btnloadingIcon1');
                                }
                            });  
                        }
                    
                    }
                    else{
                        SpinnerHide('btnCreate','btnloadingIcon1');
                    }
                }); 
        });

        $(document).on('click','.btnRemoveSupplierPayout',function(){
            var _token = $("input[name=token]").val(),
                batch_id = $(this).data('removesupplierbatchid');
            SpinnerShow('btnRemoveSupplierPayout',batch_id);
            Swal.fire({
            title: 'Are you sure',
            text: "You want to Remove the Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Remove',
            allowOutsideClick: false
            }).then((result) => {
            if (result.isConfirmed) {                
                    $.ajax({
                            type:'post',
                            url:"{{ route('remove.SupplierPayout') }}",
                            data:{batch_id:batch_id,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Your Supplier Payout successfully Removed!',
                                    icon:'success'
                                });
                                supplierpayoutlist();                                   
                                document.getElementById("default_BatchPayout").value = "";
                                SpinnerHide('btnRemoveSupplierPayout',batch_id);
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                supplierpayoutlist();
                                SpinnerHide('btnRemoveSupplierPayout',batch_id);
                            }
                        });
                    }else{
                        SpinnerHide('btnRemoveSupplierPayout',batch_id);
                    }
                });
        });
            

        $(document).on('click','.statuspayoutlink',function(){            
        var app_num = $(this).data('statuspayoutappnum'),
            _token = $("input[name=token]").val();
            SpinnerShow('statuspayoutlink',app_num);
                $.ajax({
                    url:"{{ route('get.SupplierPayout_Status') }}",
                    data:{app_num:app_num,_token:_token},
                    success:function(data){
                        var status_val = jQuery.parseJSON(data);
                        $('.status_application_number').html(status_val[0].application_number); 
                        $('.status_transac_date').html(status_val[0].transac_date);   
                        $('.status_description').html(status_val[0].description);   
                        $('.status_amount').html(' ₱ '+addCommas(status_val[0].amount));
                        $('.createdpayout, .submittedpayout, .approvalprocess, .payoutcomplete').removeClass("active");
                        if(status_val[0].issubmitted == 1){                           
                            $('.createdpayout, .submittedpayout').addClass("active");
                        }else{
                            $('.createdpayout').addClass("active");
                        }
                        
                        if(status_val[0].payout_endorse_approve == 1){                           
                            $('.createdpayout, .submittedpayout, .approvalprocess').addClass("active");
                        }else{
                            $('.createdpayout').addClass("active");
                        }
                        if(status_val[0].dbp_batch_id != null){                           
                            $('.createdpayout, .submittedpayout, .approvalprocess, .payoutcomplete').addClass("active");
                        }else{
                            $('.createdpayout').addClass("active");
                        }
                        
                            $('#CheckStatusModal').modal('toggle');
                            SpinnerHide('statuspayoutlink',app_num);
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('statuspayoutlink',app_num);
                        }
                });
        });                

        $(document).on('click','.btnViewSupplierPayoutDetails',function(){
            var  batch_id = $(this).data('submitsupplierbatchid'); 
            SpinnerShow('btnViewSupplierPayoutDetails',batch_id);
            $('#viewbatchpayoutdetails-Datatable').unbind('click');
            var TotalCreatedAmount = 0;
            var table = $('#viewbatchpayoutdetails-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.SupplierBatchPayoutDetails') }}" + '?batch_id=' + batch_id,
                columns: [                       
                    {data: 'transac_date', name: 'transac_date', title:'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title:'REFERENCE NO.'},
                    {data: 'item_name', name: 'item_name', title:'ITEM NAME'},
                    {data: 'quantity', name: 'quantity', title:'QUANTITY'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'AMOUNT'},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['grandtotalamount'];
                        TotalCreatedAmount = parseInt(dataval);
                    }
                    $('.batchpayouttotalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalCreatedAmount));
                }                          
                
            }).ajax.reload();
            $('#ViewDetailsModal').modal('toggle');
            SpinnerHide('btnViewSupplierPayoutDetails',batch_id);
        });

        $(document).on('click','.btnSubmitSupplierPayout',function(){
            var _token = $("input[name=token]").val(),
                batch_id = $(this).data('submitsupplierbatchid');
            SpinnerShow('btnSubmitSupplierPayout',batch_id);
            Swal.fire({
            title: 'Are you sure',
            text: "You want to Submit the Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Submit',
            allowOutsideClick: false
            }).then((result) => {
            if (result.isConfirmed) {                
                    $.ajax({
                        type:'post',
                        url:"{{ route('submit.SupplierPayout') }}",
                        data:{batch_id:batch_id,_token:_token},
                        success:function(data){                         
                            Swal.fire({
                                allowOutsideClick: false,
                                title:'Submitted!',
                                text:'Your Supplier Payout successfully Submitted!',
                                icon:'success'
                            });
                            $('#supplierpayout-datatable').DataTable().ajax.reload();
                            document.getElementById("default_BatchPayout").value = "";
                            SpinnerHide('btnSubmitSupplierPayout',batch_id);
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('btnSubmitSupplierPayout',batch_id);
                        }
                    });
                }else{
                    SpinnerHide('btnSubmitSupplierPayout',batch_id);
                }
            });
        });

        $(document).on('click','.btnViewSupplierPayoutVoucherAttachments',function(){
            SpinnerShow('btnViewSupplierPayoutVoucherAttachments',voucher_id);
            var _token = $("input[name=token]").val(),
                voucher_id = $(this).data('selectvoucherid');
            $.ajax({
                type:'get',
                url:"{{ route('get.SupplierPayoutAttachmentsImg') }}",
                data:{voucher_id:voucher_id,_token:_token},
                success:function(data){
                    $('.holdtransattachmentsimgcontent').html(data);
                    $('#ViewHoldTransAttachmentsModal').modal('toggle');
                    SpinnerHide('btnViewSupplierPayoutVoucherAttachments',voucher_id);
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                        SpinnerHide('btnViewSupplierPayoutVoucherAttachments',voucher_id);
                    }
            });           
        });

        $(document).on('change','.selectbatchpayout',function(){
            var  batch_id = $(this).val();
            $('.SupplierPayout_totalselectedamt').html("0.00"); 
            $('#selected_voucheramt').val("0.00");
            datatablecheckbox(batch_id);
        });

        $(document).on('click','.btnViewHoldtransDetails',function(){            
            var TotalBatchPayoutAmount = 0,
                batch_id = $(this).data('holtransactionbatchid');
                $('#viewHoldTransactionDetails-Datatable').unbind('click'); 
                SpinnerShow('btnViewHoldtransDetails',batch_id);
            var table = $('#viewHoldTransactionDetails-Datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.SupplierPayout_HoldTransDetails') }}" + '?batch_id=' + batch_id,
                columns: [                       
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                    {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                    {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                    {data: 'amount', name: 'amount', title: 'AMOUNT'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}, 
                ],
                footerCallback: function (row, data, start, end, display) {                          
                        var remarks = "";                                               
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                            remarks = data[i]['remarks'];
                        dataval = dataval.replace(',','');
                        TotalBatchPayoutAmount += parseInt(dataval);
                    }
                    TotalBatchPayoutAmount = addCommas(TotalBatchPayoutAmount);
                    $('.batchpayout_holdtrans_totalamt').html(TotalBatchPayoutAmount);
                    $('.HoldTransactionMsg').html(remarks);
                }                
            }).ajax.reload(); 
            $('#ViewHoldTransDetailsModal').modal('toggle');
            SpinnerHide('btnViewHoldtransDetails',batch_id);
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
<input type="hidden" id="totalvoucheramt" value="0">
<input type="hidden" id="selected_voucheramt" value="0">
<input type="hidden" id="selected_batchid" value="0">
<input type="hidden" id="selectedProgramId" value="">

<!-- PROGRAM SELECTION -->
<div class="row">

    <!-- PROGRAM DROPDOWN SELECTION -->
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Supplier Payout</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Supplier Payout</li>
        </ol>   
    </div>
</div>

<div class="row">                              
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL CREATED PAYOUT</div>
                <div class="stats-number batchpayoutsum">0.00</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL PENDING PAYOUT</div>
                <div class="stats-number BatchPayout_PendingSum">0.00</div>
            </div>
        </div>
    </div>
</div>       

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-success SupplierPayout_btn_Create"><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i><i class="fa fa-plus"></i> Create Payout</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Payout Details:</h4>
    </div>
    <div class="panel-body">
        <table id="supplierpayout-datatable" class="display select table table-striped display nowrap" style="width: 100%;">            
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CreatePayoutModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Create Payout</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    @CSRF                                    
                                    <div class="row m-b-15">
                                        <div class="col-md-12 table-wrapper-scroll-y my-custom-scrollbar">                                            
                                        <label class="control-label">Batch Application Number: <span class="text-danger">*</span></label>
                                        <div class="row m-b-15">
                                            <div class="col-md-12">
                                                <select id="default_BatchPayout" class="form-control selectbatchpayout" name="BatchPayout" data-size="10" data-style="btn-white" value="{{ old('BatchPayout') }}">
                                                <option value="" selected>Select Batch Application Number</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="ClaimedVoucher-Datatable" class="table table-striped display nowrap" style="width: 100%;">                                                       
                                                    <thead style="background-color: #008a8a">
                                                        <tr>
                                                            <th scope="col" style="color: white">
                                                                <div class="checkbox checkbox-css">
                                                                    <input type="checkbox" id="cssCheckbox1" class="selectedvoucherall" name="select_all" value="1">
                                                                    <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                                                                </div>
                                                            </th>
                                                            <th scope="col" style="color: white">TRANSACTION DATE</th>
                                                            <th scope="col" style="color: white">REFERENCE NO.</th>
                                                            <th scope="col" style="color: white">COMMODITY</th>
                                                            <th scope="col" style="color: white">QUANTITY</th>
                                                            <th scope="col" style="color: white">AMOUNT</th>
                                                            <th scope="col" style="color: white">TOTAL AMOUNT</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <label class="pull-right">
                                            <div class="input-group mb-3 ">
                                                <div class="input-group-prepend">
                                                <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                                </div>
                                                <h3 class="alert alert-primary SupplierPayout_totalselectedamt" role="alert">0.00</h3>
                                            </div>
                                        </label>
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
                    <a href="javascript:;" class="btn btn-success btnCreate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Create</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="CheckStatusModal" data-keyboard="false" data-backdrop="static">  
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Check Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <div class="container">
                    <article class="card">
                        <div class="card-body">
                            <article class="">
                                <div class="row">
                                    <div class="col"> <strong>TRANSACTION DATE:</strong> <br> <span class="status_transac_date"></span> </div>
                                    <div class="col"> <strong>APPLICATION NO.:</strong> <br> <span class="status_application_number"></span> </div>                                    
                                    <div class="col"> <strong>DESCRIPTION:</strong> <br> <span class="status_description"></span> </div>
                                    <div class="col"> <strong>AMOUNT:</strong> <br> <span class="status_amount"></span> </div>
                                </div>
                            </article>
                            <div class="track">
                                <div class="step createdpayout"> <span class="icon"> <i class="fa fa-users"></i> </span> <span class="text">Created Payout</span> </div>
                                <div class="step submittedpayout"> <span class="icon"> <i class="fa fa-file-alt"></i> </span> <span class="text"> Submitted Payout</span> </div>
                                <div class="step approvalprocess"> <span class="icon"> <i class="fa fa-cogs"></i> </span> <span class="text"> Approval Process</span> </div>
                                <div class="step payoutcomplete"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Complete</span> </div>
                            </div>
                            <hr>
                        </div>
                    </article>
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


<div class="modal fade bd-example-modal-lg" id="ViewDetailsModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Batch Payout Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="viewbatchpayoutdetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary batchpayouttotalamt" role="alert"></h3>
                                    </div>
                                </label>   
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ViewHoldTransDetailsModal" data-keyboard="false" data-backdrop="static">
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
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <b><h5><i class='fa fa-exclamation-triangle'></i> Message: &nbsp;&nbsp;</h5></b>
                        <div>
                            <span class="HoldTransactionMsg"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="viewHoldTransactionDetails-Datatable" class="table table-striped display nowrap" style="width: 100%">                                                       
                                <thead style="background-color: #008a8a"></thead>
                            </table>
                            <label class="pull-right">
                                <div class="input-group mb-3 ">
                                    <div class="input-group-prepend">
                                    <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                    </div>
                                    <h3 class="alert alert-primary batchpayout_holdtrans_totalamt" role="alert"></h3>
                                </div>
                            </label>                                
                        </div>
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

<div class="modal fade" id="ViewHoldTransAttachmentsModal" data-keyboard="false" data-backdrop="static">
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
                        <div class="carousel-inner holdtransattachmentsimgcontent"></div>
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