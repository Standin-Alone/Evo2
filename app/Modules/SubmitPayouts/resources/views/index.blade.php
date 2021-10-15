@extends('global.base')
@section('title', "Submit Payouts")

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
            
            SubmitPayoutList();
            SubmitPayoutGeneratedList();

            function SubmitPayoutList(){
                var table = $('#SubmitPayoutsList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    paging: false,
                    ordering:false,
                    srcollY: "300px",
                    ajax: "{{ route('get.SubmitPayoutsList') }}",
                    columns: [
                        {data: 'checkbox', name: 'checkbox'},  
                        {data: 'transac_date', name: 'transac_date'},
                        {data: 'application_number', name: 'application_number'},
                        {data: 'description', name: 'description'},
                        {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                    ]
                }).ajax.reload();
                // RETURN INTO ZERO WHEN USE SEARCH BOX
                $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                    $('.SubmitPayouts_totalselectedamt').html("0.00"); 
                    $('#SubmitPayouts_selectedamt').val("0.00");
                });                
            }

            function SubmitPayoutGeneratedList(){
                var table = $('#SubmitPayoutGeneratedList-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,                
                ajax: "{{ route('get.SubmitPayoutGeneratedList') }}",
                columns: [ 
                    {data: 'created_at', name: 'created_at', title:'TRANSACTION DATE'},
                    {data: 'folder_file_name', name: 'folder_file_name', title: 'FILE NAME'},
                    {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title: 'TOTAL RECORDS'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                ]
                }).ajax.reload();
            }

            $(document).on('click','.btnViewVoucherAttachments',function(){
                var _token = $("input[name=token]").val(),
                voucher_id = $(this).data('selectvoucherid');
                SpinnerShow('btnViewVoucherAttachments',voucher_id);
                $.ajax({
                    type:'get',
                    url:"{{ route('get.VoucherListAttachments') }}",
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

            $(document).on('click','.SubmitPayout_btnGenerateEXCEL',function(){
                SpinnerShow('SubmitPayout_btnGenerateEXCEL','btnloadingIcon');
                $('.errormsg_generateexcel').css('display','none');
                $('#SubmitPayouts_selectedamt').val("0.00");  
                $('.SubmitPayouts_totalselectedamt').html("0.00");
                $('#selectPayoutExportModal').modal('toggle');  
                SubmitPayoutList();
                SpinnerHide('SubmitPayout_btnGenerateEXCEL','btnloadingIcon');
            });

            $(document).on('click','.SubmitPayout_btnSubmitSelectedBatch',function(){
                SpinnerShow('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Generate Payout in Excel?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Generate',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        batchid = $('.selectedbatch:checked').map(function(){
                        return $(this).data('selectedbatchid');}).get().join(',');
                        if(batchid != ""){  
                            $.ajax({
                                type:'post',
                                url:"{{ route('generate.SupplierPayoutExcel') }}",
                                data:{batchid:batchid,_token:_token},
                                    success:function(data){
                                        if(data == "failed"){
                                            Swal.fire({
                                                allowOutsideClick: false,
                                                title:'Failed!',
                                                text:'You are already generate DBP Batch!',
                                                icon:'danger'
                                            });
                                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                        }else{
                                            Swal.fire({
                                                allowOutsideClick: false,
                                                title:'Generated!',
                                                text:'Your excel file successfully Generated!',
                                                icon:'success'
                                            });
                                            SubmitPayoutGeneratedList();
                                            $('.errormsg_generateexcel').css('display','none');
                                            $('#selectPayoutExportModal').modal('hide');
                                            $('#SubmitPayouts_selectedamt').val("0.00");  
                                            $('.SubmitPayouts_totalselectedamt').html("0.00");
                                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                        }
                                        
                                    },
                                    error: function (textStatus, errorThrown) {
                                            console.log('Err');
                                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                        }
                                });
                                }else{
                                    $('.errormsg_generateexcel').css('display','block');
                                    $(".errormsg_generateexcel").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select Batch Payout for Generation of excel! Please try again.");
                                    SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                                }
                        }else{
                            SpinnerHide('SubmitPayout_btnSubmitSelectedBatch','btnloadingIcon2');
                        }
                    });
            });
            
            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#SubmitPayoutsList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#SubmitPayoutsList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            // Handle click on checkbox
            $('#SubmitPayoutsList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });

            $('#SubmitPayoutsList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var total_amount = $(this).data('selectedbatchamt');
                var $row = $(this).closest('tr');
                if(this.checked){
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#SubmitPayouts_selectedamt').val()); 
                    var total = selectedamt + total_amount;
                    $('#SubmitPayouts_selectedamt').val(total);
                    $('.SubmitPayouts_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total));
                    $row.addClass("selected");
                }else{                 
                    total_amount = parseInt($(this).data('selectedbatchamt'));                    
                    var selectedamt = parseInt($('#SubmitPayouts_selectedamt').val()); 
                    var total = selectedamt - total_amount;
                    $('#SubmitPayouts_selectedamt').val(total);
                    $('.SubmitPayouts_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total));    
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });

            function ApprovedHistoryList(){
                    var table = $('#ApprovedHistoryList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.SubmitteddHistoryList') }}",
                    columns: [ 
                            {data: 'transac_date', name: 'transac_date', title:'DATE SUBMITTED'},
                            {data: 'file_name', name: 'file_name', title:'DBP BATCH CODE'},
                            {data: 'application_number', name: 'application_number', title:'APPLICATION NUMBER'},
                            {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        ]
                    }).ajax.reload();               
           }

            $(document).on('click','.btnApprovedHistoryList',function(){
                SpinnerShow('btnApprovedHistoryList','btnloadingIcon3');
                ApprovedHistoryList();
                $('#ApprovedHistoryModal').modal('toggle');
                SpinnerHide('btnApprovedHistoryList','btnloadingIcon3');
           });

           $(document).on('click','.btnDownloadExcelFile',function(){
                var filename = $(this).data('excelfilename');
                $('#download_filename').val(filename);
                SpinnerShow('btnDownloadExcelFile',filename);
                $('#SubmitPayoutPincodeModal').modal('toggle');
                SpinnerHide('btnDownloadExcelFile',filename);                
           });

           $(document).on('click','.SubmitPayout_validate',function(){
            var _token = $("input[name=token]").val(),
                filename = $('#download_filename').val(),
                email = $('.SubmitPayout_email').val(),
                password = $('.SubmitPayout_password').val();
            SpinnerShow('SubmitPayout_validate','btnloadingIcon4');
            $.ajax({
                type:'post',
                url:"{{ route('validate.SubmitPayoutPin') }}",
                data:{filename:filename,email:email,password:password,_token:_token},
                    success:function(data){
                        if(data == "INVALID"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                        }else if(data == "NO_EXIST"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                        }else{   
                            $('#SubmitPayoutPincodeModal').modal('hide');                            
                            $('.errormsg').css('display','none');
                            // $('.SubmitPayout_downloadfile').click();
                            window.location = $('.SubmitPayout_downloadfile').attr('href');
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                            
                        }
                        
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('SubmitPayout_validate','btnloadingIcon4'); 
                        }
                });
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
<input type="hidden" id="SubmitPayouts_selectedbatches" value="0">
<input type="hidden" id="SubmitPayouts_selectedbatchid" value="0">
<input type="hidden" id="SubmitPayouts_selectedvoucherid" value="0">
<input type="hidden" id="SubmitPayouts_selectedamt" value="0">
<input type="hidden" id="selectedProgramId" value="">
<input type="hidden" id="download_filename" value="">
<a href="{{route('download.SubmitPayoutExcelFile')}}" class="SubmitPayout_downloadfile" style="display:none;"></a>
<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Submit Payout</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Submit Payout</li>
        </ol>   
    </div>
</div>
<!-- MAIN VIEW CONTENT -->
<div class="">
    <a href="javascript:;" class="btn btn-xs btn-info btnApprovedHistoryList"><i class="fa fa-archive"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Submitted Payout History</a>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title btn btn-xs" style="font-weight:normal !important;">Payout Details:</h4>   
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-info SubmitPayout_btnGenerateEXCEL"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Generate Payout Excel</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
                 
    </div>
    <div class="panel-body">
        <table id="SubmitPayoutGeneratedList-datatable" class="table table-striped nowrap" style="width: 100%;">            
            <thead style=" color:#000"></thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white">Submitted Payout History</h4>
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

<div class="modal fade bd-example-modal-lg" id="selectPayoutExportModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white">Payout List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <div class="row m-b-15">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger errormsg_generateexcel" role="alert" style="display: none;">
                                        </div>
                                    </div>
                                </div> 
                                    <table id="SubmitPayoutsList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                                        <thead style="background-color: #008a8a">
                                            <tr>
                                                <th scope="col" style="color: white">
                                                    <div class="checkbox checkbox-css">
                                                        <input type="checkbox" id="cssCheckbox1" class="selectedbatchall" name="select_all" value="1">
                                                        <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                                                    </div>
                                                </th>
                                                <th scope="col">TRANSACTION DATE</th>
                                                <th scope="col" style="color: white">APPLICATION NO.</th>
                                                <th scope="col" style="color: white">DESCRIPTION</th>
                                                <th scope="col" style="color: white">TOTAL AMOUNT</th>
                                            </tr>
                                        </thead>
                                    </table>
                                <label class="pull-right">
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                        <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                                        </div>
                                        <h3 class="alert alert-primary SubmitPayouts_totalselectedamt" role="alert">0.00</h3>
                                    </div>
                                </label>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success SubmitPayout_btnSubmitSelectedBatch"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Generate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="SubmitPayoutPincodeModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Security</h4>
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
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <input type="email" class="form-control SubmitPayout_email" rows="3" placeholder="Enter Email" required>                                            
                                        </div>
                                    </div> 
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <input type="password" class="form-control SubmitPayout_password" rows="3" placeholder="Enter Password" required>
                                            
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
                    <a href="javascript:;" class="btn btn-success SubmitPayout_validate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon4 pull-left m-r-10" style="display: none;"></i> Validate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection