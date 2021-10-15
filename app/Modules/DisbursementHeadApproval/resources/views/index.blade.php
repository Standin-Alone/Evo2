@extends('global.base')
@section('title', "Final Approval")

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
            
            HeadApprovalList();

            function HeadApprovalList(){                    
                    var table = $('#HeadApprovalList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    "paging": false,
                    ordering: false,
                    scrollY: "200px",
                    ajax: "{{ route('get.HeadApprovalList') }}",
                    columns: [ 
                        {data: 'folder_file_name', name: 'folder_file_name',
                            render: function(data, type, row) {
                                return '<input type="checkbox" id="cssCheckbox1" data-selecteddbpbatchid="'+row.folder_file_name+'" data-selecteddbpbatchtotalamt="'+row.total_amount+'" class="selecteddbpbatch m-l-5" /><label for="cssCheckbox1"></label>';  
                            }
                        },
                        {data: 'created_at', name: 'created_at'},
                        {data: 'folder_file_name', name: 'folder_file_name'},
                        {data: 'name', name: 'name'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display},
                        {data: 'folder_file_name', name: 'folder_file_name',  title:'ACTION',
                            render: function(data, type, row) {
                                    if(row.isdownloadedxls_r === 1){
                                        return '<button data-excelfilename="'+row.name+'" data-folderfilename="'+row.folder_file_name+'" data-filetype="'+row.filetype+'"  class="btn btn-xs btn-outline-danger btnDownloadZipFile"><span class="fa fa-cloud-download-alt"></span><i class="fas fa-spinner fa-spin '+row.name+' pull-left m-r-10" style="display: none;"></i> Re-Download '+row.filetype+'</button>';
                                    }else{
                                        return '<button  data-excelfilename="'+row.name+'" data-folderfilename="'+row.folder_file_name+'" data-filetype="'+row.filetype+'"  class="btn btn-xs btn-outline-info btnDownloadZipFile"><span class="fa fa-cloud-download-alt"></span><i class="fas fa-spinner fa-spin '+row.name+' pull-left m-r-10" style="display: none;"></i> Download '+row.filetype+'</button>';
                                    } 
                                
                            }
                        }
                    ]
                });
                    // RETURN INTO ZERO WHEN USE SEARCH BOX
                    $('.dataTables_filter').on('keyup', 'input[type="search"]', function(e){
                        $('.SubmitPayouts_totalselectedamt').html("0.00"); 
                        $('#SubmitPayouts_selectedamt').val("0.00");
                    });  
                    
            }
            
            // Handle click on "Select all" control
            $('.selectedbatchall').on('click', function(e){
                if(this.checked){
                    $('#HeadApprovalList-datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#HeadApprovalList-datatable tbody input[type="checkbox"]:checked').trigger('click');
                }
                e.stopPropagation();
            });

            // Handle click on checkbox
            $('#HeadApprovalList-datatable').on('click', 'td:first-child', function(e){
                $(this).parent().find('input[type="checkbox"]').trigger('click');
                e.stopPropagation();
            });

            $('#HeadApprovalList-datatable tbody').on('click', 'input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');
                if(this.checked){
                    $row.addClass("selected");
                }else{ 
                    $row.removeClass("selected");
                }                    
                e.stopPropagation();
            });


            $(document).on('click','.btnHeadApproval',function(){
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
                        folder_file_name = $('.selecteddbpbatch:checked').map(function(){
                        return $(this).data('selecteddbpbatchid');}).get().join(',');
                        if(folder_file_name != ""){  
                            $.ajax({
                            type:'post',
                            url:"{{ route('approve.HeadApproval') }}",
                            data:{folder_file_name:folder_file_name,_token:_token},
                                success:function(data){
                                    Swal.fire(
                                        'Approved!',
                                        'Your DBP Batch successfully Approved!',
                                        'success'
                                    )
                                    HeadApprovalList();
                                    $('.errormsg_HeadApproval').css('display','none');
                                    SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                },
                                error: function (textStatus, errorThrown) {
                                        console.log('Err');
                                        SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                        $('.errormsg_HeadApproval').css('display','none');
                                    }
                            });
                        }else{                            
                                $('.errormsg_HeadApproval').css('display','block');
                                $(".errormsg_HeadApproval").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Select DBP Batch for Approval! Please try again.");     
                                SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                                AlertHide('errormsg_HeadApproval');
                            }
                        
                        
                        }else{
                            $('.errormsg_HeadApproval').css('display','none');
                            SpinnerHide('btnDownloadExcelFile','btnloadingIcon');
                        }
                    });
            });

            $('#HeadApprovalList-datatable').on('click','[data-dtr-index="6"]',function(){
                $('.btnDownloadZipFile').click();
                $('#HeadApprovalList-datatable').unbind('click');
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
                            {data: 'folder_file_name', name: 'folder_file_name', title: 'GROUP NAME'},
                            {data: 'name', name: 'name', title: 'FILE NAME'},
                            {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                            {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    });
                $('#ApprovedHistoryModal').modal('toggle');
                SpinnerHide('btnDownloadExcelFile','btnloadingIcon1');
           });

           $(document).on('click','.btnDownloadZipFile',function(){
            var _token = $("input[name=token]").val(),
                filename = $(this).data('excelfilename'),
                filetype = $(this).data('filetype'),
                folder_filename = $(this).data('folderfilename');
                SpinnerShow('btnDownloadZipFile',filename);
                $('.SubmitPayout_email, .SubmitPayout_password').val('');
                $('.errormsg').css('display','none');
                $.ajax({
                    type:'post',
                    url:"{{ route('validate.HeadApprovalPin') }}",
                    data:{folder_filename:folder_filename,filename:filename,filetype:filetype,_token:_token},
                        success:function(data){                         
                                $('.errormsg').css('display','none');                                
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Please wait..',
                                    text:'downloading file in progress.',
                                    icon:'info'
                                });
                                $('#HeadApprovalList-datatable').unbind('click');
                                window.location = $('.HeadApproval_downloadfile').attr('href');
                                HeadApprovalList();
                                SpinnerHide('btnDownloadZipFile',filename);                      
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnDownloadZipFile',filename);
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
<input type="hidden" id="download_folder_filename" value="">
<input type="hidden" id="download_filetype" value="">
<input type="hidden" id="download_filename" value="">
<a href="{{route('download.DBPBatchDownload')}}" class="HeadApproval_downloadfile" style="display:none;"></a>
<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Final Approval</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Final Approval</li>
        </ol>   
    </div>
</div>
<!-- MAIN VIEW CONTENT -->
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <div class="widget widget-stats bg-gradient-blue">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-pie fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title"><h5 style="color:#f8f9fa;">Final Approved Batch History</h5></div>
                        <div class="stats-progress progress"></div>
                        <a href="javascript:;" class="pull-right btnApprovedHistoryList" style="color:#f8f9fa;"><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> View Details</a>                
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="pull-right">                              
                    <a href="javascript:;" class="btn btn-lg btn-primary btnHeadApproval">  
                        <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                        <i class="fa fa-thumbs-up fa-2x pull-left m-r-10 text-black"></i>
                        <b class="disbursementdetails_title"> Approve DBP Batch File </b><br />
                        <small>Click Here</small>
                    </a>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">    
        <div class="panel-heading-btn">
            <!-- <a href="javascript:;" class="btn btn-xs btn-info btnHeadApproval"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Approve DBP Batch File</a> -->
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title panel-title-main btn btn-xs" style="font-weight:normal !important;">DBP File Details:</h4> 
        <h4 class="panel-title panel-title-sub btn btn-xs" style="font-weight:normal !important;display:none;">:</h4> 
    </div>
    <div class="panel-body">
        <div class="alert alert-danger errormsg_HeadApproval" role="alert" style="display: none;"></div>
            <table id="HeadApprovalList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                <thead style="background-color: #008a8a">
                    <tr>
                        <th scope="col" style="color: white;">
                            <div class="form-check">
                                <input type="checkbox" id="cssCheckbox1" class="selectedbatchall form-check-input is-valid" name="select_all" value="1">
                                <label for="cssCheckbox1" style="color: white !important;">&nbsp;SELECT ALL</label>
                            </div>                            
                        </th>
                        <th scope="col" style="color: white">TRANSACTION DATE</th>
                        <th scope="col" style="color: white">GROUP NAME</th>
                        <th scope="col" style="color: white">FILE NAME</th>
                        <th scope="col" style="color: white">TOTAL AMOUNT</th>
                        <th scope="col" style="color: white">TOTAL RECORDS</th>
                        <th scope="col" style="color: white">ACTION</th>
                    </tr>
                </thead>
            </table>
        </div>
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

<div class="modal fade" id="HeadApprovalPincodeModal" data-keyboard="false" data-backdrop="static">
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
                                            <div class="mb-3">
                                                <label class="form-label" for="exampleInputEmail1">Email address</label>
                                                <input class="form-control SubmitPayout_email" type="email" id="exampleInputEmail1" placeholder="Enter email" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="exampleInputPassword1">Password</label>
                                                <input class="form-control SubmitPayout_password" type="password" id="exampleInputPassword1" placeholder="Password" />
                                            </div>                                                                                 
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
                    <a href="javascript:;" class="btn btn-success HeadApproval_validate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon4 pull-left m-r-10" style="display: none;"></i> Validate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection