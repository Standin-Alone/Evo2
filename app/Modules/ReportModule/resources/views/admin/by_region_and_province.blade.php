<span class="mt-5">
    <div class="note note-primary">
        <div class="note-icon"><i class="far fa-file-alt"></i></div>
        <div class="note-content">
          <h4><b> BY REGION AND PROVINCE </b></h4>
        </div>
    </div>
</span>
<br>

{{-- Filter Cards --}}
<div class="row">
    @include('ReportModule::components.filter_cards.rffa_reg_and_prov_filter_card')
</div>

<table id="co-program-focal-datatable" class="table table-bordered text-center" style="width:100%">
    <thead class="table-header">
        <tr>
            <th>REGION</th>
            <th>PROVINCE</th>
            <th>FINTECH</th>
            <th>NO. OF UPLOADED KYC</th>
            <th>NO. OF DISBURSED</th>
            <th>TOTAL DISBURSED AMOUNT</th>
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
    </tfoot>
</table>