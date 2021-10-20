@extends('global.base')
@section('title', "Budget")

{{--  import in this section your css files--}}
@section('page-css')
<link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
<link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
<link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
<script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
<script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
<script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
<script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
<script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}" ></script> 

{{-- External JS --}}
@include('BudgetModule::components.js.js')

{{-- Dynamic dropdown --}}
@include('BudgetModule::components.js.dropdown.dynamic_dropdown')

{{-- Fund encoding - ORS validation form --}}
@include('BudgetModule::components.js.validation.add_funds')

{{-- Add Comma, 2 decimals, and Peso sign --}}
@include('BudgetModule::components.js.conversion.number_format_conversion')

@include('BudgetModule::components.js.btn_permission')
@endsection




<script>

</script>






@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    {{-- <li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li> --}}
    <li class="breadcrumb-item active">Fund Source Encoding</li>
</ol>

<!-- begin page-header -->
{{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}

<!-- begin panel -->
<div class="panel panel-inverse mt-5">
    <div class="panel-heading">
        <h4 class="panel-title">FUND SOURCE ENCODING</h4>
    </div>
    <div class="panel-body">
        <form id="fund_encoding_ors" method="POST" action="{{route('submit_encoding_form')}}" class="form-control-with-bg">
            {{ csrf_field() }}
            <span class="error_form"></span>
            <!-- begin row -->
            <div class="row">
                <!-- begin col-8 -->
                <div class="col-md-8 offset-md-2">
                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> PROGRAM</label>
                        <div class="col-md-6">
                            <select class="form-control" name="select_program" id="select_program">
                                <option value="">-- Select program --</option>
                                    @foreach ($programs as $program)
                                        <option value="{{$program->program_id}}">{{$program->title}}</option>
                                    @endforeach
                            </select>
                            <span class="error_msg"></span>
                        </div>
                    </div>

                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> UACS</label>
                        <div class="col-md-6">
                            <input type="number" name="uacs" placeholder="input uacs..." class="form-control" />
                            <span class="error_msg"></span>
                        </div>
                    </div>

                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> GFI</label>
                        <div class="col-md-6">
                            <select class="form-control" name="select_gfi" id="select_gfi">
                                <option value="">-- Select GFI --</option>
                                <option value="LandBank">LandBank</option>
                                <option value="DBP">DBP</option>
                            </select>
                            <span class="error_msg"></span>
                        </div>
                    </div>

                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> REGION</label>
                        <div class="col-md-6">
                            <select class="form-control" name="select_region" id="select_region">
                                <option value="">-- Select region --</option>
                                    @foreach ($regions as $region)
                                        <option value="{{$region->reg_code}}">{{$region->reg_name}}</option>
                                    @endforeach
                            </select>
                            <span class="error_msg"></span>
                        </div>
                    </div>

                    {{-- <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label">Province</label>
                        <div class="col-md-6">
                            <select class="form-control" name="select_province" id="select_province">
                            </select>
                            <span class="error_msg"></span>
                        </div>
                    </div> --}}

                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> NO. OF FARMERS</label>
                        <div class="col-md-6">
                            <input id="no_of_farmers" type="number" name="no_of_farmers" placeholder="" class="form-control" />
                            <span class="error_msg"></span>
                        </div>
                    </div>

                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> AMOUNT</label>
                        <div class="col-md-6">
                            <input id="amount" type="text" name="amount" placeholder="0.00" class="form-control" />
                            <span class="error_msg"></span>
                        </div>
                    </div>

                    <div class="form-group row m-b-10">
                        <label class="col-md-3 text-md-right col-form-label"><span class="text-danger">*</span> PARTICULARS</label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="3" name="particulars"></textarea>
                            <span class="error_msg"></span>
                            <br>
                            <button type="submit" name="create_fund_btn" class="btn btn-block btn-danger" disabled>SUBMIT</button>
                            <span class='text-danger alert_note'></span> 
                        </div>
                    </div>
                </div>
                <!-- end col-8 -->
            </div>
            <!-- end row -->
        </form>
    </div>
</div>
<!-- end panel -->
@endsection