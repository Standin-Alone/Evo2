<!-- begin sidebar nav -->
<ul class="nav  navbar-collapse" >
    <li class="nav-header">{{session('role')}} Navigation </li>
    
<li class="{{Route::currentRouteName() == 'main.home'  ? "active" : null}}">
    <a href="{{route('main.home')}}">					        
        <i class="fa fa-home"></i>
        <span>Home</span>
    </a>        
</li> 



@if(session()->has('main_modules'))
    

    @foreach(session('main_modules') as $item)    


    @if(is_null($item->parent_module_id) && $item->has_sub == 1 && ($item->process_type == session('Default_Program_processtype')  || in_array(1,session('role_no_sets'))))        
    <li class="has-sub  {{ (in_array(Route::currentRouteName(),array_column(json_decode(session('sub_modules'),true), 'routes'))  &&  array_filter(json_decode(session('sub_modules')),function($e) use ($item){return ($e->routes  == Route::currentRouteName() && $e->parent_module_id == $item->sys_module_id);})) ? 'active' : '' }} ">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-{{ $item->icon }}"></i>
            @foreach (session('parent_modules') as $item_parent)
                @if($item_parent->sys_module_id == $item->sys_module_id && $item_parent->nav_show == 1  && ($item_parent->process_type == session('Default_Program_processtype')  || in_array(1,session('role_no_sets'))) )
                    <span>{{$item_parent->module}}</span>
                @endif
            @endforeach
        </a>
        <ul class="sub-menu">    
                    @foreach(session('sub_modules') as $value)
                        @if($value->parent_module_id == $item->sys_module_id && $value->nav_show == 1 && $value->routes != ''  && ($value->process_type == session('Default_Program_processtype')  || in_array(1,session('role_no_sets'))) )                        
                            <li class="{{Route::currentRouteName() == $value->routes ? "active" : null}} " ><a href="{{route($value->routes)}}">{{$value->module}} </a></li>    
                        @elseif($value->parent_module_id == $item->sys_module_id && $value->nav_show == 1 && $value->routes == ''  && ($value == session('Default_Program_processtype')  || in_array(1,session('role_no_sets'))) )                        
                            <li class="has-sub ">
                                <a href="javascript:;">
                                    <b class="caret"></b>
                                    <i class="fa fa-{{ $value->icon }}"></i>
                                    <span>{{$value->module}}</span>
                                </a>
                                <ul class="sub-menu">    

                                    
                                    @foreach(session('sub_modules') as $value_parent_sub_modules)
                                        @if($value->sys_module_id == $value_parent_sub_modules->parent_module_id)
                                            
                                            <li class="{{Route::currentRouteName() == $value_parent_sub_modules->routes ? "active" : null}} " ><a href="{{route($value_parent_sub_modules->routes)}}">{{$value_parent_sub_modules->module}} </a></li>    
                                        @endif
                                    @endforeach
                                    
                                </ul>
                            </li>
                        @endif                   
                    @endforeach           
                </ul>
            </li>
    @elseif(is_null($item->parent_module_id) && $item->nav_show == 1 && $item->has_sub == 0 && ($item->process_type == session('Default_Program_processtype')  || in_array(1,session('role_no_sets'))))   
        <li class="{{Route::currentRouteName() == $item->routes  ? "active" : null}}">
            <a href="{{route($item->routes)}}">					        
                <i class="fa fa-{{ $item->icon }}"></i>
                <span>{{$item->module}}</span>
            </a>        
        </li> 
    @endif
    @endforeach
@endif



{{--    OLD
@if(session()->has('main_modules'))
    @foreach (session('main_modules') as $item)
    
    @if(!is_null($item->parent_module_id) )
        @php
            
        @endphp
    <li class="has-sub {{ (in_array(Route::currentRouteName(),array_column(json_decode(session('sub_modules'),true), 'routes'))  &&  array_filter(json_decode(session('sub_modules')),function($e) use ($item){return ($e->routes  == Route::currentRouteName() && $e->parent_module_id == $item->parent_module_id);})) ? 'active' : '' }} ">
        <a href="javascript:;">
            <b class="caret"></b>
            <i class="fa fa-{{ $item->icon }}"></i>
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
                <i class="fa fa-{{ $item->icon }}"></i>
                <span>{{$item->module}}</span>
            </a>        
        </li> 
    @endif
    @endforeach
@endif --}}

