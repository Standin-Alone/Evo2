    <!-- begin col-3 -->
    @foreach ($progs_total as $val)
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-gradient-green">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title" style="font-size: 15px">{{$val->description}} 2021  (TOTAL OF CLAIMED, NOT YET PAID) :</div>
                    <div class="stats-number">&#8369;{{number_format($val->program_total_amount, 2, '.', ',')}}</div>
                </div>
            </div>
        </div>
    @endforeach
    
    {{-- <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">RRP2 DRY SEASON 2021  (TOTAL CLAIMED) :</div>
                @foreach ($gt_dry as $gt)
                    <div class="stats-number">&#8369;{{number_format($gt->dry_grand_total, 2, '.', ',')}}</div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">RRP2 WET SEASON 2021 (TOTAL CLAIMED) :</div>
                @foreach ($gt_wet as $gt)
                    <div class="stats-number">&#8369;{{number_format($gt->wet_grand_total, 2, '.', ',')}}</div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">CASH AND FOOD 2021 (TOTAL CLAIMED) :</div>
                @foreach ($gt_csf as $gt)
                    <div class="stats-number">&#8369;{{number_format($gt->csf_grand_total, 2, '.', ',')}}</div>
                @endforeach
            </div>
        </div>
    </div> --}}
    
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-black">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF CLAIMED, NOT YET PAID : </div>
                @foreach ($gt_grand_total as $gt)
                    <div class="stats-number">&#8369;{{number_format($gt->grand_total, 2, '.', ',')}}</div>
                @endforeach
            </div>
        </div>
    </div>