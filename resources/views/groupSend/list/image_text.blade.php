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
                                <th>所属公众号</th>
                                <th>运营专员</th>
                                <th>任务标题/备注</th>
                                <th>类型</th>
                                <th>用户群体</th>
                                <th>接收人数</th>
                                <th>发送时间</th>
                                <th>创建时间</th>
                                <th>任务状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
{{--                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#checkModal" data-key="{{$key}}">切入</button>--}}
{{--                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transModal" data-key="{{$key}}">转移</button>--}}
{{--                                        <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteAccount({{$item->id  }})" >粉丝更新 </span>--}}
                                    </td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
{{--                <div class="page">{{$list->appends($app->request->all())->links()}}</div>--}}
            </div>

        </div>
    </div>
@endsection

@section('js')

@endsection
