@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>已完成</li>
@endsection

@section('pageTitle')

@endsection

@section('content')
    <!-- 公众号分配 -->
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a href="{{route('wechat.verify.wait')}}">待年审</a></li>
                        <li><a href="{{route('wechat.verify.begin')}}">年审中</a></li>
                        <li class="active"><a href="javascript:void(0)">已年审</a></li>
                    </ul><br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>平台名称</th>
                                <th>运营专员</th>
                                <th>上次认证到期时间</th>
                                <th>本次认证到期时间</th>
                                <th>状态</th>
                                <th>完成时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->nick_name}}</td>
                                    <td>{{$item->user_name}}</td>
                                    <td>{{$item->before_verify_date}}</td>
                                    <td>{{$item->complete_verify_date}}</td>
                                    <td>已年审</td>
                                    <td>{{$item->updated_at}}</td>
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

@endsection
