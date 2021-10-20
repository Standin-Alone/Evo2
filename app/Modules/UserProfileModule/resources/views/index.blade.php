@extends('global.base')
@section('title', "User | Profile")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

    {{-- External CSS --}}
    @include('UserProfileModule::components.css.css')
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
    @include('UserProfileModule::components.js.js')

    {{-- Profile Datatable --}}
    @include('UserProfileModule::components.js.datatables.profile_datatable')

    {{-- Validation --}}
    @include('UserProfileModule::components.js.validation.update_user_info')

    {{-- Change password --}}
    @include('UserProfileModule::components.js.validation.change_password')

@endsection




<script>
    
</script>






@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{route('user.profile')}}">User profile</a></li>
</ol>

<div class="row mt-5">
    <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">EDIT INFO</h4>
            </div>
            <div class="panel-body">
                {{-- <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
                    <i class='fa fa-plus'></i> Add New
                </button> --}}

                <form id="user_profile_info" method="POST" action="{{route('user.profile_info')}}" class="margin-bottom-0">
                    {{ csrf_field() }}
                    {{ method_field("PATCH") }}

                    <span class="error_info"></span>
                    <div class="form-group m-b-15">
                        <label for=""><span class="text-danger">*</span> EMAIL</label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" />
                        <span class="error_msg"></span>
                    </div>

                    <div class="form-group m-b-15">
                        <label for=""><span class="text-danger">*</span> CONTACT NO.</label>
                        <input type="number" name="contact" class="form-control form-control-lg" placeholder="Contact No." />
                        {{-- <span class="error_msg"></span> --}}
                    </div>

                    <div class="">
                    <button type="submit" id="submit-btn" class="btn btn-success btn-block btn-lg btn-prof" id="btn_info_load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Updating Info">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">CHANGE PASSWORD</h4>
            </div>
            <div class="panel-body">
                {{-- <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
                    <i class='fa fa-plus'></i> Add New
                </button> --}}
    
                <form id="user_profile_password" method="POST" action="{{route('user.profile_password')}}" class="margin-bottom-0">
                    {{ csrf_field() }}
                    {{ method_field("PATCH") }}
    
                    <span class="error_password"></span>
                    <div class="form-group m-b-15">
                        <label for=""><span class="text-danger">*</span> CURRENT PASSWORD</label>
                        <input type="password" name="current_password" class="form-control form-control-lg" placeholder="Current Password" />
                        <span class="error_msg"></span>
                    </div>
    
                    <div class="form-group m-b-15">
                        <label for=""><span class="text-danger">*</span> NEW PASSWORD</label>
                        <input type="password" name="new_password" class="form-control form-control-lg" placeholder="New Password" />
                        <span class="error_msg"></span>
                    </div>
    
                    <div class="form-group m-b-15">
                        <label for=""><span class="text-danger">*</span> CONFIRM PASSWORD</label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Confirm Password" />
                        <span class="error_msg"></span>
                    </div>
    
                    <div class="">
                        <button type="submit" id="submit-btn" class="btn btn-success btn-block btn-lg btn-pass">CHANGE PASSWORD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">USER</h4>
            </div>
            <div class="panel-body">
                {{-- <button type='button' class='btn btn-lime'data-toggle='modal' data-target='#AddModal' >
                    <i class='fa fa-plus'></i> Add New
                </button> --}}
                <br>
                <div class="card-body text-center">
                  <img class="avatar rounded-circle" src="{{url('assets/img/images/profile/profile-user.png')}}" alt="" /> 
                  <h4 class="card-title">{{session()->get('first_name')}} {{session()->get('last_name')}}</h4>
                  <h6 class="card-subtitle mb-2 text-muted">{{session()->get('user_region_name')}}</h6>
                  <p class="card-text"></p>
                </div>
                <p></p>
                <table id="profile-datatable" class="table table-bordered table-hover mt-5 mb-5 text-center display responsive nowrap" style="width:100%;">
                    <thead class="table-header">
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
            </div>
        </div>
    </div>
</div>
@endsection