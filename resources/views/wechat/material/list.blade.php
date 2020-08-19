@extends('layouts.app')
<meta name="referrer" content="never">
<style>
    .user-img{
        width: 24px;
        height: 24px;
        border-radius: 100px;
        margin-right: 5px;
    }
    .icon {
        width: 60px!important;
        height: 60px!important;
    }
    .icon:hover {
        transform: scale(3.5);
        transition: all 0.5s;
    }
</style>

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>微信公众号素材库</li>
@endsection

@section('pageTitle')
@endsection

@section('content')
    <!-- 公众号分配 -->
    @include('popup.wechatInfo.material')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline">
                        <div class="form-group  has-success has-feedback">
                            <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" value="{{request()->get('nick')}}" name="nick"  placeholder="请输入粉丝名称">
                            </div>
                            <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <button type="submit" class="btn btn-info">搜索</button>

                    </form>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>标题</th>
                                <th>原文地址</th>
                                <th>封面图片</th>
                                <th>素材/别名</th>
                                <th>原文链接</th>
                                <th>页面位置</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td><a href="{{$item->url}}" target="_blank">图文页的URL</a></td>
                                    <td>
                                        <img class="icon" src="{{$item->thumb_url}}">
                                    </td>
                                    <td>{{$item->media_name}}</td>
                                    <td>{{$item->content_source_url}}</td>
                                    <td>{{$item->order_rule + 1}}</td>
                                    <td>{{date('Y-m-s h:i:s', $item->create_time)}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#amendModal" data-key="{{$key}}">修改</button>
                                    </td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="page">{{$list->appends($app->request->all())->links()}}</div>
            </div>

        </div>
    </div>
@endsection

@section('js')
<script>
    var datas = @json($media);

    // 人员分配
    $('#amendModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('key');
        var data = datas[recipient];
        if (data.media_id == data.media_name) {
            var media_name = '';
        }
        var model = $(this);
        model.find('#modalLabel').text(data.title);
        model.find('#media_id').val(data.media_id);
        model.find('#media_name').val(media_name);
    });
</script>
@endsection
