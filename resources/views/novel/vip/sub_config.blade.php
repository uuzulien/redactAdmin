@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>子账户信息</li>
@endsection

@section('pageTitle')

@endsection

@section('content')

{{--    <div class="container-padding">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="panel panel-default">--}}

{{--                    <div class="panel-body">--}}
{{--                        <form class="form-inline">--}}
{{--                            <div class="form-group">--}}
{{--                                <h5>运营管理	</h5>--}}
{{--                                <select  class="form-control" name="pdr" id="pdr" style="width:200px;">--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                            <div class="form-group">--}}
{{--                                <h5>小说平台</h5>--}}
{{--                                <div class="input-group">--}}
{{--                                    <span class="add-on input-group-addon">@</span>--}}
{{--                                    <select id="select-id" class="form-control" autocomplete="off" name="pt_type">--}}
{{--                                        <option value="0" >所有平台</option>--}}
{{--                                        @foreach($platforms as $key => $value)--}}
{{--                                            <option value="{{$key}}" @if(request()->get('pt_type')==$key) selected @endif>{{$value}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <h5>所有状态</h5>--}}
{{--                                <div class="input-group">--}}
{{--                                    <span class="add-on input-group-addon">@</span>--}}
{{--                                    <select id="select-id" class="form-control" autocomplete="off" name="status">--}}
{{--                                        <option value="0" >所有状态</option>--}}
{{--                                        <option value="1" @if(request()->get('status')=='1') selected @endif>正常</option>--}}
{{--                                        <option value="2" @if(request()->get('status')=='2') selected @endif>异常</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <h5>公众号</h5>--}}
{{--                                <div class="input-group">--}}
{{--                                    <span class="add-on input-group-addon">微</span>--}}
{{--                                    <input type="text" class="form-control" name="pf_nick" value="{{request()->get('pf_nick')}}" placeholder="请输入公众号名称">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <h5>&nbsp;</h5>--}}
{{--                                <button type="submit" class="btn btn-default">搜索</button>--}}
{{--                            </div>--}}

{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}

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
                                    <th>账户</th>
                                    <th>运营专员</th>
                                    <th>状态</th>
                                    <th>备注</th>
                                    <th>更新时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>
                                            <span class="label label-warning">{{$item->platform_name}}</span>
                                            <span class="label label-info">{{$item->platform_nick}}</span>
                                        </td>
                                        <td>{{ $item->account}}</td>
                                        <td>{{$item->user_name}}</td>
                                        <td @if($item->status == '2') style="color: red;" @endif>{{ [0=>'初始化',1=>'正常', 2 => '异常'][$item->status]}}</td>
                                        <td>{{ $item->msg }}</td>
                                        <td>{{ $item->updated_at}}</td>
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
