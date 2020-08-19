<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '微信开发') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/theme-default.css?tk=1594870707812') }}">
    @stack('custom_css')
    <!-- START PLUGINS -->
    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery.min.js?tk=1594870707812') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap.min.js?tk=1594870707812') }}"></script>

{{--    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery.pjax.js') }}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery-ui.min.js') }}"></script>--}}

<!-- START THIS PAGE PLUGINS-->

    @yield('css')
</head>
<body>
<div class="page-container page-navigation-top-fixed hidden">

    @section('sidebar')
        @include('layouts.sidebar')
    @show


    <div class="page-content">
    @section('navigation')
        @include('layouts.navigation')
    @show

    <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            @section('breadcrumb')
                <li><a href="{{ route('home') }}">首页</a></li>
            @show

        </ul>
        <!-- END BREADCRUMB -->

        <!-- PAGE TITLE -->
    @yield('pageTitle')
    <!-- END PAGE TITLE -->

        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap" id="pjax-container" >
            @include('popup.wechatInfo.list')
            @if (isset($errors) && count($errors) > 0)
                <div class="top_nav">
                    <div class="nav_menu">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if(Session::has('alert-message'))
                <div class="top_nav">
                    <div class="nav_menu">
                        <div class="alert {{ session('alert-class') }}">
                            <ul>
                                <li>{{ session('alert-message') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->




<!-- START SCRIPTS -->

<!-- START THIS PAGE PLUGINS-->

@stack('scripts')
<!--自定义滚动条 -->
<script type="text/javascript" src="{{ asset('js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js?tk=1594870707812') }}"></script>
<!-- 水印页面 -->
<script type="text/javascript" src="{{ asset('js/watermark.js?tk=1594870707812') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/moment.min.js?tk=1594870707812') }}"></script>
<!-- 导航点击功能 -->
<script type="text/javascript" src="{{ asset('js/actions.js?tk=1594870707812') }}"></script>

{{--<script type="text/javascript" src="{{ asset('js/plugins/scrolltotop/scrolltopcontrol.js') }}"></script>--}}

{{--<script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datetimepicker.min.js') }}'></script>--}}
{{--<script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datepicker.min.js') }}'></script>--}}
{{--<script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datepicker.zh-CN.min.js') }}'></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/plugins/owl/owl.carousel.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap-file-input.js') }}"></script>--}}

{{--<script type="text/javascript" src="{{ asset('js/plugins/tagsinput/jquery.tagsinput.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/plugins/icheck/icheck.min.js') }}"></script>--}}

<!-- END THIS PAGE PLUGINS-->

<!-- START TEMPLATE -->
{{--<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>  // 仪表盘--}}
{{--<script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script>--}}
<!-- END TEMPLATE -->
<!-- END SCRIPTS -->


@yield('js')

{{--<script type="text/javascript" src="{{ asset('ueadit/ueditor.config.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('ueadit/ueditor.all.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('ueadit/ueditor.parse.js') }}"></script>--}}

<script>
    $(document).ready(function(){
        setTimeout(function () {$(".info-prompt").hide();},6000);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    //每个页面公共的方法
    $('.nav-pills > li').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    })

    // $('a:not(.noLoad)').on('click', function (event) {
    //     var href = $(this).attr('href');
    //     if (href.length > 5 && href.substr(0, 5) != 'javas'
    //         && !window.event.metaKey
    //         && !window.event.ctrlKey
    //         && !window.event.altKey
    //         && !window.event.shiftKey
    //     ) {
    //         pageLoadingFrame('show', 'v1');
    //     }
    // });
    $('form:not(.noLoad)').on('submit', function () {
        pageLoadingFrame('show', 'v1');
    });
    if (localStorage.getItem('navigation.minimized')) {
        $('.page-container').addClass('page-navigation-toggled');
        x_navigation_minimize("close");
    }

    $('.page-container').removeClass('hidden');

    var dataInfo = @json($wechatInfoList);
    var v_list = $('#wechat_list').children().length;
    var Loading = false;

    $('.avatar').click(function () {
        $('#SwitchModal').modal('show');
        if (v_list == 0){
            loadWechatList()
        }
    });
    setTimeout(function () {
        if (v_list == 0){
            loadWechatList()
        }
    }, 2000);

    // 加载所有可切换的公众号
    function loadWechatList() {
        if (Loading){
            return false;
        }
        Loading = true; // 防止重复加载
        var html = '';
        for(var key = 0; key < dataInfo.length; key++){
            var val = dataInfo[key];
            var type = {0:'未授权','1':'已授权'}[val['is_power']];
            html +=  `<div class="item ng-scope show-${key}" style="width:123px;height:150px;">
                                    <div class="content">
                                        <img class="icon-account" src="${val['head_img']}?pk=1594870707812" style="margin:15px 0 5px 0;width:80px;height:80px;">
                                        <div class="name ng-binding">${val['nick_name']}</div>
                                        <div class="type">
                                            <span class="ng-scope">${val['verify_type']}(${type ? type: '未知'})</span>
                                        </div>
                                    </div>
                                    <div class="mask">
                                        <a class="entry" href="${'/vv/account/switch/' + val['id']}" >
                                            <div>进入公众号&nbsp;<i class="fa fa-angle-right"></i></div>
                                        </a>
                                    </div>
                                </div>`;
        }
        $('#wechat_list').html(html);
    }


    // 展示微信公众号列表
    function showWechatList(data,word=null){
        $('#wechat_list .item').hide();
        // 控制显隐
        for (var key in data) {
            if (!word){
                $('.show-'+ key).show()
            }
            if (data[key].nick_name.search(word) != -1) {
                $('.show-'+ key).show()
            }
        }
    }

    $('#SwitchModal #btn').click(function () {
        showWechatList(dataInfo, $('#wechat_nick').val());
    });
    $('#wechat_nick').keypress(function (e) {
        if (e.keyCode == 13) {
            showWechatList(dataInfo, $(this).val());
        }
    });
</script>
@stack('auth')
<script>
    $('#Menus a[href="@yield('activeUrl', request()->url())"]').parents('li').addClass('active');
    /* global define */
    // 加一下水印啦啦啦(老夫的少女心)

    watermark.init({
        watermark_txt: '{{Auth::user()->name ?? '未知'}}', //第一行水印的内容
        watermark_time: moment().format('YYYY-MM-DD H:mm:ss'), //第二行水印内容
        watermark_x: 20, //水印起始位置x轴坐标
        watermark_y: 20, //水印起始位置Y轴坐标
        watermark_rows: 0, //水印行数
        watermark_cols: 0, //水印列数
        watermark_x_space: 50, //水印x轴间隔
        watermark_y_space: 50, //水印y轴间隔
        watermark_font: '微软雅黑', //水印字体
        watermark_color: 'gray', //水印字体颜色
        watermark_fontsize: '16px', //水印字体大小
        watermark_alpha: 0.12, //水印透明度，要求设置在大于等于0.005
        watermark_width: 90, //水印宽度
        watermark_height: 50, //水印高度
        watermark_angle: 30, //水印倾斜度数
        // watermark_parent_width: '1200px', //水印倾斜度数
        // watermark_parent_height: '800px', //水印倾斜度数
        // watermark_parent_node: document.getElementById('pjax-container') //水印插件挂载的父元素element,不输入则默认挂在body上
    });

</script>

</body>
</html>
