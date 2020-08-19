
<ul class="x-navigation x-navigation-horizontal x-navigation-panel">
    <!-- TOGGLE NAVIGATION -->
    <li class="xn-icon-button">
        <a href="javascript:" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
    </li>
    <!-- END TOGGLE NAVIGATION -->
    <!-- POWER OFF -->
    <li class="xn-icon-button pull-right last">
       <a href="javascript:"><span class="glyphicon glyphicon-user"></span></a>
        <ul class="xn-drop-left animated zoomIn">
            @if(Auth::id() != $user->id)
            <li><a href="{{ route('user.logout.home') }}"><span class="glyphicon glyphicon-cog"></span> 回到主账户</a></li>
            @endif
            <li><a href="{{ route('centerUser') }}"><span class="glyphicon glyphicon-cog"></span> 个人中心</a></li>
            <li><a href="{{ route('editUser') }}"><span class="glyphicon glyphicon-cog"></span> 修改个人资料</a></li>
            <li><a href="{{ url('logout') }}"><span class="fa fa-sign-out"></span> 退出</a></li>
        </ul>
    </li>
    <li class="xn-icon-button pull-right last">
        <h2 style="color: white;padding-top: 7px;margin-right: 20px;">{{$user->user_name}}</h2>
    </li>
    <!-- END POWER OFF -->
    <!-- 头像 -->
    <li class="xn-icon-button pull-left col-md-offset-2">
        <a href="javascript:void(0);" class="avatar">
            <img src="{{$headimg ?? ''}}" onerror="this.src='/img/user_avatar.jpg'">
        </a>
    </li>
    <!-- END 头像 -->
    <li class="xn-icon-button pull-left last">
        <h3 style="color: white;padding-top: 7px;margin-right: 20px;line-height: 37px;">{{ $nick ?? '未选中公众号'}}</h3>
    </li>

</ul>

{{--@component('component.confirm')--}}
{{--    @slot('id', 'lock')--}}
{{--    @slot('title', '锁屏')--}}
{{--    @slot('goto', route('lock'))--}}
{{--确定进行锁屏操作吗?--}}
{{--@endcomponent--}}
