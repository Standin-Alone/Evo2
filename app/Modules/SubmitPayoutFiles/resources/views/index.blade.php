@extends('global.base')
@section('title', "Submit Payout Files")

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

            SubmitPayoutFileList();

            function SubmitPayoutFileList(){
                var table = $('#SubmitPayoutFileList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.SubmitPayoutFileList') }}",
                    columns: [
                        {data: 'created_at', name: 'created_at', title: 'TRANSACTION DATE'},
                        {data: 'name', name: 'name', title: 'FILE NAME'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'},
                    ],
                });
            }

            $(document).on('click','.btnGenerateKey',function(){
                var _token = $("input[name=token]").val(),
                    dbp_batch_id = $(this).data('selecteddbpfileid'),
                    file_name = $(this).data('selectedfilename');
                    SpinnerShow('btnGenerateKey',file_name);

                Swal.fire({
                title: 'Are you sure',
                text: "You want to Generate Textfile with Private Key?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Submit',
                allowOutsideClick: false
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'get',
                        url:"{{ route('generate.PrivateKey') }}",
                        data:{dbp_batch_id:dbp_batch_id,file_name:file_name,_token:_token},
                        success:function(data){  
                            if(data == "failed"){
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Failed!',
                                    text:'File directory does not exist!',
                                    icon:'error'
                                });
                                SubmitPayoutFileList();
                                SpinnerHide('btnGenerateKey',file_name);
                            }else{
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Generated!',
                                    text:'Your DBP textfile successfully Generated!',
                                    icon:'success'
                                });
                                SubmitPayoutFileList();
                                SpinnerHide('btnGenerateKey',file_name);
                            }                       
                            
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('btnGenerateKey',file_name);
                                }
                            });
                        }else{
                            SpinnerHide('btnGenerateKey',file_name);
                        }
                    });
            });

            $(document).on('click','.btngeneratedtextfilehistory',function(){
                SpinnerShow('btngeneratedtextfilehistory','btnloadingIcon');
                var table = $('#GeneratedtextfileHistorylist-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.GeneratedTextfileHistory') }}",
                    columns: [ 
                        {data: 'created_at', name: 'created_at', title: 'TRANSACTION DATE'},
                        {data: 'name', name: 'name', title: 'FILE NAME'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    }).ajax.reload();
                $('#GeneratedtextfileHistoryModal').modal('toggle');
                SpinnerHide('btngeneratedtextfilehistory','btnloadingIcon');
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
    <div class="col-md-6">
            <div class="input-group">
            <h1 class="page-header">Submit Payout File</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('PayoutModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Submit Payout File</li>
        </ol>   
    </div>
</div>
<div>
    <a href="javascript:;" class="btn btn-xs btn-info btngeneratedtextfilehistory"><i class="fa fa-archive"></i><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i> Generated Textfile History</a>
</div><br>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <!-- <a href="javascript:;" class="btn btn-xs btn-info btnGenerateKey"><i class="fa fa-file-alt"></i> Generate Payout Textfile</a> -->
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Payout file Details:</h4>
    </div>
    <div class="panel-body">
        <table id="SubmitPayoutFileList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="GeneratedtextfileHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Generated Texfile History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="GeneratedtextfileHistorylist-datatable" class="table table-striped display nowrap" style="width: 100%;">
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