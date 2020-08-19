@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>关注用户汇总</li>
@endsection

@section('pageTitle')
    {{--    <div class="row">--}}
    {{--        <div class="col-md-6">--}}
    {{--            <!-- TABLE STRIPED -->--}}
    {{--            <div class="panel">--}}
    {{--                <div class="panel-heading panel-header-bottom">--}}
    {{--                    <h3 class="panel-title"><i class="fa fa-calendar" style="margin-right:5px;"></i>收入汇总</h3>--}}
    {{--                </div>--}}
    {{--                <div class="panel-body form-horizontal" id="curr_month">--}}
    {{--                    <div class="text-primary" style="font-size:30px;margin:5px 0">¥{{$total['order_money']}} </div>--}}
    {{--                    <div style="font-size:12px" class="text-muted">--}}
    {{--                        未提现: <b class="text-danger">¥{{$total['unpay_order']}}</b>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!-- END TABLE STRIPED -->--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>平台名称</th>
                                <th>日期</th>
                                <th>新关注人数</th>
                                <th>取消关注人数</th>
                                <th>净增关注人数</th>
                                <th>取消关注率</th>
                                <th>累积关注人数</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td style="width: 200px">
                                        <span class="label label-info">{{$item['platform_name']}}</span>
                                        <span class="label label-warning">{{$item['nick_name']}}</span>
                                    </td>
                                    <td>{{$item['ref_time']}}</td>
                                    <td>{{$item['new_user']}}</td>
                                    <td>{{$item['cancel_user']}}</td>
                                    <td>{{$item['net_user']}}</td>
                                    <td>{{$item['cancel_rate']}}</td>
                                    <td>{{handleStr($item['cumulate_user'], false)}}</td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <li class="disabled"><span>共{{$list->total()}}条记录</span></li>
                <div class="page">
                    {{$list->appends($app->request->all())->links()}}</div>
            </div>

        </div>
    </div>

@endsection

@section('js')

@endsection
