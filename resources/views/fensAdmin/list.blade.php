@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>粉丝明细</li>
@endsection

@section('pageTitle')
@endsection

@section('content')
    <!-- 公众号分配 -->
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
                                <span class="input-group-btn"><button type="submit" class="btn btn-default button" style="height: 36px;"><i class="fa fa-search"></i></button></span>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>昵称</th>
                                <th>来源</th>
                                <th>粉丝id</th>
                                <th>关注时间</th>
                                <th>地区</th>
                                <th>标签</th>
                                <th>性别</th>
                                <th>备注</th>
{{--                                <th>操作</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td><img class="user-img" src="{{$item->headimgurl}}"> {{$item->nickname}}</td>
                                    <td>
                                        <div class="label label-info">{{['ADD_SCENE_SEARCH' => '公众号搜索', 'ADD_SCENE_ACCOUNT_MIGRATION' => '公众号迁移', 'ADD_SCENE_PROFILE_CARD' => '名片分享',
                                         'ADD_SCENE_QR_CODE' => '扫描二维码','ADD_SCENE_PROFILE_LINK' => '图文页内名称点击', 'ADD_SCENE_PROFILE_ITEM' => '图文页右上角菜单',
                                         'ADD_SCENE_PAID' => '支付后关注', 'ADD_SCENE_OTHERS' => '其他'][$item->subscribe_scene] ?? '其他'}}
                                        </div>
                                        <br>
                                        <br>
                                        <div class="label label-warning">{{['1' => '已关注', '0' => '未关注'][$item->subscribe]}}</div>
                                    </td>
                                    <td>{{$item->id}}</td>
                                    <td>{{date('Y-m-d H:i:s', $item->subscribe_time)}}</td>
                                    <td>{{$item->country . $item->province . $item->city}}</td>
                                    <td></td>
                                    <td>{{['1' => '男', '2' => '女', '0' => '未知'][$item->sex] ?? '未知'}}</td>

                                    <td>{{$item->remark}}</td>
{{--                                    <td>--}}
                                        {{--                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#checkModal" data-key="{{$key}}">切入</button>--}}
                                        {{--                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transModal" data-key="{{$key}}">转移</button>--}}
                                        {{--                                        <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteAccount({{$item->id  }})" >粉丝更新 </span>--}}
{{--                                    </td>--}}
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <li class="disabled"><span>共{{$list->total()}}条记录</span></li>

                <div class="page">{{$list->appends($app->request->all())->links()}}</div>
            </div>

        </div>
    </div>
@endsection

@section('js')

@endsection
