<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-blue">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-file-alt fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 14px">Number of Vouchers :</div>
            @foreach ($dashboard_cards as $data)
                <div class="stats-number" style="font-size: 20px">{{number_format($data->total_no_of_vouchers)}}</div>
            @endforeach
            {{-- <div class="stats-progress progress">
                <div class="progress-bar" style="width: 70.1%;"></div>
            </div>
            <div class="stats-desc">Better than last week (70.1%)</div> --}}
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-blue">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-file-alt fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 14px">Number of On Process Voucher  :</div>
            @foreach ($dashboard_cards as $data)
                <div class="stats-number" style="font-size: 20px">{{number_format($data->total_no_of_used_vouchers)}}</div>
            @endforeach
            {{-- <div class="stats-progress progress">
                <div class="progress-bar" style="width: 70.1%;"></div>
            </div>
            <div class="stats-desc">Better than last week (70.1%)</div> --}}
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-blue">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-file-alt fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 14px">Number of Ready Vouchers :</div>
            @foreach ($dashboard_cards as $data)
                <div class="stats-number" style="font-size: 20px">{{number_format($data->total_no_of_unused_vouchers)}}</div>
            @endforeach
            {{-- <div class="stats-progress progress">
                <div class="progress-bar" style="width: 70.1%;"></div>
            </div>
            <div class="stats-desc">Better than last week (70.1%)</div> --}}
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-red">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 14px">Grand Total of Ready Vouchers :</div>
            @foreach ($total_ready_voucher_grandtotal as $gt)
                <div class="stats-number" style="font-size: 20px">&#8369;{{number_format($gt->grand_total, 2, '.', ',')}}</div>
            @endforeach
            {{-- <div class="stats-progress progress">
                <div class="progress-bar" style="width: 70.1%;"></div>
            </div>
            <div class="stats-desc">Better than last week (70.1%)</div> --}}
        </div>
    </div>
</div>