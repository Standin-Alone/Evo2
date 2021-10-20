@extends('global.base')
@section('title', "Submit Disbursement")

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

        generatedSubmitDisbursementlist()

        function generatedSubmitDisbursementlist(){
            var table = $('#generatedSubmitDisbursementlist-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,  
            scrollY: "200px",             
            ajax: "{{ route('get.generatedSubmitDisbursementlist') }}",
            columns: [ 
                {data: 'created_at', name: 'created_at', title:'TRANSACTION DATE'},
                {data: 'folder_file_name', name: 'folder_file_name', title: 'GROUP NAME'},
                {data: 'name', name: 'name', title: 'FILE NAME'},
                {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title: 'TOTAL RECORDS'},
                {data: 'name', name: 'name',  title:'ACTION',
                        render: function(data, type, row) {
                            $button1 = "";
                            $button2 = "";
                            // if(row.approver_id != ""){
                                if(row.isdownloaded === 1){
                                    $button1 = '<a href="javascript:;"  data-excelfilename="'+row.name+'" data-folderfilename="'+row.folder_file_name+'" data-filetype="(.txt)"  class="btn btn-xs btn-outline-danger btnDownloadFile"><i class="fas fa-spinner fa-spin '+row.name+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-cloud-download-alt"></span> Re-Download (.txt)</a>'; 
                                }else{
                                    $button1 = '<a href="javascript:;"  data-excelfilename="'+row.name+'" data-folderfilename="'+row.folder_file_name+'" data-filetype="(.txt)"  class="btn btn-xs btn-outline-info btnDownloadFile"><i class="fas fa-spinner fa-spin '+row.name+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-cloud-download-alt"></span> Download (.txt)</a>';
                                } 
                            // }else{
                            //     $button1 = '<span class="btn btn-xs btn-outline-default" disabled data-toggle="tooltip" data-placement="top" title="For Review and Approve by Regional Director"><i class="fas fa-spinner fa-spin pull-left m-r-10" style="display: none;"></i><span class="fa fa-cloud-download-alt"></span> Download (.txt)</span>'; 
                            // }
                            
                            
                            if(row.isdownloadedxls === 1){
                                $button2 = '<a href="javascript:;"  data-excelfilename="'+row.name+'" data-folderfilename="'+row.folder_file_name+'" data-filetype="(.xls)"  class="btn btn-xs btn-outline-danger btnDownloadFile"><i class="fas fa-spinner fa-spin '+row.name+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-cloud-download-alt"></span> Re-Download (.xls)</a>';
                            }else{
                                $button2 = '<a href="javascript:;"  data-excelfilename="'+row.name+'" data-folderfilename="'+row.folder_file_name+'" data-filetype="(.xls)"  class="btn btn-xs btn-outline-info btnDownloadFile"><i class="fas fa-spinner fa-spin '+row.name+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-cloud-download-alt"></span> Download (.xls)</a>';
                            }
                            return $button1+$button2;
                        }
                    }  
            ]
            });
        }
           
        function SubmitDisbursementList(selected_prv_code){             
            var table = $('#SubmitDisbursementList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,   
                    scrollY: "200px",
                    ajax: "{{ route('get.SubmitDisbursementList') }}" + '?selected_prv_code=' + selected_prv_code,
                    columns: [
                        {data: 'approved_date', name: 'approved_date', title:'APPROVED DATE'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq', title:'DISBURSEMENT BATCH NO.'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL AMOUNT'},   
                        {data: 'total_records', name: 'total_records', title:'TOTAL RECORDS'}, 
                        {data: 'approved_batch_seq', name: 'approved_batch_seq',  title:'ACTION',
                            render: function(data, type, row) {
                                return '<a href="javascript:;" data-selectedbatchid="'+row.approved_batch_seq+'" class="btn btn-xs btn-outline-info SubmitDisbursement_btnGenerate"><i class="fas fa-spinner fa-spin '+row.approved_batch_seq+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-file-excel"></span> Generate</a>';
                            }
                        }                   
                    ],
                });
            }

            $(document).on('click','.SubmitDisbursement_btnGenerateEXCEL',function(){
                SpinnerShow('SubmitDisbursement_btnGenerateEXCEL','btnloadingIcon');
                $('.errormsg_generateexcel').css('display','none');
                $('#SubmitDisbursement_selectedamt').val(0);  
                $('.SubmitDisbursement_totalselectedamt').html("0.00");
                $('#selectDisbursementExportModal').modal('toggle');  
                SubmitDisbursementList("");
                getProvinceList();
                SpinnerHide('SubmitDisbursement_btnGenerateEXCEL','btnloadingIcon');
            });

            $(document).on('click','.SubmitDisbursement_btnGenerate',function(){
                var batch_id = $(this).data('selectedbatchid'),
                    _token = $("input[name=token]").val();
                SpinnerShow('SubmitDisbursement_btnGenerate',batch_id);
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Generate the selected Disbursement?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Generate',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                            $.ajax({
                                type:'post',
                                url:"{{ route('generate.SubmitDisbursement') }}",
                                data:{batch_id:batch_id,_token:_token},
                                success:function(data){ 
                                    if(data == "failed"){
                                        Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Failed!',
                                            text:'Your Disbursement List failed to generate excel!',
                                            icon:'error'
                                        });
                                        SpinnerHide('SubmitDisbursement_btnGenerate',batch_id); 
                                    }
                                    else if(data == "exists"){
                                        Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Failed!',
                                            text:'Transaction are already Locked! Please try in the next day.',
                                            icon:'error'
                                        });
                                        SpinnerHide('SubmitDisbursement_btnGenerate',batch_id); 
                                    }
                                    else{
                                        Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Generated!',
                                            text:'Your Disbursement List successfully Generated!',
                                            icon:'success'
                                        });                                  
                                        $('.errormsg_generateexcel').css('display','none');
                                        $('#SubmitDisbursement_selectedamt').val(0);  
                                        $('.SubmitDisbursement_totalselectedamt').html("0.00"); 
                                        var tablereset = $('#SubmitDisbursementList-datatable').DataTable();
                                        tablereset.clear().draw();
                                        var selected_prv_code = $('.selectProvince').val();
                                        SubmitDisbursementList(selected_prv_code);
                                        generatedSubmitDisbursementlist();                                        
                                        SpinnerHide('SubmitDisbursement_btnGenerate',batch_id);  
                                    }                               
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('SubmitDisbursement_btnGenerate',batch_id);
                                }
                            });
                        
                    }else{
                        SpinnerHide('SubmitDisbursement_btnGenerate',batch_id);
                    }
                });
            });

            function ViewFinalSubmitDisbursement(){
                    var table = $('#FinalSubmitDisbursement-datatable').DataTable({ 
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,   
                        ajax: "{{ route('get.FinalSubmitDisbursementList') }}",
                        columns: [
                            {data: 'created_at', name: 'created_at', title:'APPROVED DATE'},
                            {data: 'folder_file_name', name: 'folder_file_name', title:'GROUP FILE NAME'},
                            {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL AMOUNT'},  
                            {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL RECORDS'}, 
                            {name:'action', title:'ACTION', render:function(data, type, row){
                                return '<a href="javascript:;" data-selectedfolder_name="'+row.folder_file_name+'" class="btn btn-xs btn-outline-info SubmitDisbursement_btnFinalSubmit"><i class="fas fa-spinner fa-spin '+row.folder_file_name+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-file-excel"></span> Final Submit</a>';
                            }}   
                        ],
                    });
                $('#FinalSubmitDisbursementModal').modal('toggle');
            }

            $(document).on('click','.SubmitDisbursement_btnViewDisbursement',function(){
                SpinnerShow('SubmitDisbursement_btnViewDisbursement','btnloadingIcon2');
                ViewFinalSubmitDisbursement();
                SpinnerHide('SubmitDisbursement_btnViewDisbursement','btnloadingIcon2');
            });

            $(document).on('click','.SubmitDisbursement_btnFinalSubmit',function(){      
                var folder_file_name = $(this).data('selectedfolder_name');
                SpinnerShow('SubmitDisbursement_btnFinalSubmit',folder_file_name);         
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Submit the generated Disbursement?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Submit',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val();
                            $.ajax({
                                type:'post',
                                url:"{{ route('generate.FinalSubmitDisbursement') }}",
                                data:{folder_file_name:folder_file_name,_token:_token},
                                success:function(data){ 
                                    if(data == "failed"){
                                        Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Failed!',
                                            text:'Your Final Submitted File is already done!',
                                            icon:'error'
                                        });
                                        SpinnerHide('SubmitDisbursement_btnFinalSubmit',folder_file_name);     
                                    }else{
                                        Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Generated!',
                                            text:'Your Disbursement List successfully Generated!',
                                            icon:'success'
                                        });                                  
                                        $('.errormsg_SubmitDisbursement').css('display','none');
                                        $('#SubmitDisbursement_selectedamt').val(0);  
                                        $('.SubmitDisbursement_totalselectedamt').html("0.00");
                                        generatedSubmitDisbursementlist();
                                        ViewFinalSubmitDisbursement();                                        
                                        SpinnerHide('SubmitDisbursement_btnFinalSubmit',folder_file_name);   
                                    }                               
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('SubmitDisbursement_btnFinalSubmit',folder_file_name); 
                                }
                            });
                        
                    }else{
                        SpinnerHide('SubmitDisbursement_btnFinalSubmit',folder_file_name); 
                    }
                });
            });

           $(document).on('click','.btnDownloadFile',function(){
            var _token = $("input[name=token]").val(),
                filename = $(this).data('excelfilename'),
                filetype = $(this).data('filetype'),
                folder_filename = $(this).data('folderfilename');
                SpinnerShow('btnDownloadFile',filename);
                $('.errormsg').css('display','none');
                $.ajax({
                    type:'post',
                    url:"{{ route('validate.SubmitDisbursementPin') }}",
                    data:{folder_filename:folder_filename,filename:filename,filetype:filetype,_token:_token},
                        success:function(data){                         
                            var url = $('.SubmitDisbursement_downloadfile').attr('href');
                            window.open(url,'_blank');                                                       
                            $('#generatedSubmitDisbursementlist-datatable').DataTable().ajax.reload();     
                            generatedSubmitDisbursementlist();   
                            SpinnerHide('btnDownloadFile',filename);                                                                       
                        },
                        error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('btnDownloadFile',filename); 
                        }
                });
                
           });

           function getProvinceList(){
                $.ajax({
                    type:'get',
                    url:"{{ route('get.SubmitDisbursementProvinceList') }}",
                    success:function(data){                    
                        $('.option_prov_code').remove();
                        for(var i=0;i<data.length;i++){                   
                                $('.selectProvince').append($('<option>', {class:'option_prov_code',value:data[i].prov_code, text:data[i].prov_name}));
                            }           
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                });  
            }
            
            $(document).on('change','.selectProvince',function(){
                var selected_prv_code = $(this).val();
                SpinnerShow('selectProvince',selected_prv_code);
                SubmitDisbursementList(selected_prv_code);
                SpinnerHide('selectProvince',selected_prv_code);
            });

            $(document).on('click','.btngeneratedDisburseHistory',function(){
                SpinnerShow('btngeneratedDisburseHistory','btnloadingIcon3');
                var table = $('#generatedDisburseHistory-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.generatedDisburseHistory') }}",
                    columns: [ 
                            {data: 'created_at', name: 'created_at', title:'DATE APPROVED'},
                            {data: 'folder_file_name', name: 'folder_file_name', title: 'GROUP NAME'},
                            {data: 'name', name: 'name', title: 'FILE NAME'},
                            {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                            {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    });
                $('#generatedDisburseHistoryModal').modal('toggle');
                SpinnerHide('btngeneratedDisburseHistory','btnloadingIcon3');
           });

        });        
</script>

@if(Session::get('failed'))
    <script>
        swal.fire({
        title: "Failed!",
        text: "{{ session('failed') }}",
        icon: "error",
        button: "OK",
        });
    </script>
@endif

@endsection

@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="SubmitDisbursement_selectedamt" value="0">
<input type="hidden" id="download_folder_filename" value="">
<input type="hidden" id="download_filetype" value="">
<input type="hidden" id="download_filename" value="">
<a href="{{route('download.SubmitDisbursementExcelFile')}}" class="SubmitDisbursement_downloadfile" style="display:none;"></a>

<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Submit Disbursement</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li> 
            <li class="breadcrumb-item active">Submit Disbursement</li>
        </ol>   
    </div>
</div>
<div class="row">
    <div class="col-xl-12 ui-sortable">       
        <div class="pull-right">                              
            <a href="javascript:;" class="btn btn-lg btn-primary SubmitDisbursement_btnGenerateEXCEL">  
                <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                <i class="fa fa-file-alt fa-2x pull-left m-r-10 text-black"></i>
                <b class="disbursementdetails_title"> Generate DBP Textfile</b><br />
                <small>Click Here</small>
            </a>
        </div>
    </div>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <!-- <a href="javascript:;" class="btn btn-xs btn-info SubmitDisbursement_btnGenerateEXCEL"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Generate DBP Textfile</a> -->
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title panel-title-main btn btn-xs" style="font-weight:normal !important;">DBP Batch Details:</h4>
        <h4 class="panel-title panel-title-sub btn btn-xs" style="font-weight:normal !important;display:none;">:</h4> 
    </div>
    <div class="panel-body">
    <table id="generatedSubmitDisbursementlist-datatable" class="table table-striped display nowrap" style="width: 100%;">
        <thead style="background-color: #008a8a;"></thead>
    </table>
    </div>
</div>
<!-- end panel -->

<div class="modal fade bd-example-modal-lg" id="selectDisbursementExportModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved Disbursement List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <!-- <a href="javascript:;" class="btn btn-success SubmitDisbursement_btnFinalSubmit"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Final Submit</a> -->
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <div class="row m-b-15">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger errormsg_SubmitDisbursement" role="alert" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <select id="default_DisbursementProvince" class="form-control selectProvince" name="DisbursementProvince" data-size="10" data-style="btn-white" value="{{ old('DisbursementProvince') }}">
                                        <option value="" selected>Select Province</option>
                                    </select>  
                                </div>
                                <div style="margin-top:10px;">
                                    <table id="SubmitDisbursementList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                                        <thead style="background-color: #008a8a"></thead>
                                    </table> 
                                </div>
                                
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">                   
                    <!-- <a href="javascript:;" class="btn btn-success SubmitDisbursement_btnGenerate"><i class="fa fa-file-excel"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Generate</a> -->
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="SubmitDisbursementPincodeModal" data-keyboard="false" data-backdrop="static">
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
                    <a href="javascript:;" class="btn btn-success SubmitDisbursement_validate"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon4 pull-left m-r-10" style="display: none;"></i> Validate</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="FinalSubmitDisbursementModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Final Submit Disbursement:</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    <table id="FinalSubmitDisbursement-datatable" class="table table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                </div>
                <div class="modal-footer">
                <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="generatedDisburseHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Generated Disbursement History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="generatedDisburseHistory-datatable" class="table table-striped display nowrap" style="width: 100%;">
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
@endsection