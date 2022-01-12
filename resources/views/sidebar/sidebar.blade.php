<!-- begin sidebar nav -->
<ul class="nav">
    <li class="nav-header">{{session('role')}} Navigation </li>
    
<li class="{{Route::currentRouteName() == 'main.home'  ? "active" : null}}">
    <a href="{{route('main.home')}}">					        
        <i class="fa fa-th-large"></i>
        <span>Home</span>
    </a>        
</li> 
    

@if(session()->has('main_modules'))
    @foreach (session('main_modules') as $item)
    @if(!is_null($item->parent_module_id) )
    
    <li class="has-sub active">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-th-large"></i>
            @foreach (session('parent_modules') as $item_parent)
                @if($item_parent->sys_module_id == $item->parent_module_id && $item_parent->nav_show == 1)
                    <span>{{$item_parent->module}}</span>
                @endif
            @endforeach
        </a>
        <ul class="sub-menu">    
                    @foreach(session('sub_modules') as $value)
                        @if($value->parent_module_id == $item->parent_module_id && $value->nav_show == 1 )                        
                        <li class="{{Route::currentRouteName() == $value->routes ? "active" : null}}" ><a href="{{route($value->routes)}}">{{$value->module}} </a></li>    
                        @endif                   
                    @endforeach           
                </ul>
            </li>
    @elseif(is_null($item->parent_module_id) && $item->nav_show == 1)   
        <li class="{{Route::currentRouteName() == $item->routes  ? "active" : null}}">
            <a href="{{route($item->routes)}}">					        
                <i class="fa fa-th-large"></i>
                <span>{{$item->module}}</span>
            </a>        
        </li> 
    @endif
    @endforeach
@endif


<!-- <li class="has-sub active">
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
    <li class="has-sub active">
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
    <li class="has-sub active">
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
    </li> -->

    <!-- begin sidebar minify button -->
    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
    <!-- end sidebar minify button -->
</ul>
<!-- end sidebar nav -->