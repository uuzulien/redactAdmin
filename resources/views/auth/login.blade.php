<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="body-full-height">
<head>
    <!-- META SECTION -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <meta name="viewport" content="width=device-width, initial-scale=1" >

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="favicon.ico" type="image/x-icon" >
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/theme-forest.css') }}">
    <!-- EOF CSS INCLUDE -->
</head>
<body>

<div class="login-container">
    <div class="login-box animated fadeInDown">
        <div class="" style="width: 400px;height: 100px;font-size: 28px;color:white;text-align: center;line-height:100px"><b>运营平台登录</b></div>
{{--        <div class="login-logo">Cool-Admin</div>--}}
        <div class="login-body">
            <div class="login-title text-center"><strong>用户登录</strong></div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form class="form-horizontal" method="POST" action="{{ route('doLogin') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="name" placeholder="用户名" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" class="form-control" name="password" required placeholder="密码" autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-info btn-block">登录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

</body>
</html>
