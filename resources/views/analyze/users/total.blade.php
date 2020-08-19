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
    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form class="form-inline">

                            @include('layouts.common')
                            <div class="form-group">
                                <h5>小说平台</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">@</span>
                                    <select id="select-id" class="form-control" autocomplete="off" name="pt_type">
                                        <option value="0" >所有平台</option>
                                        @foreach($platforms as $key => $value)
                                            <option value="{{$key}}" @if(request()->get('pt_type')==$key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>公众号</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">微</span>
                                    <input type="text" class="form-control" name="pf_nick" value="{{request()->get('pf_nick')}}" placeholder="请输入公众号名称">
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
                    <div class="panel-heading">

                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>新关注人数</th>
                                    <th>取消关注人数</th>
                                    <th>净增关注人数</th>
                                    <th>取消关注率</th>
                                    <th>累积关注人数</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="success">
                                    <td class="text-center"><b>合 计</b></td>
                                    <td><b>{{$total['new_user']}}</b></td>
                                    <td><b>{{$total['cancel_user']}}</b></td>
                                    <td><b>{{$total['net_user']}}</b></td>
                                    <td><b>{{$total['cancel_rate']}}</b></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{handleStr($item['new_user'], false)}}</td>
                                        <td>{{handleStr($item['cancel_user'], false)}}</td>
                                        <td>{{handleStr($item['net_user'], false)}}</td>
                                        <td>{{$item['cancel_rate']}}</td>
                                        <td>{{handleStr($item['cumulate_user'], false)}}</td>
                                        <td>
                                            <a href="{{route('wechat.month_user.total',['group' => request()->get('group'),'pdr' => request()->get('pdr'), 'pt_type' => request()->get('pt_type'),'pf_nick' => request()->get('pf_nick'),'start_at' => explode('~',$key)[0], 'end_at' => explode('~',$key)[1]])}}" class="btn btn-sm btn-primary">查看详情</a>
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
    </div>

@endsection

@section('js')

@endsection
