@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>进行中</li>
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
                        <li class="active"><a href="javascript:void(0)">年审中</a></li>
                        <li><a href="{{route('wechat.verify.complete')}}">已年审</a></li>
                    </ul><br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>平台名称</th>
                                <th>审核人员</th>
                                <th>认证日期</th>
                                <th>进度</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->nick_name}}</td>
                                    <td>{{$item->user_name}}</td>
                                    <td>{{$item->verify_date}}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <label class="label label-info">待打款</label>
                                        @else($item->status)
                                            <label class="label label-warning">已打款</label>
                                        @endif
                                    </td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        @if(Auth::user()->role_id == 12)
                                        <button data-id="{{$item->id}}" data-wid="{{$item->wid}}" data-time="{{$item->verify_date}}" type="button" class="btn btn-sm btn-warning checkAudit">已打款</button>
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
        $('.checkAudit').click(function () {
            var id = $(this).data('id'),
                wid = $(this).data('wid'),
                time = $(this).data('time');
            this.disabled = true;
            layer.confirm('是否已经完成打款？', {
                offset: '200px',
                btn: ['是','否'],

            }, function(){
                $.ajax({
                    url: "{{route('wechat.verify.update')}}",
                    type: 'post',
                    data:{id:id,wid:wid,verify_date:time, status: 2},
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
