@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>高级群发</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="{{route('wechat.image_text.add')}}" class="btn btn-sm btn-primary refuse"><span class="fa fa-plus-square" aria-hidden="true"></span> 添加高级群发</a>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 公众号分配 -->
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>创建账号</th>
                                <th>标题</th>
                                <th>任务类型</th>
                                <th>任务状态</th>
                                <th>接收人数</th>
                                <th>预约时间</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item['user_name']}}</td>
                                    <td>
                                        @foreach($item['titles'] as $k => $v)
                                            {{$k+1}}.<span>{{$v}}</span>
                                            <br>
                                        @endforeach
                                    </td>
                                    <td>{{[0 => '文本消息', 1 => '图文消息'][$item['msgtype']]}}</td>
                                    <td>
                                        @switch($item['status'])
                                            @case(-1)
                                                <span class="label label-primary">等待初始化</span>
                                                @break
                                            @case(0)
                                                <span class="label label-info">未发送</span>
                                                @break
                                            @case(1)
                                            <span class="label label-warning">发送中</span>
                                                @break
                                            @case(2)
                                            <span class="label label-success">发送成功</span>
                                                @break
                                            @case(3)
                                                <span class="label label-danger">发送失败</span>
                                                @break
                                            @default
                                                <span class="label label-info">未知</span>
                                        @endswitch

                                    </td>
                                    <td>{{$item['send_num']}}</td>
                                    <td>{{$item['send_time']}}</td>
                                    <td>{{$item['created_at']}}</td>
                                    <td>
                                        <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteAccount({{$item['id']}})" >删除 </span>
                                    </td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
{{--                    <div class="col-xs-12 col-md-10 col-sm-10">--}}
{{--                            <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >--}}
{{--                                第 <input type="text" class="text-center form-control" style="width: 50px;display: inline-block" data-jump="{{$list->url(1)}}" value="{{ $list->currentPage() }}" id="customPage" data-total-page="{{ $list->lastPage() }}" > 页 / 本页 {{$list->count()}} 条数据--}}
{{--                            </span>--}}
{{--                        <span>共{{$list->total()}}条数据 </span>--}}
{{--                    </div>--}}
{{--                    <div class="page">{{$list->appends($app->request->all())->links()}}</div>--}}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')

@endsection
