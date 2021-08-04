<!-- begin sidebar nav -->
<ul class="nav">
    <li class="nav-header">{{session('role')}} Navigation </li>
    

  @foreach(session('modules') as $key => $item) 
    @if($item[0]['has_sub'] == 1)
        
    <li class="has-sub">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-table"></i>
            <span>{{$key}}</span>
        </a>
        <ul class="sub-menu">    
                    @foreach($item['sub_modules'] as $value)
                        <li class="" ><a href="">{{$value->module}} </a></li>                       
                    @endforeach           
                </ul>
            </li>
    @endif
    @endforeach





          
{{--        



            <li class="{{Route::currentRouteName() == $value['route'] ? "active" : null}}">
                <a href="{{route($value['route'])}}">					        
                    <i class="fa fa-th-large"></i>
                    <span>{{$value['parent_module_name']}}</span>
                </a>        
            </li> --}}
    
 

    <!-- begin sidebar minify button -->
    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
    <!-- end sidebar minify button -->
</ul>
<!-- end sidebar nav -->