@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>客服消息</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="{{route('wechat.custom_msg.add')}}" class="btn btn-sm btn-primary refuse"><span class="fa fa-plus-square" aria-hidden="true"></span> 添加客服消息群发</a>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 公众号分配 -->
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>创建账号</th>
                                <th style="width: 360px;">小说标题</th>
                                <th>书名</th>
                                <th>任务类型</th>
                                <th>类型</th>
                                <th>发送时间</th>
                                <th>创建时间</th>
                                <th>用户群体</th>
                                <th>接收人数</th>
                                <th>推送状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->user_name}}</td>
                                    <td>
                                        @if($item->msgtype == 0)
                                            {!! $item->temp_text !!}
                                        @else
                                            {{$item->title}}
                                        @endif
                                    </td>
                                    <td>{{$item->book_name}}</td>
                                    <td>{{$item->task_name ?? '未知'}}</td>
                                    <td>{{[0 => '文本消息', 1 => '图文消息'][$item->msgtype]}}</td>
                                    <td>{{$item->send_time}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{['所有粉丝','条件粉丝'][$item->filter_type]}}</td>
                                    <td>{{$item->send_num}}</td>
                                    <td>{!! $item->send_status !!}</td>
                                    <td>
{{--                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#checkModal" data-key="{{$key}}">用相同条件群发 </button>--}}
                                        <a type="button" class="btn btn-sm btn-primary" href="{{route('wechat.custom_msg.edit',['id' => $item->id])}}">编辑</a>
                                        @if($item->status == 0)
                                        <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteAccount({{$item->id  }})" >删除 </span>
                                        @endif
                                    </td>
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
    // 删除账号
    function deleteAccount(id) {
        //询问框
        layer.confirm('确定要删除该项吗？', {
            btn: ['确定', '取消'], //按钮
            area: ['320px', '186px'],
            offset:'200px',
            skin: 'demo-class'
        }, function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                type: "delete",
                dataType: "json",
                url: '/group_send/list/del_notice/'+id,
                success: function (res) {

                    layer.msg('操作成功', {
                        offset:'200px',
                        icon: 1,
                        time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                    }, function(){
                        location.reload();
                    });
                },
                error(res){
                    console.log(res.responseJSON.msg);
                    layer.open({
                        title:false,
                        content:'<span>'+res.responseJSON.msg+'</span>',
                        btn:false,
                        time:3000,
                        closeBtn:0,
                    });
                }
            });
        }, function () {

        });
    }
</script>
@endsection
