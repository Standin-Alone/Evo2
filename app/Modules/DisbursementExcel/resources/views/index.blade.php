@extends('global.base')
@section('title', "Disbursement Excel")

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
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/pgv/backend-script.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        // CALL DATATABLE
        getProvinceList();

        // DISPLAY DATATABLE
        function DisbursementListExcel(selected_prv_code){
                var table = $('#DisbursementListExcel-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    scrollY: "200px",
                    "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                    dom: 'Bfrtip',
                        buttons: [
                            'csv', 'excel', 'pdf'
                        ],
                    ajax: "{{ route('get.DisbursementListExcel') }}" + '?selected_prv_code=' + selected_prv_code,
                    columns: [ 
                        {data: 'date_uploaded', name: 'date_uploaded', title: 'DATE UPLOADED'},                        
                        {data: 'data_source', name: 'data_source', title: 'DATA SOURCE'},
                        {data: 'fintech_provider', name: 'fintech_provider', title: 'FINTECH PROVIDER'},
                        {data: 'rsbsa_no', name: 'rsbsa_no', title: 'RSBSA NO.'},
                        {data: 'first_name', name: 'first_name', title: 'FIRST NAME'},
                        {data: 'middle_name', name: 'middle_name', title: 'MIDDLE NAME'},
                        {data: 'last_name', name: 'last_name', title: 'LAST NAME'},
                        {data: 'ext_name', name: 'ext_name', title: 'EXTENTION NAME'},
                        {data: 'id_number', name: 'id_number', title: 'ID NUMBER'},
                        {data: 'gov_id_type', name: 'gov_id_type', title: 'GOVERNMENT ID TYPE'},
                        {data: 'street_purok', name: 'street_purok', title: 'STREET-PUROK'},
                        {data: 'bgy_code', name: 'bgy_code', title: 'BARANGAY CODE'},
                        {data: 'barangay', name: 'barangay', title: 'BARANGAY NAME'},
                        {data: 'mun_code', name: 'mun_code', title: 'MUNICIPALITY CODE'},
                        {data: 'municipality', name: 'municipality', title: 'MUNICIPALITY NAME'},
                        {data: 'district', name: 'district', title: 'DISTRICT'},
                        {data: 'prov_code', name: 'prov_code', title: 'PROVINCE CODE'},
                        {data: 'province', name: 'province', title: 'PROVINCE NAME'},
                        {data: 'reg_code', name: 'reg_code', title: 'REGION CODE'},
                        {data: 'region', name: 'region', title: 'REGION NAME'},
                        {data: 'birthdate', name: 'birthdate', title: 'BIRTHDATE'},
                        {data: 'place_of_birth', name: 'place_of_birth', title: 'PLACE OF BIRTH'},
                        {data: 'mobile_no', name: 'mobile_no', title: 'MOBILE NO.'},
                        {data: 'sex', name: 'sex', title: 'SEX'},
                        {data: 'nationality', name: 'nationality', title: 'NATIONALITY'},
                        {data: 'profession', name: 'profession', title: 'PROFESSION'},
                        {data: 'sourceoffunds', name: 'sourceoffunds', title: 'SOURCE OF FUNDS'},
                        {data: 'mothers_maiden_name', name: 'mothers_maiden_name', title: 'MOTHERS MAIDEN NAME'},
                        {data: 'no_parcel', name: 'no_parcel', title: 'NO. PARCEL'},
                        {data: 'total_farm_area', name: 'total_farm_area', title: 'TOTAL FARM AREA'},
                        {data: 'account_number', name: 'account_number', title: 'ACCOUNT NUMBER'},
                        {data: 'remarks', name: 'remarks', title: 'REMARKS'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL AMOUNT'},
                        {data: 'uploaded_by_user_fullname', name: 'uploaded_by_user_fullname', title: 'UPLOADED BY'},                        
                    ],
                    rowGroup: {
                        dataSrc: function (data) {
                                return "<span>{{ session('Default_Program') }}</span>";
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
                        }  
                    });
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

            $(document).on('change','.selectProvince',function(){
                var selected_prv_code = $(this).val();
                SpinnerShow('selectProvince',selected_prv_code);
                var table = $('#DisbursementList-datatable').DataTable();
                table.clear().draw();  
                $('.selectedbatchall').prop('checked',false); 
                $('.Disbursement_totalselectedamt').val(0);
                $('#Disbursement_selectedamt').val(0);  
                $('.Disbursement_totalselectedamt').html("0.00");   
                DisbursementListExcel(selected_prv_code);
                SpinnerHide('selectProvince',selected_prv_code);
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
            <h1 class="page-header">Disbursement Excel</h1>                                  
        </div>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li> 
            <li class="breadcrumb-item"><a href="{{route('DisbursementModule.index')}}">Disbursement Module</a></li>
            <li class="breadcrumb-item active">Disbursement Excel</li>
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
    </select><br>
    <table id="DisbursementListExcel-datatable" class="table table-striped display nowrap" style="width: 100%;">
        <thead style="background-color: #008a8a;"></thead>
    </table>
    </div>
</div>
<!-- end panel -->
@endsection