@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body" style="text-align: center">
                       <h1>对不起！你无此权限 请联系管理员开通。</h1>
                    </div>
                    <div class="panel-body" style="text-align: center">
                        <span id="time"></span>后自动<a href="javascript:;" onclick="window.history.go(-1)">返回</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var i=5;
        $(function(){
            setTimeout(function(){window.history.go(-1);},5000);//5秒后返回
            after();
        });
        //自动刷新页面上的时间
        function after(){
            $("#time").empty().append(i);
            i=i-1;
            setTimeout(function(){
                after();
            },1000);
        }
    </script>
@endsection
