<span class="mt-5">
    <div class="note note-primary">
        <div class="note-icon"><i class="far fa-file-alt"></i></div>
        <div class="note-content">
          <h4><b> REPORTS BY APPROVAL LEVEL </b></h4>
        </div>
    </div>
</span>
<br>

<div class="row">
    @include('ReportModule::components.filter_cards.rffa_report_level_filter_card')
</div>

<table id="rfo-program-focal-report_approval" class="table table-bordered text-center" style="width:100%">
    <thead class="table-header">
        <tr>
            <th>PROVINCE</th>
            <th>FINTECH</th>
            <th>TOTAL UPLOADED</th>
            <th>GENERATE BENEFECIARIES</th>
            <th>BUDGET</th>
            <th>DISBURSEMENT</th>
            {{-- <th>Final Approval</th> --}}
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
        {{-- <th></th> --}}
    </tfoot>
</table>