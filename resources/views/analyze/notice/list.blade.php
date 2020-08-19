@extends('layouts.app')
@push('custom_css')
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/bootstrap/bootstrap-datetimepicker.min.css') }}">
@endpush

@push('scripts')
    <script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datetimepicker.min.js') }}'></script>
@endpush
@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>消息推送详情列表</li>
@endsection

@section('pageTitle')
    <div class="page-title">

    </div>
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
                                <h5>推送状态</h5>
                                <select  class="form-control" name="status" id="status">
                                    <option value="all" selected>全部</option>
                                    <option value="0" @if(request()->get('status') === '0') selected @endif>待发送</option>
                                    <option value="1" @if(request()->get('status') === '1') selected @endif>发送中</option>
                                    <option value="2" @if(request()->get('status') === '2') selected @endif>发送成功</option>
                                    <option value="3" @if(request()->get('status') === '3') selected @endif>发送失败</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h5>消息类型</h5>
                                <select  class="form-control" name="msgtype" id="msgtype">
                                    <option value="all" selected>全部</option>
                                    <option value="0" @if(request()->get('msgtype') === '0') selected @endif>文本消息</option>
                                    <option value="1" @if(request()->get('msgtype') === '1') selected @endif>图文消息</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h5>用户群体</h5>
                                <select  class="form-control" name="fltype" id="fltype">
                                    <option value="all" selected>全部</option>
                                    <option value="0" @if(request()->get('fltype') === '0') selected @endif>所有粉丝</option>
                                    <option value="1" @if(request()->get('fltype') === '1') selected @endif>条件粉丝</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <input type="text" class="form-control datetimepicker" id="form-start_date" name="start_date" placeholder="开始日期" value="{{request()->input('start_date')}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <input type="text" class="form-control datetimepicker" id="form-end_date" name="end_date" placeholder="结束日期" value="{{request()->input('end_date')}}" autocomplete="off">
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

    <!-- 公众号分配 -->
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
                                    <th>公众号</th>
                                    <th>创建账号</th>
                                    <th>任务标题</th>
                                    <th>备注</th>
                                    <th>任务类型</th>
                                    <th>消息类型</th>
                                    <th>发送时间</th>
                                    <th>创建时间</th>
                                    <th>用户群体</th>
                                    <th>接收人数</th>
                                    <th>推送状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item->wechat_name}}</td>
                                        <td>{{$item->user_name}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->remark}}</td>
                                        <td>{{$item->task_name}}</td>
                                        <td>{{[0 => '文本消息', 1 => '图文消息'][$item->msgtype]}}</td>
                                        <td>{{$item->send_time}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{['所有粉丝','条件粉丝'][$item->filter_type]}}</td>
                                        <td>{{$item->send_num}}</td>
                                        <td>{!! $item->send_status !!}</td>
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
        // 权限树
        {{--var sel_datas = @json($groupTree);--}}
        {{--var sel_key = '{{request()->get('pdr')}}';--}}
        {{--var tree_content = `<option value="${sel_datas.key}" ${sel_key == sel_datas.key ? 'selected' : ''}>${sel_datas.name}</option>`;--}}

        {{--for (var i in sel_datas['datas']) {--}}
        {{--    tree_content += `<option value="${sel_datas['datas'][i].key}" ${sel_key == sel_datas['datas'][i].key ? 'selected' : ''}>┖──${sel_datas['datas'][i].name}</option>`;--}}
        {{--    for(var k = 0; k < sel_datas['datas'][i].datas.length; k++) {--}}
        {{--        tree_content += `<option value="${sel_datas['datas'][i].datas[k].key}" ${sel_key == sel_datas['datas'][i].datas[k].key ? 'selected' : ''}>┊╌╌┖──${sel_datas['datas'][i].datas[k].name}</option>`;--}}
        {{--    }--}}
        {{--}--}}
        {{--$('#pdr').html(tree_content);--}}

        $('.datetimepicker').datetimepicker({
            forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
            language: 'zh-CN',//显示中文
            format: 'yyyy-mm-dd hh:ii',//显示格式
            // minView: "month",//设置只显示到月份
            // initialDate: new Date(),//初始化当前日期
            autoclose: true,//选中自动关闭
            todayBtn: true//显示今日按钮

        });
    </script>

@endsection
