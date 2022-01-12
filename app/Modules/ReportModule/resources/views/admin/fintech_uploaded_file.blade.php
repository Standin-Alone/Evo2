<span class="mt-5">
    <div class="note note-primary">
        <div class="note-icon"><i class="far fa-file-alt"></i></div>
        <div class="note-content">
          <h4><b> FINTECH BY FILES </b></h4>
        </div>
    </div>
</span>
<br>

<div class="row">
    @include('ReportModule::components.filter_cards.fintech_files_daily_or_monthly_filter_card')
</div>

<table id="fintech-files" class="table table-bordered text-center" style="width:100%">
    <thead class="table-header">
        <tr>
            <th>FINTECH</th>
            <th>NO. OF UPLOADED FILE</th>
            <th>NO. OF DISBURSEMENT FILE</th>
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