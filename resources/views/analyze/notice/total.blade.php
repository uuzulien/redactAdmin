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
                                    <tr>
                                        <td>{{$key}}</td>

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
                                            已推送天数：{{$item['day_num']}}
                                            <br>
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
{{--                                            <span class="btn btn-sm btn-default" data-toggle="modal" data-target="#trendModal" data-day="all">互动详情</span>--}}
                                            <a href="{{route('wechat.notice.month',['group' => request()->get('group'), 'pdr' => request()->get('pdr'), 'pt_type' => request()->get('pt_type'),'pf_nick' => request()->get('pf_nick'),'start_at' => explode('~',$key)[0], 'end_at' => explode('~',$key)[1]])}}" class="btn btn-sm btn-primary">查看详情</a>
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
