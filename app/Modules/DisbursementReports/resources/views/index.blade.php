@extends('global.base')
@section('title', "Disbursement Reports")

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
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
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script src="assets/pgv/backend-script.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('.input-daterange').datepicker({
            todayBtn:'linked',
            format:'yyyy-mm-dd',
            autoclose:true
        });

        // CALL DATATABLE
        getProvinceList();

        // DISPLAY DATATABLE
        function DisbursementListReports(selected_prv_code,filterby,from_date,to_date){
                
                datatable_url = "";
                if(filterby == "overall"){
                    datatable_url = "{!! route('get.DisbursementListReports_overall') !!}";
                }else if(filterby == "pending"){
                    datatable_url = "{{ route('get.DisbursementListReports_pending') }}";
                }else if(filterby == "approved"){
                    datatable_url = "{{ route('get.DisbursementListReports_approved') }}";
                }
                var table = $('#DisbursementListReports-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    scrollY: "200px",
                    "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                    dom: 'Bfrtip',
                        buttons: [
                            'csv', 'excel', 'pdf'
                        ],
                    ajax: {url: datatable_url,
                            type: "get",
                            data:{selected_prv_code:selected_prv_code,from_date:from_date,to_date:to_date}},
                    columns: [ 
                        {data: 'date_uploaded', name: 'date_uploaded', title:'TRANSACTION DATE'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL AMOUNT'},
                        {data: 'region', name: 'region', title:'REGION'},
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'municipality', name: 'municipality', title:'MUNICIPALITY'},
                        {data: 'barangay', name: 'barangay', title:'BARANGAY'},
                        {data: 'birthdate', name: 'birthdate', title:'BIRTHDATE'},
                        {data: 'mobile_no', name: 'mobile_no', title:'MOBILE NO.'},
                        {data: 'sex', name: 'sex', title:'SEX'},
                    ],
                        footerCallback: function (row, data, start, end, display) { 
                        var TotalAmount = 0;                                        
                            for (var i = 0; i < data.length; i++) {
                                var dataval = data[i]['amount'];
                                TotalAmount += parseInt(dataval);
                            }
                            $('.Disbursement_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount )); 
                    }  
                    }).ajax.reload();
                    
            }

            function getProvinceList(){
                $.ajax({
                    type:'get',
                    url:"{{ route('get.DisbursementApprovalProvinceList') }}",
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

            $(document).on('click','.btnrefreshTable',function(){
                var selected_prv_code = $('.selectProvince').val(),
                    filterby = "",
                    filter1 = $('#customRadio1').is(':checked'),
                    filter2 = $('#customRadio2').is(':checked'),
                    filter3 = $('#customRadio3').is(':checked');
                    if(filter1 === true) {filterby = "overall";}
                    if(filter2 === true) {filterby = "pending";}
                    if(filter3 === true) {filterby = "approved";}

                var from_date = $('#from_date').val(),
                    to_date = $('#to_date').val();
                SpinnerShow('btnrefreshTable','btnloadingIcon3'); 
                $('.selectedbatchall').prop('checked',false); 
                $('.Disbursement_totalselectedamt').val(0);
                $('#Disbursement_selectedamt').val(0);  
                $('.Disbursement_totalselectedamt').html("0.00");   
                DisbursementListReports(selected_prv_code,filterby,from_date,to_date);
                SpinnerHide('btnrefreshTable','btnloadingIcon3');  
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
            <h1 class="page-header">Disbursement Reports</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li> 
            <li class="breadcrumb-item active">Disbursement Reports</li>
        </ol>   
    </div>
</div> 
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Disbursement Details:</h4>
    </div>
    <div class="panel-body">
    <select id="default_DisbursementProvince" class="form-control selectProvince" name="DisbursementProvince" data-size="10" data-style="btn-white" value="{{ old('DisbursementProvince') }}">
        <option value="" selected>Select Province</option>
    </select>
    
    <div class="col-md-12 pt-2">
        <div class="row">
            <div class="form-check form-check-inline m-t-15 ">
                <input class="form-check-input" type="radio" id="customRadio1" name="inlineRadio" checked>
                <label class="form-check-label" for="customRadio1"> View ALL</label>
            </div>
            <div class="form-check form-check-inline m-t-15">
                <input class="form-check-input" type="radio" id="customRadio2" name="inlineRadio">
                <label class="form-check-label" for="customRadio2"> For Approval</label>
            </div>
            <div class="form-check form-check-inline m-t-15">
                <input class="form-check-input" type="radio" id="customRadio3" name="inlineRadio">
                <label class="form-check-label" for="customRadio3"> Approved</label>
            </div>
            <div class="row input-daterange">
                <div class="col-md-6">
                    <label class="form-label" for="from_date">Start Date:</label>
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="to_date">End Date:</label>
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                </div>
            </div>
            
            <div class="col-md-4 m-t-25">
                <!-- <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button> -->
                <a href="javascript:;" class="btn btn-success btnrefreshTable"><i class="fa fa-sync-alt"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Refresh Table</a>
            </div>
        </div>
        
    
    </div>
    
    <br>
    <table id="DisbursementListReports-datatable" class="table table-striped display nowrap" style="width: 100%;">
        <thead style="background-color: #008a8a;"></thead>
    </table>
    <label class="pull-right mt-3">
        <div class="input-group" style="margin-bottom:-15px !important;">
            <div class="input-group-prepend">
            <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
            </div>
            <h3 class="alert alert-primary Disbursement_totalselectedamt" role="alert">0.00</h3>
        </div>
    </label>
    </div>
</div>
<!-- end panel -->
@endsection