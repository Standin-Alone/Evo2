<!-- begin sidebar nav -->
<ul class="nav">
    <li class="nav-header">Navigation {{session('role')}}</li>
    
    @if(session('role') == 'Admin')

    @foreach(session('modules') as $item )            
        <li class="{{Route::currentRouteName() == $item->routes ? "active" : null}}">
            <a href="{{ route($item->routes) }}">					        
                <i class="fa fa-th-large"></i>
                <span>{{$item->module}}</span>
            </a>        
        </li>
    @endforeach

    @endif
    
 

    <!-- begin sidebar minify button -->
    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
    <!-- end sidebar minify button -->
</ul>
<!-- end sidebar nav -->