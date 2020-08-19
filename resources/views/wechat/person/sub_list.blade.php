@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>名下授权公众号</li>
@endsection

@section('pageTitle')

@endsection

@section('content')


    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">

                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default">


                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>平台名称</th>
                                    <th>扫码机器</th>
                                    <th>运营专员</th>
                                    <th>创建时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>
                                            <span class="label label-warning">{{$item->platform_name}}</span>
                                            <span class="label label-info">{{$item->nick_name}}</span>
                                        </td>
                                        <td>{{ $item->scan_moblie}}</td>
                                        <td>{{$item->user_name}}</td>
                                        <td>{{ $item->created_at}}</td>
                                    </tr>
                                @empty
                                    没有数据
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xs-12 col-md-10 col-sm-10">
                            <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >
                                第 {{ $list->currentPage() }} 页 / 本页 {{$list->count()}} 条数据
                            </span>
                            <span>共{{$list->total()}}条数据 </span>
                        </div>
                        <div class="page">{{$list->appends($app->request->all())->links()}}</div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
