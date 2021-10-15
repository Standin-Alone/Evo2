<!-- begin sidebar nav -->
<ul class="nav">
    <li class="nav-header">Navigation</li>
    {{-- <li class="">
        <a href="{{ route('main.home') }}">					        
            <i class="fa fa-th-large"></i>
            <span>Dashboard</span>
        </a>        
    </li> --}}
    {{-- <li class="{{Route::currentRouteName() == 'user.index' ? 'active' : null}}">
        <a href="{{ route('user.index') }}">					        
            <i class="fa fa-th-large"></i>
            <span>User Management</span>
        </a>        
    </li> --}}
    <li class="has-sub {{Route::currentRouteName() == 'list-of-users.index' ? 'active' : null}}">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>User Management</span> 
        </a>
        <ul class="sub-menu">
            <li class="">
                <li class="{{Route::currentRouteName() == 'list-of-users.index' ? 'active' : null}}"><a href="{{ route('list-of-users.index') }}">List of users</a></li>
            </li>
        </ul>
    </li>
    <li class="has-sub {{Route::currentRouteName() == 'farmer.index' ? 'active' : null}}">
        <a href="{{route('farmer.index')}}">
            {{-- <b class="caret"></b> --}}
            <i class="fa fa-th-large"></i>
            <span>Farmers Intervention Details</span> 
        </a>
        {{-- <ul class="sub-menu">
            <li class="">
                <a href="#">List of users</a>
            </li>
        </ul> --}}
    </li>
    <li class="has-sub {{(Route::currentRouteName() == 'fund_encoding' || Route::currentRouteName() == 'fund_moni_and_disb') ? 'active' : null}}">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>Budget</span> 
        </a>
        <ul class="sub-menu">
            <li class="{{Route::currentRouteName() == 'fund_encoding' ? 'active' : null}}">
                <a href="{{route('fund_encoding')}}">Fund Source Encoding</a>
            </li>
            <li class="{{Route::currentRouteName() == 'fund_moni_and_disb' ? 'active' : null}}">
                <a href="{{route('fund_moni_and_disb')}}">Fund monitoring and disbursement</a>
            </li>
        </ul>
    </li>
    <li class="has-sub {{ ( Route::currentRouteName() == 'report.total_claim_vouchers' || 
                            Route::currentRouteName() == 'report.total_ready_vouchers' || 
                            Route::currentRouteName() == 'report.claimed_not_yet_paid' ||
                            Route::currentRouteName() == 'reports.summary' ) ? 'active' : null}}">

        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>Reports Module</span> 
        </a>
        <ul class="sub-menu">
            <li class="{{Route::currentRouteName() == 'report.total_claim_vouchers' ? 'active' : null}}">
                <a href="{{route('report.total_claim_vouchers')}}">Total claim vouchers</a>
            </li>
            <li class="{{Route::currentRouteName() == 'report.total_ready_vouchers' ? 'active' : null}}">
                <a href="{{route('report.total_ready_vouchers')}}">Total number of ready vouchers</a>
            </li>
            <li class="{{Route::currentRouteName() == 'report.claimed_not_yet_paid' ? 'active' : null}}">
                <a href="{{route('report.claimed_not_yet_paid')}}">Claimed not yet paid</a>
            </li>
            <li class="{{Route::currentRouteName() == 'reports.summary' ? 'active' : null}}">
                <a href="{{route('reports.summary')}}">Summary transaction claims by supplier</a>
            </li>
        </ul>    
    </li>
    <li class="has-sub">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>Supplier Module</span>
        </a>
        <ul class="sub-menu">
            <li class="{{Route::currentRouteName() == 'DownloadApp.index' ? 'active' : null}}">
                <a href="{{ route('DownloadApp.index') }}">	
                    <span>Download Mobile App</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'VoucherTrans.index' ? 'active' : null}}">
                <a href="{{ route('VoucherTrans.index') }}">
                    <span>Voucher Monitoring</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'PayoutSummary.index' ? 'active' : null}}">
                <a href="{{ route('PayoutSummary.index') }}">
                    <span>Payout Summary</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'ProgramSrn.index' ? 'active' : null}}">
                <a href="{{ route('ProgramSrn.index') }}">	
                    <span>Program Overview</span>
                </a>        
            </li>
        </ul>
    </li>
    <li class="has-sub">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>Payout Management</span>
        </a>
        <ul class="sub-menu">
        <li class="{{Route::currentRouteName() == 'BatchPayout.index' ? 'active' : null}}">
                <a href="{{ route('BatchPayout.index') }}">	
                    <span>Batch Payout</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'SupplierPayout.index' ? 'active' : null}}">
                <a href="{{ route('SupplierPayout.index') }}">	
                    <span>Supplier Payout</span>
                </a>        
            </li>
        </ul>
    </li>
    <li class="has-sub">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>Payout Module</span>
        </a>
        <ul class="sub-menu">
        <li class="{{Route::currentRouteName() == 'PayoutMonitoring.index' ? 'active' : null}}">
                <a href="{{ route('PayoutMonitoring.index') }}">	
                    <span>Payout Monitoring</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'PayoutApproval.index' ? 'active' : null}}">
                <a href="{{ route('PayoutApproval.index') }}">	
                    <span>Payout Approval</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'PayoutSupervisorApproval.index' ? 'active' : null}}">
                <a href="{{ route('PayoutSupervisorApproval.index') }}">	
                    <span>Payout Supervisor Approval</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'SubmitPayouts.index' ? 'active' : null}}">
                <a href="{{ route('SubmitPayouts.index') }}">	
                    <span>Submit Payouts</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'DBPapproval.index' ? 'active' : null}}">
                <a href="{{ route('DBPapproval.index') }}">	
                    <span>DBP Approval</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'SubmitPayoutFiles.index' ? 'active' : null}}">
                <a href="{{ route('SubmitPayoutFiles.index') }}">	
                    <span>Submit Payout Files</span>
                </a>        
            </li>
        </ul>
    </li>
    <li class="has-sub">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            <span>Disbursement Module</span>
        </a>
        <ul class="sub-menu">
            <li class="{{Route::currentRouteName() == 'DisbursementModule.index' ? 'active' : null}}">
                <a href="{{ route('DisbursementModule.index') }}">	
                    <span>Disbursement Approval</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'SubmitDisbursement.index' ? 'active' : null}}">
                <a href="{{ route('SubmitDisbursement.index') }}">	
                    <span>Submit Disbursement</span>
                </a>        
            </li>
            <li class="{{Route::currentRouteName() == 'DisbursementHeadApproval.index' ? 'active' : null}}">
                <a href="{{ route('DisbursementHeadApproval.index') }}">	
                    <span>Disbursement Final Approval</span>
                </a>        
            </li>
        </ul>
    </li>
    
    {{-- <li class="has-sub">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-building"></i>
            <span>Voucher Management</span> 
        </a>
        <ul class="sub-menu">
            <li><a href="ui_general.html">Suppliers</a></li>						
            
        </ul>
    </li> --}}

    <!-- begin sidebar minify button -->
    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
    <!-- end sidebar minify button -->
</ul>
<!-- end sidebar nav -->