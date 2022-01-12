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

    {{-- Number conversion with negative sign --}}
    @include('BudgetModule::components.js.conversion.number_format_conversion_with_negative_sign')

    {{-- Disbursement datatable --}}
    @include('BudgetModule::components.js.datatables.disbursement_datatable')
    @include('BudgetModule::components.js.datatables.rffa_breakdown_modal_datatable')
    @include('BudgetModule::components.js.datatables.voucher_breakdown_modal_datatable')
@endsection


<script>

</script>


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">Fund Monitoring and Disbursement</li>
</ol>

<div class="row mt-5">
    {{-- @include('BudgetModule::dashboard_cards') --}}
</div>

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">FUND MONITORING AND DISBURSEMENT</h4>
    </div>
    <div class="panel-body">
        <br>
        {{-- Program Filter Card --}}
        @include('BudgetModule::components.filter_cards.program_filter_card')
        <br>
        <table id="disbursement_table" class="table table-striped table-bordered table-hover text-center" style="width:100%;">            
            <thead class="table-header">
                <tr>          
                    <th>PROGRAM</th>
                    {{-- <th>Particulars</th> --}}
                    <th>REGION</th>
                    {{-- group by program_id and region. SUM(amount) on fund_source table--}}
                    <th>TOTAL AMOUNT</th>
                    {{-- group by program_id, SUM(amount) on excel_export table --}}
                    <th>DISBURSE AMOUNT</th>
                    {{-- (Total amount subtract to Disburse amount)  --}}
                    <th>REMAINING AMOUNT</th>
                    {{-- <th>View Fund Source Breakdown</th> --}}
                    {{-- <th>PROGRESS BAR</th> --}}
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <!-- #modal-view -->
        <div class="modal fade" id="view_computation">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"  style="width:100%;">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">DISBURSE BREAKDOWN</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        <table id="rffa_disbursement_breakdown_link"class="table table-bordered table-hover mt-5 mb-5 text-center" style="width:100%;">
                            <thead  class="table-header">
                              <tr>
                                <th scope="col" style="color: white">RSBSA NO.</th>
                                <th scope="col" style="color: white">FULLNAME</th>
                                <th scope="col" style="color: white">ACCOUNT NO.</th>
                                <th scope="col" style="color: white">PROGRAM</th>
                                <th scope="col" style="color: white">AMOUNT</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-danger" data-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- #modal-view -->
        <div class="modal fade" id="view_voucher_breakdown_computation">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"  style="width:100%;">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">DISBURSE BREAKDOWN</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        <table id="voucher_disbursement_breakdown_link"class="table table-bordered table-hover mt-5 mb-5 text-center" style="width:100%;">
                            <thead  style="background-color: #008a8a">
                                <th scope="col" style="color: white">REFERENCE NO.</th>
                                <th scope="col" style="color: white">FULLNAME</th>
                                <th scope="col" style="color: white">PROGRAM</th>
                                <th scope="col" style="color: white">ITEM NAME</th>
                                <th scope="col" style="color: white">QUANTITY</th>
                                <th scope="col" style="color: white">AMOUNT</th>
                                <th scope="col" style="color: white">TOTAL AMOUNT</th>
                            </thead>
                        </table>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tfoot>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-danger" data-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection