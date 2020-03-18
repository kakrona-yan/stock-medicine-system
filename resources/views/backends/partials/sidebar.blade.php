@if (auth()->check())
	@if ($menus)
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex justify-content-center align-items-center"
            href="{{ \Auth::user()->isRoleAdmin() ? route('dashboard') : '#'}}">
            <div class="sidebar-brand-text text-center">{{ __('menu.shop_name') }}</div>
        </a>
        @foreach ($menus as $key => $menu)
            @if($menu['role_type'])
                <li class="nav-item {{$menu['route'] == Route::currentRouteName() ? 'active' : '' }}">
                    <a class="nav-link" href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
                        @if(isset($menu['sub_menu']) && $menu['sub_menu'] !=null)
                        data-toggle="collapse"
                        data-target="#{{isset($menu['sub_menu']) && $menu['sub_menu'] !=null ? 'sub_menu'.$key : ''}}"
                        aria-expanded="true"
                        aria-controls="collapseTwo"
                        @endif
                    ><i class="{{ $menu['icon'] }}"></i><span>{{ $menu['label'] }}</span>
                    </a>
                    @if(isset($menu['sub_menu']) && $menu['sub_menu'] !=null)
                        <div id ="sub_menu{{$key}}" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                @foreach ($menu['sub_menu'] as $subMenu)
                                    <a class="collapse-item" href="{{ $subMenu['route'] ? route($subMenu['route']) : '#' }}">
                                        <i class="{{ $subMenu['icon'] }} mr-1"></i><span>{{ $subMenu['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
    <!-- End of Sidebar -->
    @endif
@endif

