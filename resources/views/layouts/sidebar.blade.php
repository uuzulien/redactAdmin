<div class="page-sidebar page-sidebar-fixed scroll">
    <!-- START X-NAVIGATION -->
    <ul class="x-navigation" id="Menus">
        <li class="xn-logo">
{{--            <div style="padding-right: 18px"></div>--}}
            <a href="{{ route('home') }}" class="text-center">运<span>营管理后台</span></a>
            <a href="javascript:" class="x-navigation-control"></a>
        </li>
        <li class="xn-title">导航</li>
        @if(!empty($menu))
            @foreach($menu as $valPartentMenu)
                @if($valPartentMenu['is_menu']==1)
                <li class="xn-openable"><a href="javascript:"><span class="{{$valPartentMenu['icon']}}"></span> <span class="xn-text">{{$valPartentMenu['display_name']}}</span></a>
                @else
                    <li class="xn-openable hide"><a href="javascript:"><span class="{{$valPartentMenu['icon']}}"></span> <span class="xn-text">{{$valPartentMenu['display_name']}}</span></a>
                @endif
                    @if(!empty($valPartentMenu['children']))
                        <ul>
                            @foreach($valPartentMenu['children'] as $menuChild)
                                @if(!empty($menuChild['children']))
                                    @if($menuChild['is_menu']==1)
                                        <li class="xn-openable"><a href="javascript:"><span class="{{$menuChild['icon']}}"></span> <span>{{$menuChild['display_name']}}</span></a>
                                    @else
                                        <li class="xn-openable hide"><a href="javascript:"><span class="{{$menuChild['icon']}}"></span> <span>{{$menuChild['display_name']}}</span></a>
                                    @endif
                                    <ul>
                                        @foreach($menuChild['children'] as $menuChild1)
                                            @if($menuChild1['is_menu']==1 && is_route($menuChild1['name']))
                                                <li><a href="{{ route($menuChild1['name']) }}"><span class="{{$menuChild1['icon']}}"></span>{{$menuChild1['display_name']}}</a></li>
                                            @elseif($menuChild1['icon'] && is_route($menuChild1['name']))
                                                <li class="hide"><a href="{{ route($menuChild1['name']) }}"><span class="{{$menuChild1['icon']}}"></span>{{$menuChild1['display_name']}}</a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @elseif(substr($menuChild['name'],0,2)=='目录')
                                            @if($menuChild['is_menu']==1 && is_route($menuChild['name']))
                                                <li><a href="{{ route($menuChild['name']) }}"><span class="{{$menuChild['icon']}}"></span>{{$menuChild['display_name']}}</a></li>
                                            @elseif($menuChild['icon'] && is_route($menuChild['name']))
                                                <li class="hide"><a href="{{ route($menuChild['name']) }}"><span class="{{$menuChild['icon']}}"></span>{{$menuChild['display_name']}}</a></li>
                                            @endif
                                @else
                                            @if($menuChild['is_menu']==1 && is_route($menuChild['name']))
                                                <li><a href="{{ route($menuChild['name']) }}"><span class="{{$menuChild['icon']}}"></span>{{$menuChild['display_name']}}</a></li>
                                            @elseif($menuChild['is_menu']==1 )
                                                <li><a href="javascript:void(0)"><span class="{{$menuChild['icon']}}"></span>{{$menuChild['display_name']}}</a></li>
                                            @elseif($menuChild['icon'])
                                                <li class="hide"><a href="javascript:void(0)"><span class="{{$menuChild['icon']}}"></span>{{$menuChild['display_name']}}</a></li>
                                            @endif
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
    <!-- END X-NAVIGATION -->
</div>
