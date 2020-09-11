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
    <li>微信图文消息</li>
@endsection

@section('pageTitle')

    <div class="page-title">
        <h2>
            <a class="btn btn-info" href="{{route('wechat.imgtext.add')}}">新建图文消息</a>
        </h2>

    </div>
@endsection

@section('content')
{{--    <!-- 公众号分配 -->--}}
{{--    @include('popup.material.link.history')--}}
{{--    <!-- 链接修改 -->--}}
{{--    @include('popup.material.edit.history')--}}

    <div class="row">
        <div class="col-md-12">



            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>发起人</th>
                                <th>标题</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->user_name}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{route('wechat.imgtext.edit', $item->id)}}">编辑</a>
                                    </td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-md-10 col-sm-10">
                            <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >
                                第 <input type="text" class="text-center form-control" style="width: 50px;display: inline-block" data-jump="{{$list->url(1)}}" value="{{ $list->currentPage() }}" id="customPage" data-total-page="{{ $list->lastPage() }}" > 页 / 本页 {{$list->count()}} 条数据
                            </span>
                        <span>共{{$list->total()}}条数据 </span>
                    </div>
                    <div class="page">{{$list->appends($app->request->all())->links()}}</div>

                </div>

            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        // 快速跳转
        $("#customPage").keydown(function(e){
            if(e.keyCode == 13){
                var jump = $("#customPage").attr('data-jump');
                jump = jump.substring(0, jump.length-1);
                var page = $('#customPage').val();
                location.href= jump + page;
            }
        });
    </script>
@endsection
