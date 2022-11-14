@extends('global.base')
@section('title', "Program Item Categories Management")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{ url('assets/plugins/gritter/css/jquery.gritter.css') }}assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="{{ url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
	<link href="{{ url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection


{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="{{ url('assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
	<script src="{{ url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
	<script src="{{ url('assets/js/demo/ui-modal-notification.demo.min.js') }}"></script>
    <script src="{{ url('assets/plugins/DataTables/media/js/jquery.dataTables.js') }}"></script>
	<script src="{{ url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js') }}"></script>
	<script src="{{ url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ url('assets/js/demo/table-manage-default.demo.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('ProgramItemCategoriesManagement::js.js')
@endsection



@section('content')
<!-- begin page-header -->
<h1 class="page-header">Program Item Categories Management <small>setup for categories</small></h1>
<!-- end page-header -->

<!-- begin panel -->
<div class="panel  ">
    <div class="panel-heading gradient-bg">
        <h3 class="panel-title text-light">Item Categories</h3>
    </div>
    <div class="panel-body">
        <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddCategoryModal' >
            <i class='fa fa-plus'></i> Add New
        </button>
        <br>
        <br><br>
        <table id="load-table" class="table table-striped" style="width:100%">                     
        </table>


        <!-- #modal-add -->
        <div class="modal fade" id="SetProgramModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="SetProgramCategoryForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#6C9738;">
                            <h4 class="modal-title" style="color: white">
                                Set Program 
                                <br>                                
                                <span class="text-light"  id="category-name"></span>
                            </h4>
                            
                            
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            <input type="text" class="hide" id="category_id" name="fertilizer_category_id">
                            {{--modal body start--}}       
                                <div class="program-category-container" style="display:none">
                                    <div class="col-md-12">
                                        <label>Program</label>
                                        <select name="program_id" id="program-category-select" class="form-control">                                
                                        </select>
                                    </div>           
                                </div>
                                {{-- TABLE FOR SUB CATEGORY --}}
                                <div class="load-registered-program-table-container" style="display:none">
                                    <table class="table table-bordered" id="load-registered-program-table" style="width:100%;">

                                    </table>  
                                </div>
                                        
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-lime save-btn-for-category" style="display:none">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


         <!-- #modal-view -->
         <div class="modal fade" id="SetProgramSubCategoryModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="SetProgramSubCategoryForm" method="POST">
                    @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">Set Program for Sub Category
                            <br>
                            <span class="text-light"  id="sub-category-name"></span>
                        </h4>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <input type="text" class="hide" id="sub_category_id" name="fertilizer_sub_category_id">
                        <div class="col-md-12">
                            <label>Program</label>
                            <select name="program_id" id="program-select" class="form-control">                                
                            </select>
                        </div>                        

                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                        <button  type="submit" class="btn btn-success" >Add Program</button>
                    </div>
                </div>
                </form>
            </div>
        </div>


        <!-- #modal-Add Category -->
        <div class="modal fade" id="AddCategoryModal">
            <div class="modal-dialog" style="max-width: 30%">
                <form id="AddCategoryForm" method="POST" >
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f59c1a">
                            <h4 class="modal-title" style="color: white">Add Category</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}  
                            <div class="col-md-12 m-b-10">
                                <label>Category name</label>
                                <input type="text" class="form-control" name="category_name" placeholder="category name...">
                            </div>                

                            <div class="col-md-12 m-b-10">
                                <label>Program</label>
                                <select class="form-control " id="add-program-category-select" name="program" >
                                    @foreach ($get_programs as $item)
                                        <option value="{{ $item->program_id }}">{{$item->title }} ({{$item->shortname }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-success">Add </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- end panel -->
@endsection