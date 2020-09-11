@extends('layouts.app')
<meta name="referrer" content="never">
@include('UEditor::head')

@section('breadcrumb')

@endsection

@section('pageTitle')

@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <section class="content-header hide">
                <h1>控制台
                    <small>Control panel</small></h1>
            </section>
            <div class="content">
                <form class="form-horizontal" method="post" action="{{route('wechat.imgtext.save')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">标题:</label>
                        <div class="col-xs-12 col-sm-6">
                            <input id="c-wechatnameen" class="form-control" name="row[title]" value="" type="text"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">文章内容:</label>
                        <div class="col-xs-12 col-sm-8">
                            <script id="ContentList" type="text/plain" name="row[content]">
                            </script>

                        </div>
                    </div>

                    <div class="form-group layer-footer">
                        <label class="control-label col-xs-12 col-sm-2"></label>
                        <div class="col-xs-12 col-sm-8">
                            <button type="submit" class="btn btn-success btn-embossed">确定</button>
                            <button type="reset" class="btn btn-default btn-embossed">重置</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script type="text/javascript">
        var ue = UE.getEditor('ContentList',{
            initialFrameWidth :760,//设置编辑器宽度
            initialFrameHeight:600,//设置编辑器高度
            maximumWords:1000000,
            scaleEnabled:true,
            allowDivTransToP: false,
        });
        ue.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', {
                '_token': '{{ csrf_token() }}',
                'wid': '{{$wid}}'
            });
            // ue.addListener('simpleupload', function (t, arg) {
            //     alert(1111);
            //     var list = arg.list;
            //
            //     // console.log(arg);
            //     // alert(t,arg);
            //     // alert(arg[0].url);
            // });
        });
    </script>

@endsection
