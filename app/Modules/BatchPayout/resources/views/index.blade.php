@extends('global.base')
@section('title', "Batch Payout")

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
        
        // CALL DATATABLE CONTENT
        BatchPayoutList();

        // DATATABE CONTENT
        function BatchPayoutList(){
            $('#BatchPayout-datatable').unbind('click');
            var table = $('#BatchPayout-datatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('get.BatchPayoutList') }}",
                dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                columns: [
                    {data: 'program', name: 'program', title:'PROGRAM'},
                    {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                    {data: 'application_number', name: 'application_number', title: 'APPLICATION NO.'},
                    {data: 'description', name: 'description', title: 'DESCRIPTION'},
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    {data: 'payout_status', name: 'payout_status', title: 'APPROVAL STATUS'},                  
                    {data: 'action', name: 'action', orderable: true, searchable: true, title: 'ACTION'},
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
                    var TotalAmount = 0;                                        
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['grandtotalamount'];
                            TotalAmount = parseInt(dataval);
                        }
                        // DISPLAY GRAND TOTAL
                        $('.BatchPayout_totalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                }
            }).ajax.reload(); 

            // DRAW DATATABLE BASED ON THE DEFAULT PROGRAM
            var SessionId = $('#selectedProgramDesc').val();
            table.column(0).search(SessionId).draw();
        }       

        // CLICK FUNCTION TO CREATE BATCH WITH APPLICATION_NUMBER AND DESCRIPTION
        $(document).on('click','.btnCreateBatch', function(){
            SpinnerShow('btnCreateBatch','btnloadingIcon');
            $('.errormsg').css('display','none');
            $('.txtdescription').val('');
            $('#CreateBatchModal').modal('toggle');     
            SpinnerHide('btnCreateBatch','btnloadingIcon');    
        });

        // CLICK FUNCTION TO SAVE INPUTED BATCH DATA
        $(document).on('click','.btnSubmitBatch', function(){
            SpinnerShow('btnSubmitBatch','btnloadingIcon1');                        
            Swal.fire({
            title: 'Are you sure',
            text: "You want to create Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Create',
            allowOutsideClick: false,
            }).then((result) => {
            if (result.isConfirmed) {
                var _token = $("input[name=token]").val(),
                    program_id = $('#selectedProgramId').val(), 
                    desc = $('.txtdescription').val();                    
                if (desc == '') {
                        $('.errormsg').css('display','block');
                        $(".errormsg").html("Please enter value in both field!");
                        SpinnerHide('btnSubmitBatch','btnloadingIcon1'); 
                    }else{                   
                        $.ajax({
                            type:'post',
                            url:"{{ route('create.batchpayout') }}",
                            data:{program_id:program_id,desc:desc,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Created!',
                                    text:'Your Batch Payout successfully created!',
                                    icon:'success'
                                });
                                $('#BatchPayout-datatable').DataTable().ajax.reload();
                                $('.errormsg').css('display','none');
                                $('.txtdescription').val('');
                                $('#CreateBatchModal').modal('hide');
                                BatchPayoutList();
                                SpinnerHide('btnSubmitBatch','btnloadingIcon1'); 
                            },
                            error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnSubmitBatch','btnloadingIcon1'); 
                            }
                        }); 
                    }          
                }else{
                    SpinnerHide('btnSubmitBatch','btnloadingIcon1');   
                }
            });
        });

        // CLICK FUNCTION TO EDIT/MODIFY THE EXISTING BATCH
        $(document).on('click','.btneditbatchpayout', function(){                   
            var batch_id = $(this).data('editbatchid'),
            desc = $(this).data('editdescription'),
            amount = $(this).data('editamount');
            SpinnerShow('btneditbatchpayout',batch_id);
            if(amount > 0){                    
                Swal.fire(
                    'Failed!',
                    'Failed to process! Batch is already used by another transaction.',
                    'warning'
                )
                $('#EditBatchPayoutModal').modal('hide');
                SpinnerHide('btneditbatchpayout',batch_id);
            }else{
                $('.edit_txtbatch_id').val(batch_id);
                $('.edit_txtdescription').val(desc);
                $('#EditBatchPayoutModal').modal('toggle'); 
                SpinnerHide('btneditbatchpayout',batch_id);                   
            }                 
        });

        // CLICK FUNCTION TO UPDATE BATCH DATA
        $(document).on('click','.btnUpdateBatch', function(){
            SpinnerShow('btnUpdateBatch','btnloadingIcon2');

            Swal.fire({
            title: 'Are you sure',
            text: "You want to update Batch Payout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Update',
            allowOutsideClick: false
            }).then((result) => {
            if (result.isConfirmed) {
                var _token = $("input[name=token]").val(),
                    batch_id = $('.edit_txtbatch_id').val(),
                    desc = $('.edit_txtdescription').val();
                if (batch_id == '' || desc == '') {
                        $('.errormsgedit').css('display','block');
                        $(".errormsgedit").html("Please enter value in both field!");
                        SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                    }else{                     
                        $.ajax({
                            type:'post',
                            url:"{{ route('update.batchpayout') }}",
                            data:{batch_id:batch_id,desc:desc,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Updated!',
                                    text:'Your Batch Payout successfully Updated!',
                                    icon:'success'
                                });
                                $('#BatchPayout-datatable').DataTable().ajax.reload();
                                $('.errormsgedit').css('display','none');
                                $('.edit_txtbatch_id, .edit_txtdescription').val('');
                                $('#EditBatchPayoutModal').modal('hide');
                                BatchPayoutList();
                                SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                            },
                            error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                            }
                            });
                    }                    
                }else{
                    SpinnerHide('btnUpdateBatch','btnloadingIcon2');
                }
            });
        });

        // CLICK FUNCTION TO REMOVE SELECTED BATCH
        $(document).on('click','.btnremovebatchpayout', function(){            
            var _token = $("input[name=token]").val(),
                batch_id = $(this).data('selectedbatchid');
                desc = $('.txtdescription').val();
            SpinnerShow('btnremovebatchpayout',batch_id);
            Swal.fire({
            title: 'Are you sure',
            text: "You want to delete Batch Payout?",
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
                        url:"{{ route('remove.batchpayout') }}",
                        data:{batch_id:batch_id,_token:_token},
                        success:function(data){
                            Swal.fire({
                                allowOutsideClick: false,
                                title:'Removed!',
                                text:'Your Batch Payout successfully Removed!',
                                icon:'success'
                            });
                            $('#BatchPayout-datatable').DataTable().ajax.reload();
                            $('.errormsg').css('display','none');
                            $('.txtdescription').val('');
                            $('#CreateBatchModal').modal('hide');                           
                            BatchPayoutList();
                            SpinnerHide('btnremovebatchpayout',batch_id);
                        },
                        error: function (textStatus, errorThrown) {
                            console.log('Err');
                            BatchPayoutList();
                            SpinnerHide('btnremovebatchpayout',batch_id);
                        }
                    });                 
                }else{
                    SpinnerHide('btnremovebatchpayout',batch_id);
                }
            });        
        });

        $(document).on('keyup','.txtdescription',function(){
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

<!-- MAIN VIEW HEADER -->

<!-- STORE DATA OBJECT -->
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">

<!-- PROGRAM SELECTION -->
<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <h1 class="page-header">Batch Payout</h1>                                  
        </div>
    </div>

    <!-- HEADER CAPTION -->
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Batch Payout</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-chart-line fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title">TOTAL CREATED BATCH PAYOUT</div>
                <div class="stats-number BatchPayout_totalamt">0.00</div>
            </div>
        </div> 
    </div>
</div>

<!-- MAIN VIEW CONTENT -->
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-success btnCreateBatch btnload"><i class="fa fa-plus"><i class="fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i></i> Create Batch</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Batch Details:</h4>
    </div>
    <div class="panel-body">
        <table id="BatchPayout-datatable" class="table table-striped display nowrap" style="width: 100%;">            
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>

<!-- MODALS -->

<!-- MODAL FOR CREATION OF BATCH -->
<div class="modal fade" id="CreateBatchModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Create Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}                            
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <label class="control-label">Description: <span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control txtdescription " rows="3" placeholder="Enter Description" required></textarea>
                                        </div>
                                    </div>                                            
                                </form>
                            </div>
                        </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a id="AddBTN" href="javascript:;" class="btn btn-success btnSubmitBatch"><i class="fa fa-check-circle"><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TO EDIT EXISTING SELECTED BATCH DATA -->
<div class="modal fade" id="EditBatchPayoutModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Edit Batch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <input type="hidden" class="edit_txtbatch_id">
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsgedit" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <label class="control-label">Description: <span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control edit_txtdescription" rows="3" placeholder="Enter Description" required></textarea>
                                        </div>
                                    </div>                                            
                                </form>
                            </div>
                        </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a id="AddBTN" href="javascript:;" class="btn btn-success btnUpdateBatch"><i class="fa fa-check-circle"><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i></i> Update</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection