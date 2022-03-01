@extends('global.base')
@section('title', 'Supplier Program')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="{{ url('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet" />
    <link href="{{ url('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />

    {{-- External CSS --}}
    @include('SupplierProgramModule::components.css.css')
@endsection




{{-- import in this section your javascript files --}}
@section('page-js')
    <script src="{{ url('assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/js/demo/ui-modal-notification.demo.min.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('assets/js/demo/table-manage-default.demo.min.js') }}"></script>
    <script src="{{ url('assets/plugins/switchery/switchery.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>

    {{-- External CSS --}}
    @include('SupplierProgramModule::components.js.js')

    {{-- List of Supplier Datatable --}}
    @include('SupplierProgramModule::components.js.datatables.list_of_supplier_datatable')

    {{-- Supplier Program Datatable --}}
    @include('SupplierProgramModule::components.js.datatables.supplier_program_datatable')

    {{-- Modal Datatable --}}
    {{-- @include('ProgramSrnModule::components.js.datatables.list_of_registered_srn_under_in_program_datatable') --}}

    {{-- Validation --}}
    {{-- @include('UserManagement::components.js.validation.add_modal_validation') --}}

    {{-- @include('UserManagement::components.js.btn_permission') --}}
    <script>

    </script>
@endsection







@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="input-group">
                <h1 class="page-header">SUPPLIER PROGRAM OVERVIEW</h1>
            </div>
        </div>
        <div class="col-md-4">
            <ol class="breadcrumb pull-right">
                <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
                <li class="breadcrumb-item active">Supplier Program Module</li>
            </ol>
        </div>
    </div>

    <div class="panel panel-inverse mt-2">
        <div class="panel-heading">
            LIST OF SUPPLIERS
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                        class="fa fa-expand mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                        class="fa fa-redo mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                        class="fa fa-minus mt-1"></i></a>
            </div>
        </div>
        <div class="panel-body">
            {{-- <div class="row mt-3">
            @include('ProgramSrnModule::components.filter_cards.filter_cards')
        </div> --}}
            <br>
            <br><br>
            <table id="list-of-supplier-datatable" class="table table-bordered table-hover text-center" style="width:100%;">
                <thead class="table-header">
                    <tr>
                        <th style="color: white !important; width: 100px !important;"> SUPPLIER NAME </th>
                        <th style="color: white !important; width: 100px !important;"> ADDRESS </th>
                        <th style="color: white !important; width: 100px !important;"> MAIN BRANCH </th>
                        <th style="color: white !important; width: 100px !important;"> ACCOUNT NO. </th>
                        <th style="color: white !important; width: 100px !important;"> EMAIL ACCOUNT </th>
                        <th style="color: white !important; width: 100px !important;"> CONTACT NO. </th>
                        <th style="color: white !important; width: 100px !important;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        {{-- Setup-modal-view --}}
        <div class="modal fade" id="setup_modal">
            <div class="modal-dialog modal-lg">
                <form id="setup_program" method="POST">
                    {{ csrf_field() }}
                    <span class="error_form"></span>

                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#21a8dd;">
                            <h4 class="modal-title" style="color: white;">SETUP PROGRAM:</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                style="color: white">×</button>
                        </div>
                        <div id="setup_modal_body" class="modal-body">
                            {{-- modal body start --}}
                            <div class="col-lg-12">
                                <span class="error_form"></span>
                                <div class="form-group">
                                    <label><span class="text-danger">*</span> PROGRAM </label>
                                    <select class="form-control" name="select_program" id="select_program">
                                        <option value="">-- Select program --</option>
                                        @foreach ($programs as $p)
                                            <option value="{{ $p->program_id }}">{{ $p->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>

                                <div class="form-group">
                                    <label><span class="text-danger">*</span> PROGRAM ITEMS </label>
                                    <br>
                                    <select class="form-control select_program_item" name="select_program_item"
                                        id="select_program_item" multiple>
                                        @foreach ($program_items as $pi)
                                            <option value="{{ $pi->item_id }}">{{ $pi->item_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>
                            </div>
                            {{-- modal body end --}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger btn_close" data-dismiss="modal">CLOSE</a>
                            <button type="submit" class="btn btn-outline-info"
                                data-supplier_id="{{ $supplier_id }}">PREVIEW</button>
                            {{-- <button type="button" class="btn btn-outline-info" data-id="" data-toggle="modal" data-target="#preview_setup_modal">PREVIEW</button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- preview-setup-program-and-program-items-modal --}}
        <div class="modal fade" id="preview_setup_modal">
            <div class="modal-dialog modal-lg">
                <form id="create_program" method="POST">
                    {{ csrf_field() }}
                    <span class="error_form"></span>
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#21a8dd;">
                            <h4 class="modal-title" style="color: white;">PREVIEW PROGRAM:</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                style="color: white">×</button>
                        </div>
                        <div id="preview_modal_body" class="modal-body">
                            {{-- the content are append in the JS --}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" class="btn btn-outline-success">SAVE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <br>

    <div class="panel panel-inverse mt-2">
        <div class="panel-heading">
            LIST OF SUPPLIER PROGRAMS
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                        class="fa fa-expand mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                        class="fa fa-redo mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                        class="fa fa-minus mt-1"></i></a>
            </div>
        </div>
        <div class="panel-body">
            {{-- <div class="row mt-3">
            @include('ProgramSrnModule::components.filter_cards.filter_cards')
        </div> --}}
            <br>
            <br><br>
            <table id="supplier-program-datatable" class="table table-bordered table-hover text-center" style="width:100%;">
                <thead class="table-header">
                    <tr>
                        <th style="color: white !important; width: 100px !important;"> SUPPLIER NAME </th>
                        <th style="color: white !important; width: 100px !important;"> PROGRAM </th>
                        <th style="color: white !important; width: 100px !important;"> PROGRAM ITEM </th>
                        <th style="color: white !important; width: 100px !important;"> UNIT MEASURE </th>
                        <th style="color: white !important; width: 100px !important;"> CEILING AMOUNT </th>
                        <th style="color: white !important; width: 100px !important;"> DATE CREATED </th>
                        <th style="color: white !important; width: 100px !important;"> STATUS </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
