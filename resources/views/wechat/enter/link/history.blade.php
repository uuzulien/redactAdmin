@extends('layouts.app')
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
    <li>微信推送链接录入</li>
@endsection

@section('pageTitle')

    <div class="page-title">
        <h2>
            @if(!$list->count())
                <button class="btn btn-info" data-toggle="modal" data-target="#addModal">新建链接</button>
            @endif
        </h2>

    </div>
@endsection

@section('content')
    <!-- 公众号分配 -->
    @include('popup.material.link.history')
    <!-- 链接修改 -->
    @include('popup.material.edit.history')

    <div class="row">
        <div class="col-md-12">



            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>发起人</th>
                                <th>链接地址</th>
                                <th>审核结果</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>
                                        @if($item->user_id == 1)
                                            系统默认
                                        @else
                                            {{$item->user_name}}
                                        @endif
                                    </td>
                                    <td>{{$item->href}}</td>
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
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#historyLink" data-key="{{$key}}">修改链接</button>
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
        var is_admin = {{Auth::user()->roles->first()->is_admin ?? 0}};

        // 继续阅读修改
        $('#historyLink').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];

            var modal = $(this);
            modal.find('#href').val(data.href);
            // 已通过的禁止修改
            if (data.status == 1 && is_admin < 3){
                modal.find('.btn-primary').prop('disabled', true);

            }else{
                modal.find('.btn-primary').prop('disabled', false);
            }
        });
    </script>
@endsection
