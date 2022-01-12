@extends('global.base')
@section('title', "Supplier Program Setup")

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
<style>
    .container { margin-top: 5%; }

    .datatable-editor-action .mdi {
    margin-right: 5px;
        color: #666;
    }
</style>
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

        SupplierProgramList();
        
        function SupplierProgramList(){             
            var table = $('#SupplierProgramList-datatable').DataTable({ 
                destroy: true,
                processing: true,
                serverSide: true,
                responsive: true,   
                scrollY: "350px",
                ajax: "{{ route('get.SupplierProgramList') }}",
                columns: [
                    {data: 'supplier_name', name: 'supplier_name', title:'SUPPLIER NAME'}, 
                    {data: 'address', name: 'address', title:'ADDRESS'}, 
                    {data: 'supplier_group', name: 'supplier_group', title:'MAIN BRANCH'}, 
                    {data: 'bank_account_no', name: 'bank_account_no', title:'ACCOUNT NO.'},
                    {data: 'email', name: 'email', title:'EMAIL ACCOUNT'},
                    {data: 'contact', name: 'contact', title:'CONTACT NO.'},
                    {data: 'supplier_id', name: 'supplier_id',  title:'ACTION',
                        render: function(data, type, row) {
                            return  '<a href="javascript:;" data-selectedsupplierid="'+row.supplier_id+'" class="btn btn-xs btn-outline-info Remove_SupplierProfile_Button" data-toggle="tooltip" data-placement="top" title="Remove Details"><span class="fa fa-cog"></span><i class="fas fa-spinner fa-spin '+row.supplier_id+' pull-left m-r-10" style="display: none;"></i> Setup</a>';
                        }
                    }
                ],
            });
        }


    });        
</script>

@endsection

@section('content')
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Supplier Program Setup</h1>                       
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Supplier Program Setup</li>
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
        <h4 class="panel-title panel-title-main btn btn-xs" style="font-weight:normal !important;"><span class="fa fa-th-large"></span> DETAILS:</h4>
        <h4 class="panel-title panel-title-sub btn btn-xs" style="font-weight:normal !important;display:none;">:</h4> 
    </div>
    <div class="panel-body">
        <table id="SupplierProgramList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>
@endsection