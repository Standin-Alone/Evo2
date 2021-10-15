@extends('global.base')
@section('title', "Budget")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

    {{-- External CSS --}}
    @include('BudgetModule::components.css.css')
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

    {{-- Validation and Edit button--}}
    @include('BudgetModule::components.js.validation.edit_amount')

    {{-- Add Comma, 2 decimals, and Peso sign --}}
    @include('BudgetModule::components.js.conversion.number_format_conversion')

    {{-- Disbursement datatable --}}
    @include('BudgetModule::components.js.datatables.overview_datatable')
@endsection


<script>

</script>


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">Fund Source Overview</li>
</ol>

<div class="row mt-5">
    {{-- @include('BudgetModule::dashboard_cards') --}}
</div>

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Fund Source Overview</h4>
    </div>
    <div class="panel-body">
        <br>
        {{-- Program Filter Card --}}
        {{-- @include('BudgetModule::components.filter_cards.program_filter_card') --}}
        <br>
        <table id="overview_table" class="table table-striped table-bordered table-hover text-center" style="width:100%;">            
            <thead class="table-header">
                <tr>          
                    <th>Program</th>
                    <th>UACS</th>
                    <th>GFI</th>
                    <th>Region</th>
                    <th>Particulars</th>
                    <th>Target of beneficiaries</th>
                    <th>Amount</th>
                    <th>Action</th>                   
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- #modal-EDIT -->
<div class="modal fade" id="edit_overview_modal">
    <div class="modal-dialog modal-lg">
        <form id="edit_amount" method="PUT" route="">
            {{ csrf_field() }}
            @method('PATCH')

            <span class="error_form"></span>

            <div class="modal-content" style="width:100%;">
                <div class="modal-header" style="background-color: #f59c1a">
                    <h4 class="modal-title" style="color: white">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white">×</button>
                </div>
                <div class="modal-body">
                    {{--modal body start--}}

                    <input id="fund_id" name="fund_id" type="text" class="form-control hide" name="fund_id"/>

                    <div class="col-lg-12">
                        <div class="form-group row m-b-10">
                            <label class="col-md-3 text-md-right col-form-label">UACS</label>
                            <div class="col-md-6">
                                <input type="number" id="uacs" name="uacs" placeholder="input uacs..." class="form-control" />
                                <span class="error_msg"></span>
                            </div>
                        </div>

                        <div class="form-group row m-b-10">
                            <label class="col-md-3 text-md-right col-form-label">GFI</label>
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
                            <label class="col-md-3 text-md-right col-form-label">Region</label>
                            <div class="col-md-6">
                                <input type="hidden" id="select_region_hidden" name="select_region_hidden" value="">
                                <select class="form-control" name="select_region" id="select_region">
                                    <option value="">-- Select region --</option>
                                        @foreach ($regions as $region)
                                            <option value="{{$region->reg_code}}">{{$region->reg_name}}</option>
                                        @endforeach
                                </select>
                                <span class="error_msg"></span>
                            </div>
                        </div>

                        <div class="form-group row m-b-10">
                            <label class="col-md-3 text-md-right col-form-label">No. of Farmers</label>
                            <div class="col-md-6">
                                <input id="no_of_farmers" type="number" name="no_of_farmers" placeholder="" class="form-control" />
                                <span class="error_msg"></span>
                            </div>
                        </div>

                        <div class="form-group row m-b-10">
                            <label class="col-md-3 text-md-right col-form-label">Amount</label>
                            <div class="col-md-6">
                                <input id="amount" type="text" name="amount" placeholder="" class="form-control" />
                                <span class="error_msg"></span>
                            </div>
                        </div>

                        <div class="form-group row m-b-10">
                            <label class="col-md-3 text-md-right col-form-label">Particulars</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="3" id="particulars" name="particulars"></textarea>
                                <span class="error_msg"></span>
                            </div>
                        </div>
                    </div>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-lime">Update</button>
                    {{-- <a id="EditBTN" href="javascript:;" class="btn btn-success">Update</a> --}}
                </div>
            </div>
        </form>
    </div>
</div>

@endsection