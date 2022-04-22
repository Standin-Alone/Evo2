@extends('global.base')
@section('title', 'Program Items')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="{{ url('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') }}" rel="stylesheet" />

    <link href="{{ url('assets/plugins/lightbox/css/lightbox.css') }}" rel="stylesheet" />

    {{-- External CSS --}}
    @include('ProgramItemsModule::components.css.css')
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

  	<script src="{{ url('assets/plugins/lightbox/js/lightbox.min.js') }}"></script>

    {{-- External JS --}}
    @include('ProgramItemsModule::components.js.js')

    {{-- List of Program Items Datatable --}}
    @include('ProgramItemsModule::components.js.datatables.list_of_program_items_datatable')

    {{-- Create Modal Js --}}
    @include('ProgramItemsModule::components.js.create_modal_js')

    {{-- Update Modal JS --}}
    @include('ProgramItemsModule::components.js.update_modal_js')
@endsection







@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="input-group">
                <h1 class="page-header">PROGRAM ITEM</h1>
            </div>
        </div>
        <div class="col-md-4">
            <ol class="breadcrumb pull-right">
                <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
                <li class="breadcrumb-item active">Program Item Module</li>
            </ol>
        </div>
    </div>

    {{-- <button type="button" id="" name="add_role_btn" class="btn btn-xs btn-lime" data-toggle="modal"
        data-target="#create_new_program_item_modal">
        <img src="{{ url('/assets/img/images/product_presentation.png') }}" width="50%" height:="50%" alt="" srcset="">
    </button> --}}

    {{-- <img src="{{ url('/assets/img/images/product_presentation.png') }}" width="20%" height:="20%" alt="" srcset=""> --}}

    <!-- begin panel -->
    <div class="panel panel-inverse mt-2">
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                        class="fa fa-expand mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                        class="fa fa-redo mt-1"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                        class="fa fa-minus mt-1"></i></a>
            </div>
            {{-- @foreach (session()->get('programs_ids') as $prog_id)
            <input type="hidden" id="detected_program" name="detected_program" value="{{$prog_id}}" />
        @endforeach --}}
            <button type="button" id="" name="add_role_btn" class="btn btn-xs btn-lime" data-toggle="modal"
                data-target="#create_new_program_item_modal">
                <i class="fa fa-plus"></i> CREATE PROGRAM ITEM
            </button>
        </div>
        <div class="panel-body">
            <br>
            <table id="list-of-program-item-datatable" class="table table-bordered table-hover text-center"
                style="width:100%;">
                <thead class="table-header">
                    <tr>
                        <th style="color: white !important; width: 100px !important;"> ITEM IMAGE </th>
                        <th style="color: white !important; width: 100px !important;"> ITEM NAME </th>
                        <th style="color: white !important; width: 100px !important;"> UNIT MEASURE </th>
                        {{-- <th style="color: white !important; width: 100px !important;"> CEILING AMOUNT </th> --}}
                        <th style="color: white !important; width: 100px !important;"> REGION </th>
                        {{-- <th style="color: white !important; width: 100px !important;"> PROVINCE </th> --}}
                        <th style="color: white !important; width: 100px !important;"> STATUS </th>
                        <th style="color: white !important; width: 100px !important;"> DATE CREATED </th>
                        <th style="color: white !important; width: 100px !important;"> ACTION </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- #create_new_program_item_modal-->
    @include('ProgramItemsModule::components.modal.create_new_program_item_modal')

    {{-- #update_program_item_modal--}}
    @include('ProgramItemsModule::components.modal.update_program_item_modal')

@endsection
