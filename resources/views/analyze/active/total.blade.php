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
                                    <th>新增活跃人数<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="累积每日的互动次数"></i></th>
                                    <th>过期活跃人数<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="累积当日过期活跃数"></i></th>
                                    <th>过期活跃比</th>
                                    <th>净活跃人数<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="当前活跃粉丝数量，可收到客服消息人数"></i></th>
                                    <th>累积活跃人数</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="success">
                                    <td class="text-center"><b>合 计</b></td>
                                    <td><b>{{$total['new_user']}}</b></td>
                                    <td><b>{{$total['cancel_user']}}</b></td>
                                    <td><b>{{$total['cancel_rate']}}</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$item['new_user']}}</td>
                                        <td>{{$item['cancel_user']}}</td>
                                        <td>{{$item['cancel_rate']}}</td>
                                        <td>{{$item['net_user']}}</td>
                                        <td>{{$item['cumulate_user']}}</td>
                                        <td>
                                            <span class="btn btn-sm btn-default" data-toggle="modal" data-target="#trendModal" data-day="all">互动详情</span>
                                            <a href="{{route('wechat.month_user.active',['group' => request()->get('group'), 'pdr' => request()->get('pdr'), 'pt_type' => request()->get('pt_type'),'pf_nick' => request()->get('pf_nick'),'start_at' => explode('~',$key)[0], 'end_at' => explode('~',$key)[1]])}}" class="btn btn-sm btn-primary">查看详情</a>
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
