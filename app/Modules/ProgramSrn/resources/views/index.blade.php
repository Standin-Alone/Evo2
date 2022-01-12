@extends('global.base')
@section('title', "Program Overview")

{{--  import in this section your css files--}}
@section('page-css')
    {{-- Include Libraries CSS --}}
    @include('components.libraries.css-components')
@endsection

{{--  import in this section your javascript files  --}}
@section('page-js')    
    {{-- Include Libraries JS --}}
    @include('components.libraries.js-components')
    
    {{-- Include Script Components --}}
    @include('ProgramSrn::components.script.js')



@endsection


@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>

<ol class="breadcrumb pull-right"> 
        <li class="breadcrumb-item"><a href="{{ route('SupplierModule.index') }}">Home Page</a></li>
        <li class="breadcrumb-item active">Program Overview</li>
    </ol>
    <h1 class="page-header">Program Overview</h1>

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title" style="font-weight:normal !important;">Program Details:</h4>
    </div>
    <div class="panel-body">
        <table id="SupplierProgramList-datatable" class="display select table table-striped" style="width: 100%;">
            <thead style="background-color: #008a8a;"></thead>
        </table>
    </div>
</div>
<!-- end panel -->


<div class="modal fade" id="addSuppierProgramModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="addSupplierProgram" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white">Add Available Program</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <label class="control-label">SRN:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Registered SRN" name="SupplierProgram_srn"/>
                                            <span class="text-danger">@error('SupplierProgram_srn'){{ $message }} @enderror</span>
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success SupplierProgram_submit">Submit</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection