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

    {{-- Disbursement datatable --}}
    @include('BudgetModule::components.js.datatables.disbursement_datatable')
    @include('BudgetModule::components.js.datatables.modal_datatable')
@endsection


<script>

</script>


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">Monitoring and Disbursement</li>
</ol>

<div class="row mt-5">
    {{-- @include('BudgetModule::dashboard_cards') --}}
</div>

<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Fund Monitoring and Disbursement</h4>
    </div>
    <div class="panel-body">
        <br>
        {{-- Program Filter Card --}}
        @include('BudgetModule::components.filter_cards.program_filter_card')
        <br>
        <table id="disbursement_table" class="table table-striped table-bordered table-hover text-center" style="width:100%;">            
            <thead class="table-header">
                <tr>          
                    <th>Program</th>
                    {{-- <th>Particulars</th> --}}
                    <th>Region</th>
                    {{-- group by program_id and region. SUM(amount) on fund_source table--}}
                    <th>Total amount</th>
                    {{-- group by program_id, SUM(amount) on excel_export table --}}
                    <th>Disburse amount</th>
                    {{-- (Total amount subtract to Disburse amount)  --}}
                    <th>Remaining amount</th>
                    {{-- <th>View Fund Source Breakdown</th> --}}
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <!-- #modal-view -->
        <div class="modal fade" id="view_computation">
            <div class="modal-dialog modal-lg">
                <div class="modal-content  style="width:100%;">
                    <div class="modal-header" style="background-color: #008a8a">
                        <h4 class="modal-title" style="color: white">Computation of remaining amount</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                    </div>
                    <div class="modal-body">
                        {{--modal body start--}}
                        <table id="rffa_disbursement_breakdown_link"class="table table-bordered table-hover mt-5 mb-5 text-center" style="width:100%;">
                            <thead  class="table-header">
                              <tr>
                                <th scope="col" style="color: white">Program</th>
                                <th scope="col" style="color: white">Amount</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                            </tfoot>
                        </table>
                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection