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
    <li>微信推送标题录入</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <button class="btn btn-info" data-toggle="modal" data-target="#addModal">新建标题</button>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 新建标题 -->
    @include('popup.material.title')
    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form class="form-inline">
                            @include('layouts.common')

                            <div class="form-group">
                                <h5>所有状态</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">@</span>
                                    <select id="select-id" class="form-control" autocomplete="off" name="status">
                                        <option value="all" >所有状态</option>
                                        <option value="0" @if(request()->get('status')=='1') selected @endif>未审核</option>
                                        <option value="1" @if(request()->get('status')=='2') selected @endif>通过</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>公众号</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">标题</span>
                                    <input type="text" class="form-control" name="title" value="{{request()->get('title')}}" placeholder="请输入标题名称">
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <button type="submit" class="btn btn-default">搜索</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">

                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li @if(request()->input('act', 2) == 2) class="active" @endif><a href="{{route('wechat.enter.title',['act'=>2])}}">小说标题</a></li>
                            <li @if(request()->input('act') == 1) class="active" @endif><a href="{{route('wechat.enter.title',['act'=>1])}}">活动标题</a></li>
                            <li @if(request()->input('act') == 3) class="active" @endif><a href="{{route('wechat.enter.title',['act'=>3])}}">签到标题</a></li>
                            <li @if(request()->input('act') == 4) class="active" @endif><a href="{{route('wechat.enter.title',['act'=>4])}}">继续阅读标题</a></li>
                        </ul><br>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>标题</th>
                                    <th>消息类型</th>
                                    <th>任务类型</th>
                                    <th>发起人</th>
                                    <th>审核结果</th>
                                    <th>申请时间</th>
                                    <th>审核时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item->title}}</td>
                                        <td>{{['1' => '图文消息','2' => '文本消息'][$item->msg_type] ?? '-'}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>
                                            @if($item->user_id == 1)
                                                系统默认
                                            @else
                                                {{$item->user_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 0)
                                                <span class="label label-info">未审核</span>
                                            @elseif($item->status == 1)
                                                <span class="label label-success">通过</span>
                                            @else
                                                <span class="label label-warning" data-toggle="tooltip" title="" data-original-title="{{$item->msg}}" data-placement="left">拒绝</span>
                                            @endif
                                        </td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->audit_time ?? '-'}}</td>
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
