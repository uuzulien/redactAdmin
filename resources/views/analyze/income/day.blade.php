@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>收入汇总</li>
@endsection

@section('pageTitle')

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
                                <th>投放专员</th>
                                <th>日期</th>
                                <th>充值单数</th>
                                <th>充值金额</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="success">
                                <td class="text-center" colspan="3"><b>合 计</b></td>
                                <td><b>{{$total['order_num']}}</b></td>
                                <td><b>{{$total['order_money']}}</b> 元</td>
                            </tr>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td style="width: 200px">
                                        <span class="label label-warning">{{$item['platform_name']}}</span>
                                        <span class="label label-info">{{$item['platform_nick']}}</span>
                                    </td>
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['order_time']}}</td>
                                    <td>{{$item['order_num']}}</td>
                                    <td>￥{{$item['order_money']}}</td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <li class="disabled"><span>共{{$list->count()}}条记录</span></li>

                {{--                <div class="page">{{$list->appends($app->request->all())->links()}}</div>--}}
            </div>

        </div>
    </div>
@endsection

@section('js')

@endsection
