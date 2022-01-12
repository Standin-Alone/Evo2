<span class="mt-5">
    <div class="note note-primary">
        <div class="note-icon"><i class="far fa-file-alt"></i></div>
        <div class="note-content">
          <h4><b> FINTECH BY RECORDS </b></h4>
        </div>
    </div>
</span>
<br>

<div class="row">
    @include('ReportModule::components.filter_cards.fintech_records_daily_or_monthly_filter_card')
</div>

<table id="fintech-records" class="table table-bordered text-center" style="width:100%">
    <thead class="table-header">
        <tr>
            <th>FINTECH</th>
            <th>NO. OF RECORDS UPLOADED</th>
            <th>NO. OF RECORDS DISBURSED</th>
            {{-- <th></th> --}}
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <th></th>
        <th></th>
        <th></th>
        {{-- <th></th> --}}
    </tfoot>
</table>