<!-- begin col-3 -->
<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-blue">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-file-alt fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 14px">Number of Not Paid Vouchers :</div>
            @foreach ($count_no_of_not_paid as $data)
                <div class="stats-number" style="font-size: 20px">{{number_format($data->total_no_of_used_vouchers)}}</div>
            @endforeach
            {{-- <div class="stats-progress progress">
                <div class="progress-bar" style="width: 70.1%;"></div>
            </div>
            <div class="stats-desc">Better than last week (70.1%)</div> --}}
        </div>
    </div>
</div>

@if ($progs_total == null)
    @foreach ($program_title as $progs)
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 14px">{{$progs->description}}2021 (TOTAL OF NOT YET PAID) :</div>
                <div class="stats-number" style="font-size: 20px">&#8369;0.00</div>
                {{-- <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 70.1%;"></div>
                </div>
                <div class="stats-desc">Better than last week (70.1%)</div> --}}
            </div>
        </div>
    </div>
    @endforeach  
@else
    @foreach ($progs_total as $val)
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 14px">{{$val->description}} 2021  (TOTAL CLAIMED) :</div>
                <div class="stats-number" style="font-size: 20px">&#8369;{{number_format($val->program_total_amount, 2, '.', ',')}}</div>
                {{-- <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 70.1%;"></div>
                </div>
                <div class="stats-desc">Better than last week (70.1%)</div> --}}
            </div>
        </div>
    </div>
    @endforeach
@endif
    
<div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-gradient-red">
        <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
        <div class="stats-content">
            <div class="stats-title" style="font-size: 15px">GRAND TOTAL OF CLAIMED, NOT YET PAID : </div>
            @foreach ($gt_grand_total as $gt)
                <div class="stats-number" style="font-size: 20px">&#8369;{{number_format($gt->grand_total, 2, '.', ',')}}</div>
            @endforeach
            {{-- <div class="stats-progress progress">
                <div class="progress-bar" style="width: 70.1%;"></div>
            </div>
            <div class="stats-desc">Better than last week (70.1%)</div> --}}
        </div>
    </div>
</div>