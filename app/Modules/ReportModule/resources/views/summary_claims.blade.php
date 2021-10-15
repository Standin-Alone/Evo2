@extends('global.base')
@section('title', "Report")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />

    {{-- Include external JS components --}}
    @include('ReportModule::components.css.css')
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

    {{-- Include external JS components --}}
    @include('ReportModule::components.js.js')

    {{-- Voucher Claimed DataTable --}}
    @include('ReportModule::components.js.datatables.summary_claims_datatable')
@endsection


<script>

</script>


@section('content')

<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
    <li class="breadcrumb-item active">Summary Transaction Claims by Supplier</li>
</ol>

<div class="row mt-5">
    @include('ReportModule::components.dashboard_cards.summary_db_card')
</div>
<span class="text-danger">*Note: If the program card did not appear above. It means that the program are not available or does have a value of "0.00".</span>
<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title"> Summary Transaction Claims by Supplier</h4>
    </div>
    <div class="panel-body">
        <br>
        <div class="row">
            <div class="panel panel-primary col-md-12">
                <div class="panel-heading">Select by Supplier</div>
                <div class="panel-body border">
                    <div class="form-group">
                      <label for=""></label>
                      <select data-column="0" class="form-control filter-select" name="filter_supplier" id="filter_supplier">
                            <option value="">-- Select Supplier --</option>
                            @foreach ($supplier as $s_name)
                                <option value="{{$s_name}}">{{$s_name}}</option>
                            @endforeach
                      </select>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table id="summary-datatable" class="table table-bordered text-center mb-5 display" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>Supplier</th>
                    <th>Program</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Total Amount</th>
                    <th>Transact By</th>
                    <th>Transaction Date</th>
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
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</div>
@endsection