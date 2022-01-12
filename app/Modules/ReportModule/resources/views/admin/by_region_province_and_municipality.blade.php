<span class="mt-5">
    <div class="note note-primary">
        <div class="note-icon"><i class="far fa-file-alt"></i></div>
        <div class="note-content">
          <h4><b> BY REGION, PROVINCE AND MUNICIPALITY </b></h4>
          {{-- <h4><b> BY REGION, PROVINCE, MUNICIPALITY AND BARANGAY </b></h4> --}}
        </div>
    </div>
</span>
<br>

<div class="row">
    @include('ReportModule::components.filter_cards.rffa_reg_prov_muni_filter_card')
</div>

<table id="co-program-focal-datatable-by-region-province-municipality-and-barangay" class="table table-bordered text-center" style="width:100%">
    <thead class="table-header">
        <tr>
            <th>REGION</th>
            <th>PROVINCE</th>
            <th>MUNICIPALITY</th>
            {{-- <th>BARANGAY</th> --}}
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
        {{-- <th></th> --}}
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tfoot>
</table>