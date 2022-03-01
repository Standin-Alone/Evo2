{{-- @extends('global.base') --}}
@section('title', 'Supplier Application and Validation')

{{-- import in this section your css files --}}
{{-- @section('page-css') --}}


    {{-- Include external JS components --}}
    {{-- @include('SupplierProfile::components.css.css') --}}
{{-- @endsection --}}




{{-- import in this section your javascript files --}}
{{-- @section('page-js') --}}


    {{-- Include external JS components --}}
    {{-- @include('SupplierProfile::components.js.js') --}}

    {{-- Datatables --}}

{{-- @endsection --}}


{{-- @section('content') --}}

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <meta charset="utf-8" />
        <title> @yield('title','Interventions Management Platform')</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        <!-- ================== BEGIN BASE CSS STYLE ================== -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="{{url('assets/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" />
        <link href="{{url('assets/plugins/bootstrap/4.0.0/css/bootstrap.min.css')}}" rel="stylesheet" />
        <link href="{{url('assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css')}}" rel="stylesheet" />
        <link href="{{url('assets/plugins/animate/animate.min.css')}}" rel="stylesheet" />
        <link href="{{url('assets/css/default/style.min.css')}}" rel="stylesheet" />
        <link href="{{url('assets/css/default/style-responsive.min.css')}}" rel="stylesheet" />
        <link href="{{url('assets/css/default/theme/default.css')}}" rel="stylesheet" id="theme" />
        {{-- <link href="{{url('assets/css/farmer-module/style.css')}}" rel="stylesheet" /> --}}

        {{-- <link href="{{ url('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" /> --}}
    
        {{-- Select2 plugin --}}
        <link href="{{ url('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <!-- ================== END BASE CSS STYLE ================== -->
        
        @include('RfoApprovalModule::components.css.supplier_profile_update_form_css')

        <!-- ================== BEGIN BASE JS ================== -->
        {{-- <script src="{{url('assets/plugins/pace/pace.min.js')}}"></script> --}}

        <title>Document</title>
    </head>
    <body>
        <!-- begin breadcrumb -->
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        {{-- <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1> --}}
        <!-- end page-header -->

        <!-- end row -->

        {{-- <div class="panel-body panel-form">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        </div> --}}

        <div class="container mt-5">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">MAIN BRANCH SUPPLIER PROFILE</h4>
                </div>
                <div class="panel-body">
                    <br>
                    <br>
                    <form id="supplier_profile_update_form" method="POST" action="" class="margin-bottom-0">
                        {{ csrf_field() }}

                        <span class="error_form"></span>

                        <div class="col-md-12">
                            @foreach ($supplier_profile as $sp) 
                               {{-- <div class="col-md-3 mb-3"> --}}
                                    {{-- <label class="form-label">First Name: <span class="text-danger">*</span></label> --}}
                                    <input class="form-control main_supplier_id" name="main_supplier_id" type="hidden" value="{{ $sp->supplier_id }}" placeholder="" />
                                    <input class="form-control main_supplier_group_id" name="main_supplier_group_id" type="hidden" value="{{ $sp->supplier_group_id }}" placeholder="">
                                    <input class="form-control rfo_approver_main_region" name="rfo_approver_main_region" type="hidden" value="{{ $sp->reg }}" placeholder="" />
                                    <input class="form-control rfo_approver_main_supplier_type" name="rfo_approver_main_supplier_type" type="hidden" value="{{ $sp->supplier_type }}" placeholder="" />
                                    {{-- <span class="error_msg"></span> --}}
                                {{-- </div> --}}
                                <div class="mb-3">
                                    <label class="form-label">Supplier Group: <span class="text-danger">*</span></label>
                                    <input class="form-control mainSupplierGroup" name="mainSupplierGroup" value="{{ $sp->group_name }}" type="text" readonly />
                                    <span class="error_msg"></span>
                                </div>  

                                <div class="mb-3">
                                    <label class="form-label">Supplier Name: <span class="text-danger">*</span></label>
                                    <input class="form-control mainSupplierName" name="mainSupplierName" value="{{ $sp->supplier_name }}" type="text" placeholder="Enter Supplier Name" />
                                    <span class="error_msg"></span>
                                </div>  

                                <div class="mb-3">
                                    <label class="form-label">Complete Address: <span class="text-danger">*</span></label>
                                    <textarea class="form-control main_complete_address" name="main_complete_address" value="{{ $sp->address }}" rows="3" placeholder="Enter Complete Address" required>{{ $sp->address }}
                                    </textarea>
                                    <span class="error_msg"></span>
                                </div>  

                                <div class="row mb-3">
                                    {{-- Region Dropdown --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Region: <span class="text-danger">*</span></label>
                                        <select id="mainSelectSupplierRegion" class="form-control mainSelectSupplierRegion" name="mainSelectSupplierRegion">
                                            <option value="" selected>-- Select --</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->reg_code }}">{{ $region->reg_name }}</option>
                                            @endforeach
                                        </select> 
                                        <span class="error_msg"></span>
                                    </div>

                                    {{-- Province Dropdown --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Province: <span class="text-danger">*</span></label>
                                        <select id="mainSelectProvince" class="form-control mainSelectProvince" name="mainSelectProvince">
                                        </select>
                                        <span class="error_msg"></span> 
                                    </div>

                                    {{-- Municipality Dropdown --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Municipality/City: <span class="text-danger">*</span></label>
                                        <select id="mainSelectCity" class="form-control mainSelectCity" name="mainSelectCity">
                                        </select> 
                                        <span class="error_msg"></span>
                                    </div>

                                    {{-- Barangay Dropdown --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Barangay: <span class="text-danger">*</span></label>
                                        <select id="mainSelectSupplierBarangay" class="form-control mainSelectSupplierBarangay" name="mainSelectSupplierBarangay">
                                        </select> 
                                        <span class="error_msg"></span>
                                    </div>
                                </div>   
                                
                                <div class="row mb-3">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Business Permit: <span class="text-danger">*</span></label>
                                        <input class="form-control main_business_permit" name="main_business_permit" type="text" placeholder="Enter Business Permit" />
                                        <span class="error_msg"></span>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Email: <span class="text-danger">*</span></label>
                                        <input class="form-control main_email" name="main_email" type="email" value="{{ $sp->email }}" placeholder="Enter Email" />
                                        <span class="error_msg"></span>
                                    </div>  

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Contact No: <span class="text-danger">*</span></label>
                                        <input class="form-control main_contact_no" name="main_contact_no" type="number" value="{{ $sp->contact }}" placeholder="Enter Contact No." />
                                        <span class="error_msg"></span>
                                    </div>  
                                </div> 

                                <div class="row mb-3">  
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">First Name: <span class="text-danger">*</span></label>
                                        <input class="form-control main_first_name" name="main_first_name" type="text" value="{{ $sp->owner_first_name }}" placeholder="Enter First Name" />
                                        <span class="error_msg"></span>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Middle Name: </label>
                                        <input class="form-control main_middle_name" name="main_middle_name" type="text" value="{{ $sp->owner_middle_name }}" placeholder="Enter Middle Name" />
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Last Name: <span class="text-danger">*</span></label>
                                        <input class="form-control main_last_name" name="main_last_name" type="text" value="{{ $sp->owner_last_name }}" placeholder="Enter Last Name" />
                                        <span class="error_msg"></span>
                                    </div>  

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Extention Name: </label>
                                        <input class="form-control main_ext_name" name="main_ext_name" type="text" value="{{ $sp->owner_ext_name }}" placeholder="Enter Extention Name" />
                                        {{-- <span class="error_msg"></span> --}}
                                    </div>                                              
                                </div> 
                            @endforeach  

                            @foreach ($supplier_profile as $sp)
                                @if ( ($sp->bank_long_name == null) && ($sp->bank_short_name == null) && ($sp->bank_account_name == null) && ($sp->owner_phone == null))
                                    <div class="row mb-3"> 
                                       
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Bank: <span class="text-danger">*</span></label>
                                            <select class="form-control select_main_owner_bank_name" name="select_main_owner_bank_name">
                                                <option value="" selected>-- Select --</option>
                                                @foreach ($banks as $b)
                                                    <option value="{{ $b->shortname }}">{{ $b->name }}</option>
                                                @endforeach  
                                            </select> 
                                            <span class="error_msg"></span>
                                        </div>
                                                                                     
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Account Name: <span class="text-danger">*</span></label>
                                            <input class="form-control main_bank_account_name" name="main_bank_account_name" type="text" value="" placeholder="Enter Bank Account Name" />
                                            <span class="error_msg"></span>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Account No.: <span class="text-danger">*</span></label>
                                            <input class="form-control main_bank_account_no" name="main_bank_account_no" type="number" value="" placeholder="Enter Bank Account Number" />
                                            <span class="error_msg"></span>
                                        </div>                                          
                                    </div>   
        
                                    <div class="mb-3">
                                        <label class="form-label">Phone No.: <span class="text-danger">*</span></label>
                                        <input class="form-control main_phone_no" name="main_phone_no" type="number" placeholder="Enter Phone No." />
                                        <span class="error_msg"></span>
                                    </div> 
                                @else
                                    <div class="row mb-3">                                                
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Bank: <span class="text-danger">*</span></label>
                                            <select class="form-control select_main_owner_bank_name" name="select_main_owner_bank_name">
                                                {{-- <option value="" selected>-- Select Bank --</option> --}}
                                                <option value="{{ $sp->short_long_name }}">{{ $sp->bank_long_name }}</option>
                                            </select> 
                                            <span class="error_msg"></span>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Account Name: <span class="text-danger">*</span></label>
                                            <input class="form-control main_bank_account_name" name="main_bank_account_name" type="text" value="{{ $sp->bank_account_name }}" placeholder="Enter Bank Account Name" />
                                            <span class="error_msg"></span>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Account No.: <span class="text-danger">*</span></label>
                                            <input class="form-control main_bank_account_no" name="main_bank_account_no" type="number" value="{{ $sp->bank_account_no }}" placeholder="Enter Bank Account Number" />
                                            <span class="error_msg"></span>
                                        </div>                                             
                                    </div>   
        
                                    <div class="mb-3">
                                        <label class="form-label">Phone No.: <span class="text-danger">*</span></label>
                                        <input class="form-control main_phone_no" name="main_phone_no" type="number" value="{{ $sp->owner_phone }}" placeholder="Enter Phone No." />
                                        <span class="error_msg"></span>
                                    </div>
                                @endif
                            @endforeach                                                                  
                        </div>
                        <hr>
                        <button type="submit" id="btnUpdateProfile" class="btn btn-outline-info float-right">UPDATE</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================== BEGIN BASE JS ================== -->
        <script src="{{url('assets/plugins/jquery/jquery-3.2.1.min.js')}}"></script>
        <script src="{{url('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <script src="{{url('assets/plugins/bootstrap/4.0.0/js/bootstrap.bundle.min.js')}}"></script>
        <!--[if lt IE 9]>
            <script src="../assets/crossbrowserjs/html5shiv.js"></script>
            <script src="../assets/crossbrowserjs/respond.min.js"></script>
            <script src="../assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]-->
        <script src="{{url('assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
        {{-- <script src="{{url('assets/plugins/js-cookie/js.cookie.js')}}"></script> --}}
        <script src="{{url('assets/js/theme/default.min.js')}}"></script>
        <script src="{{url('assets/js/apps.min.js')}}"></script>
        <!-- ================== END BASE JS ================== -->

        {{-- Include external JS components --}}
        @include('RfoApprovalModule::components.js.supplier_profile_update_form')

    </body>
    </html>

{{-- @endsection --}}