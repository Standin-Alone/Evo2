<span class="mt-5">
    <div class="note note-primary">
        <div class="note-icon"><i class="far fa-file-alt"></i></div>
        <div class="note-content">
          <h4><b> BY REGION </b></h4>
        </div>
    </div>
</span>
<br>

<div class="row">
    @include('ReportModule::components.filter_cards.rffa_prov_filter_card')
</div>

<table id="rfo-program-focal-datatable" class="table table-bordered text-center" style="width:100%">
    <thead class="table-header">
        <tr>
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
    </tfoot>
</table>