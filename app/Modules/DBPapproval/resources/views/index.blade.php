@extends('global.base')
@section('title', "DBP Approval")

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
            
            DBPapprovalList();

            function DBPapprovalList(){
                    var table = $('#DBPapprovalList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    "paging": false,
                    ordering: false,
                    ajax: "{{ route('get.DBPapprovalList') }}",
                    columns: [ 
                        {data: 'checkbox', name: 'checkbox'},  
                        {data: 'created_at', name: 'created_at'},
                        {data: 'name', name: 'name'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                }).ajax.reload();
                    // RETURN INTO ZERO WHEN USE SEARCH BOX
                    $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                        $('.SubmitPayouts_totalselectedamt').html("0.00"); 
                        $('#SubmitPayouts_selectedamt').val("0.00");
                    });
                
            }
            
            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#DBPapprovalList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#DBPapprovalList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            // Handle click on checkbox
            $('#DBPapprovalList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });

            $('#DBPapprovalList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');
                if(this.checked){
                    $row.addClass("selected");
                }else{ 
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });


            $(document).on('click','.btnDBPapproval',function(){
                SpinnerShow('btnDownloadExcelFile','btnloadingIcon');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Approve all selected DBP Batch?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Approve',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        batchid = $('.selecteddbpbatch:checked').map(function(){
                        return $(this).data('selecteddbpbatchid');}).get().join(',');
                        if(batchid != ""){  
                            $.ajax({
                            type:'post',
                            url:"{{ route('approve.DBPapproval') }}",
                            data:{batchid:batchid,_token:_token},
                                success:function(data){
                                    Swal.fire(
                                        'Approved!',
                                        'Your DBP Batch successfully Approved!',
                                        'success'
                                    )
                                    DBPapprovalList();
                                    $('.errormsg_dbpapproval').css('display','none');
                                    SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                },
                                error: function (textStatus, errorThrown) {
                                        console.log('Err');
                                        SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                        $('.errormsg_dbpapproval').css('display','none');
                                    }
                            });
                        }else{                            
                                $('.errormsg_dbpapproval').css('display','block');
                                $(".errormsg_dbpapproval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select DBP Batch for Approval! Please try again.");     
                                SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                            }
                        
                        
                        }else{
                            SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                        }
                    });
            });

            $(document).on('click','.btnApprovedHistoryList',function(){
                SpinnerShow('btnDownloadExcelFile','btnloadingIcon1');
                var table = $('#ApprovedHistoryList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.DBPapproveddHistoryList') }}",
                    columns: [ 
                            {data: 'created_at', name: 'created_at', title:'DATE APPROVED'},
                            {data: 'file_name', name: 'file_name', title:'DBP BATCH CODE'},
                            {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                            {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    }).ajax.reload();
                $('#ApprovedHistoryModal').modal('toggle');
                SpinnerHide('btnDownloadExcelFile','btnloadingIcon1');
           });

           $(document).on('click','.btnDownloadTextFile',function(){
                var filename = $(this).data('textfilename');
                $('#download_filename').val(filename);
                SpinnerShow('btnDownloadTextFile',filename);
                $('#dbpapprovalPincodeModal').modal('toggle');
                SpinnerHide('btnDownloadTextFile',filename);   
           });

           $(document).on('click','.dbpapproval_validate',function(){
            var _token = $("input[name=token]").val(),
                filename = $('#download_filename').val(),
                email = $('.dbpapproval_email').val(),
                password = $('.dbpapproval_password').val();
            SpinnerShow('dbpapproval_validate','btnloadingIcon4');
            $.ajax({
                type:'post',
                url:"{{ route('validate.DBPApprovalPin') }}",
                data:{filename:filename,email:email,password:password,_token:_token},
                    success:function(data){
                        if(data == "INVALID"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4'); 
                        }else if(data == "NO_EXIST"){
                            $('.errormsg').css('display','block');
                            $(".errormsg").html("Invalid email or password! Please try again.");
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4'); 
                        }else{   
                            $('#dbpapprovalPincodeModal').modal('hide');                            
                            $('.errormsg').css('display','none');
                            // $('.dbpapproval_downloadfile').click();
                            window.location = $('.dbpapproval_downloadfile').attr('href');
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4');                             
                        }                        
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('dbpapproval_validate','btnloadingIcon4'); 
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
<a href="{{route('download.DBPBatchDownload')}}" class="dbpapproval_downloadfile" style="display:none;"></a>
<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">DBP Approval</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">DBP Approval</li>
        </ol>   
    </div>
</div>
<!-- MAIN VIEW CONTENT -->
<div>
    <a href="javascript:;" class="btn btn-xs btn-info btnApprovedHistoryList"><i class="fa fa-archive"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> DBP Approved Batch History</a>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">    
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-info btnDBPapproval"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Approve DBP File</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title btn btn-xs" style="font-weight:normal !important;">DBP File Details:</h4> 
    </div>
    <div class="panel-body">
        <div class="alert alert-danger errormsg_dbpapproval" role="alert" style="display: none;"></div>
        <table id="DBPapprovalList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a">
                <tr>
                    <th scope="col" style="color: white">
                        <div class="checkbox checkbox-css">
                            <input type="checkbox" id="cssCheckbox1" class="selectedbatchall" name="select_all" value="1">
                            <label for="cssCheckbox1" style="color: white">&nbsp;&nbsp;ALL</label>
                        </div>
                    </th>
                    <th scope="col" style="color: white">TRANSACTION DATE</th>
                    <th scope="col" style="color: white">FILE NAME</th>
                    <th scope="col" style="color: white">TOTAL AMOUNT</th>
                    <th scope="col" style="color: white">TOTAL RECORDS</th>
                    <th scope="col" style="color: white">ACTION</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved DBP History</h4>
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

<div class="modal fade" id="dbpapprovalPincodeModal" data-keyboard="false" data-backdrop="static">
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
                                        <input type="email" class="form-control dbpapproval_email" rows="3" placeholder="Enter Email" required>                                            
                                        </div>
                                    </div> 
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                        <input type="password" class="form-control dbpapproval_password" rows="3" placeholder="Enter Password" required>
                                            
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
                    <a href="javascript:;" class="btn btn-success dbpapproval_validate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon4 pull-left m-r-10" style="display: none;"></i> Validate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection