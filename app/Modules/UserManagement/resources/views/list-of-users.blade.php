@extends('global.base')
@section('title', "User Management")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />
    
    {{-- External CSS --}}
    @include('UserManagement::components.css.css')
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
	<script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
	<script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
	<script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
	<script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}"></script>
    
    {{-- External CSS --}}
    @include('UserManagement::components.js.js')

    {{-- List of User Datatable --}}
    @include('UserManagement::components.js.datatables.list_of_users_datatable')
    
    {{-- Modal Datatable --}}
    @include('UserManagement::components.js.datatables.modal_datatable')

    {{-- Validation --}}
    @include('UserManagement::components.js.validation.add_modal_validation')

    @include('UserManagement::components.js.btn_permission')
@endsection




<script>
    
</script>





@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">List of Users</li>
</ol>
<!-- end breadcrumb -->

<!-- begin panel -->
<div class="panel panel-inverse mt-5">
    <div class="panel-heading">
        @foreach (session()->get('programs_ids') as $prog_id)
        <input type="hidden" id="detected_program" name="detected_program" value="{{$prog_id}}" />
        @endforeach
        <button type="button" id="add-btn" name="add_role_btn" class="btn btn-lime" data-toggle="modal" data-target="#AddModal" style="font-size= 14px;">
            <i class="fa fa-plus"></i> ADD ROLE
        </button>
        {{-- <h4 class="panel-title">List of Users</h4> --}}
        {{-- <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div> --}}
    </div>
    <div class="panel-body">
        <div class="row">
            @include('UserManagement::components.filter_cards')
        </div>
        <br>
        <br><br>
        <table id="user-datatable" class="table table-striped table-bordered table-hover text-center" style="width:100%;">            
            <thead class="table-header">
                <tr>                    
                    <th>FULLNAME</th>
                    <th>AGENCY</th>
                    <th>REGION</th> 
                    {{-- <th>Province</th>       --}}
                    <th>PROGRAM</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

         <!-- #modal-view -->
         <input type="hidden" id="ud" value="">
         <div class="modal fade" id="ViewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="width:100%;">
                    <div class="modal-header" style="background-color: #008a8a;">
                        <h4 class="modal-title" style="color: white">INTERVENTION PROGRAM</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <table id="interv-datatable"class="table table-bordered table-hover mt-5 mb-5 text-center" style="width:100%;">
                            <thead  class="table-header">
                              <tr>
                                <th scope="col">PROGRAM</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">CONTACT NO.</th>
                                <th scope="col">ROLE</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- #modal-add -->
        <div class="modal fade" id="AddModal">
            <div class="modal-dialog modal-lg">
                <form id="add_role_form" method="POST" route="">
                    {{ csrf_field() }}
                    <span class="error_form"></span>

                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#6C9738;">
                            <h4 class="modal-title" style="color: white;">ADD NEW USER ROLE:</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <div class="col-lg-12">
                                <span class="error_form"></span>
                                <div class="form-group">
                                    <label><span class="text-danger">*</span> USER</label>
                                    <select class="form-control" name="select_user" id="select_user">
                                        <option value="">-- Select user --</option>
                                        @foreach ($users as $u)
                                            @if (session()->get('uuid') != $u->user_id)
                                                <option value="{{$u->user_id}}">{{$u->last_name}}, {{$u->first_name}} {{$u->middle_name}} {{$u->ext_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>

                                <div class="form-group">
                                    <label><span class="text-danger">*</span> ROLE</label>
                                    <select class="form-control" name="select_role" id="select_role">
                                        <option value="">-- Select role --</option>
                                        @foreach ($roles as $r)
                                            <option value="{{$r->role_id}}">{{$r->role}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>

                                <div class="form-group">
                                    <label><span class="text-danger">*</span> PROGRAM</label>
                                    <select class="form-control" name="select_program" id="select_program">
                                        <option value="">-- Select program --</option>
                                        @foreach ($programs as $p)
                                            <option value="{{$p->program_id}}">{{$p->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>
                            </div>
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" class="btn btn-lime">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end panel -->
@endsection