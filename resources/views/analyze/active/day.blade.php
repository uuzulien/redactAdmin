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
                                <th>新活跃人数</th>
                                <th>过期活跃人数</th>
                                <th>净活跃人数</th>
                                <th>过期活跃比</th>
                                <th>累积活跃人数</th>
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
                                    <td>{{$item['cumulate_user']}}</td>
                                </tr>
                            @empty
                                没有数据
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-md-10 col-sm-10">
                            <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >
                                第 <input type="text" class="text-center form-control" style="width: 50px;display: inline-block" data-jump="{{$list->url(1)}}" value="{{ $list->currentPage() }}" id="customPage" data-total-page="{{ $list->lastPage() }}" > 页 / 本页 {{$list->count()}} 条数据
                            </span>
                        <span>共{{$list->total()}}条数据 </span>
                    </div>
                    <div class="page">{{$list->appends($app->request->all())->links()}}</div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('js')
<script>
    // 快速跳转
    $("#customPage").keydown(function(e){
        if(e.keyCode == 13){
            var jump = $("#customPage").attr('data-jump');
            jump = jump.substring(0, jump.length-1);
            var page = $('#customPage').val();
            location.href= jump + page;
        }
    });
</script>
@endsection
