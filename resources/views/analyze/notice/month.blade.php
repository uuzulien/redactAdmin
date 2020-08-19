@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>活跃用户汇总数据</li>
@endsection

@section('pageTitle')
@endsection

@section('content')
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
                                    <th>推送情况</th>
                                    <th>素材数据</th>
                                    <th>消息类型数据</th>
                                    <th>任务类型数据</th>
                                    <th>统计数据</th>
                                    <th>充值数据</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    @if(\Carbon\Carbon::parse($key)->isToday())
                                        <tr class="success">
                                            <td colspan="8">Historical news push timeline</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>
                                            已推送：<b>{{$item['succes_num']}}</b>
                                            <br>
                                            待推送：{{$item['wait_num']}}
                                            <br>
                                            发送中：{{$item['into_num']}}
                                        </td>
                                        <td>
                                            推送标题：{{$item['title_num']}}
                                            <br>
                                            单次最佳：
                                            <br>
                                            累计最佳：
                                        </td>
                                        <td>
                                            文本消息：{{$item['text_num']}}
                                            <br>
                                            图文消息：{{$item['news_num']}}
                                        </td>
                                        <td>
                                            活动：{{$item['active_num']}}
                                            <br>
                                            推书：{{$item['book_num']}}
                                            <br>
                                            签到：{{$item['sign_num']}}
                                            <br>
                                            继续阅读：{{$item['history_num']}}
                                            <br>

                                        </td>
                                        <td>
                                            公众号数：{{$item['wid_num']}}
                                            <br>
                                            发送数：<b>{{$item['send_num']}}</b>
                                            <br>
                                            点击量：
                                            <br>
                                            点击率

                                        </td>
                                        <td>
                                            充值金额：
                                            <br>
                                            点击产值：
                                        </td>
                                        <td>
                                            <a href="{{route('wechat.notice.day',['group' => request()->get('group'), 'pdr' => request()->get('pdr'), 'pt_type' => request()->get('pt_type'),'pf_nick' => request()->get('pf_nick'),'start_at' => $key . ' 00:00', 'end_at' => $key . ' 23:59'])}}" class="btn btn-sm btn-primary">查看详情</a>
                                        </td>
                                    </tr>
                                @empty
                                    没有数据
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('js')

@endsection
