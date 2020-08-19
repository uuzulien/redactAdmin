@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>收入汇总</li>
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
                                <th>日期</th>
                                <th>充值单数</th>
                                <th>充值金额</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="success">
                                <td class="text-center"><b>合 计</b></td>
                                <td><b>{{$total['order_num']}}</b></td>
                                <td><b>{{$total['order_money']}}</b> 元</td>
                                <td></td>
                            </tr>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$item['order_num']}}</td>
                                    <td>￥{{$item['order_money']}}</td>
                                    <td>
                                        <a href="{{route('wechat.day_order.pay_list',['group' => request()->get('group'),'pdr' => request()->get('pdr'),'user_id' => request()->get('user_id'), 'pt_type' => request()->get('pt_type'),'pf_nick' => request()->get('pf_nick'),'time_at' => $key])}}" class="btn btn-sm btn-primary">查看详情</a>
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
