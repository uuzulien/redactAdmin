<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '管理后台') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/theme-default.css') }}">
    <!-- START PLUGINS -->
    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery.pjax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
    <!-- END PLUGINS -->
    @yield('css')
</head>
<body>
<div class="page-container page-navigation-top-fixed hidden">

    <div class="page-content">
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap" id="pjax-container">
            @yield('content')
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->




<!-- START SCRIPTS -->

<!-- START THIS PAGE PLUGINS-->
<script type="text/javascript" src="{{ asset('js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/scrolltotop/scrolltopcontrol.js') }}"></script>


<script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datepicker.min.js') }}'></script>
<script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datepicker.zh-CN.min.js') }}'></script>
<script type="text/javascript" src="{{ asset('js/plugins/owl/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap-file-input.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap-select.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/tagsinput/jquery.tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/icheck/icheck.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/plugins/moment.min.js') }}"></script>
<!-- END THIS PAGE PLUGINS-->

<!-- START TEMPLATE -->
<script type="text/javascript" src="{{ asset('js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/actions.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script>
<!-- END TEMPLATE -->
<!-- END SCRIPTS -->

@yield('js')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

//    $(document).pjax('a', '#pjax-container');
//    $(document).on("pjax:timeout", function(event) {
//        event.preventDefault();
//    });
    //每个页面公共的方法
    $('.nav-pills > li').click(function () {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
    })

    $('a:not(.noLoad)').on('click', function (event) {
        var href = $(this).attr('href');
        if (href.length > 5 && href.substr(0, 5) != 'javas'
            && !window.event.metaKey
            && !window.event.ctrlKey
            && !window.event.altKey
            && !window.event.shiftKey
        ) {
            pageLoadingFrame('show', 'v1');
        }
    });
    $('form:not(.noLoad)').on('submit', function () {
        pageLoadingFrame('show', 'v1');
    });
    if (localStorage.getItem('navigation.minimized')) {
        $('.page-container').addClass('page-navigation-toggled');
        x_navigation_minimize("close");
    }

    $('.page-container').removeClass('hidden');
</script>

<script>
    $('#Menus a[href="@yield('activeUrl', request()->url())"]').parents('li').addClass('active');
</script>

</body>
</html>
