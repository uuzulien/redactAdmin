@extends('layouts.app')
@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush
<meta name="referrer" content="never">
<style>
    .user-img{
        width: 24px;
        height: 24px;
        border-radius: 100px;
        margin-right: 5px;
    }
    .icon {
        width: 60px!important;
        height: 60px!important;
    }
    .icon:hover {
        transform: scale(3.5);
        transition: all 0.5s;
    }
</style>

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>待审核标题</li>
@endsection

@section('pageTitle')
@endsection

@section('content')
    <!-- 拒绝原因 -->
    @include('popup.audit.reason_title')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline">
                        <div class="form-group  has-success has-feedback">
                            <div class="input-group">
                                <span class="input-group-addon">标题</span>
                                <input type="text" class="form-control" value="{{request()->get('title')}}" name="title"  placeholder="请输入小说标题">
                                <span class="input-group-btn"><button type="submit" class="btn btn-default button" style="height: 36px;"><i class="fa fa-search"></i></button></span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="panel-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a href="{{route('title.audit.novel')}}">小说标题</a></li>
                        <li><a href="{{route('title.audit.active')}}">活动标题</a></li>
                        <li class="active"><a href="{{route('title.audit.signin')}}">签到标题</a></li>
                        <li><a href="{{route('title.audit.history')}}">继续阅读标题</a></li>
                    </ul><br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>标题</th>
                                <th>类型</th>
                                <th>发起人</th>
                                <th>审核结果</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        @if($item->user_id == 1)
                                            系统默认
                                        @else
                                            {{$item->user_name}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 0)
                                            <span class="label label-info">未审核</span>
                                        @elseif($item->status == 1)
                                            <span class="label label-success">通过</span>
                                        @else
                                            <span class="label label-warning" data-toggle="tooltip" title="" data-original-title="{{$item->msg}}" data-placement="left">拒绝</span>
                                        @endif
                                    </td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->audit_time}}</td>
                                    <td>
                                        @if($item->status == 2)
                                            <button data-id="{{$item->id}}" type="button" class="btn btn-sm btn-primary review">复核</button>
                                        @else
                                            <button data-id="{{$item->id}}" type="button" class="btn btn-sm btn-info pass">通过</button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#refuse" data-key="{{$key}}">拒绝</button>
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
        var datas = @json($list)['data'];

        // 人员分配
        $('#refuse').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            // if (data.media_id == data.media_name) {
            //     var media_name = '';
            // }
            var model = $(this);
            model.find('.title').text(data.title);
            model.find('#notice').val(data.msg);
            model.find('.tid').val(data.id);
            // model.find('#media_name').val(media_name);
        });

        $('.pass').click(function () {
            var id = $(this).data('id');

            layer.confirm('确认通过吗？', {
                offset: '200px',
                btn: ['确认','取消']
            }, function(){
                this.disabled = true;
                $.ajax({
                    url: "{{route('wechat.title.update')}}",
                    type: 'post',
                    data:{id:id, status: 1},
                    success:function()
                    {
                        layer.msg('操作成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        var err = eval("(" + XMLHttpRequest.responseText + ")");
                        layer.alert(err.message, function() {
                            location.reload();
                        })
                    }
                });
                return false;
            }, function(){

            });
            return false;
        });

        // 重新审核
        $('.review').click(function () {
            var id = $(this).data('id');

            layer.confirm('是否重新进入待审核状态？', {
                offset:'200px',
                btn: ['是','否']
            }, function(){
                this.disabled = true;
                $.ajax({
                    url: "{{route('wechat.title.update')}}",
                    type: 'post',
                    data:{id:id, status: 0},
                    success:function()
                    {
                        layer.msg('操作成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        var err = eval("(" + XMLHttpRequest.responseText + ")");
                        layer.alert(err.message, function() {
                            location.reload();
                        })
                    }
                });
                return false;
            }, function(){

            });
            return false;
        });
    </script>
@endsection
