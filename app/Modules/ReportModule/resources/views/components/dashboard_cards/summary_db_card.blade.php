<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-green">
        <div class="stats-icon"><i class="fa fa-dollar-sign"></i></div>
        <div class="stats-info">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF CLAIMED VOUCHERS : </div>
            @foreach ($gt_voucher_claimed as $gt_VC)
                <div class="stats-number">&#8369;{{number_format($gt_VC->grand_total, 2, '.', ',')}}</div>
            @endforeach
        </div>
        <div class="stats-link">
            {{-- <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a> --}}
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-purple">
        <div class="stats-icon"><i class="fa fa-dollar-sign"></i></div>
        <div class="stats-info">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF CLAIMED, NOT YET PAID : </div>
            @foreach ($gt_not_paid as $gt_NP)
                <div class="stats-number">&#8369;{{number_format($gt_NP->grand_total, 2, '.', ',')}}</div>
            @endforeach
        </div>
        <div class="stats-link">
            {{-- <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a> --}}
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-red">
        <div class="stats-icon"><i class="fa fa-dollar-sign"></i></div>
        <div class="stats-info">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF SUMMARY CLAIMS :</div>
            @foreach ($gt_grand_total as $gt)
                <div class="stats-number">&#8369;{{number_format($gt->grand_total, 2, '.', ',')}}</div>
            @endforeach
        </div>
        <div class="stats-link">
            {{-- <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a> --}}
        </div>
    </div>
</div>