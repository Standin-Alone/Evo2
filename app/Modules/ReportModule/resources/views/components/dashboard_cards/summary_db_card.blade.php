<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-black">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF CLAIMED VOUCHERS : </div>
            @foreach ($gt_voucher_claimed as $gt_VC)
                <div class="stats-number">&#8369;{{number_format($gt_VC->grand_total, 2, '.', ',')}}</div>
            @endforeach
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-black">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF CLAIMED, NOT YET PAID : </div>
            @foreach ($gt_not_paid as $gt_NP)
                <div class="stats-number">&#8369;{{number_format($gt_NP->grand_total, 2, '.', ',')}}</div>
            @endforeach
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-green">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF SUMMARY CLAIMS :</div>
            @foreach ($gt_grand_total as $gt)
                <div class="stats-number">&#8369;{{number_format($gt->grand_total, 2, '.', ',')}}</div>
            @endforeach
        </div>
    </div>
</div>
