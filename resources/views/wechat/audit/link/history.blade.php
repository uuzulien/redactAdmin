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
    <li>微信推送链接审核</li>
@endsection

@section('pageTitle')
@endsection

@section('content')
    <!-- 拒绝原因 -->
    @include('popup.audit.reason_link')
    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form class="form-inline">
                            <div class="form-group">
                                <h5>审核结果	</h5>
                                <select  class="form-control" name="status" id="status" style="width:200px;">
                                    <option value="all">全部</option>
                                    <option value="0" @if(request()->get('status','all')=='0') selected @endif>未审核</option>
                                    <option value="2" @if(request()->get('status')=='2') selected @endif>拒绝</option>
                                </select>
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
                    <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li><a href="{{route('wechat.audit.novel')}}">小说管理</a></li>
                            <li><a href="{{route('wechat.audit.active')}}">活动管理</a></li>
                            <li><a href="{{route('wechat.audit.signin')}}">签到管理</a></li>
                            <li class="active"><a href="{{route('wechat.audit.history')}}">继续阅读管理</a></li>
                        </ul><br>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>平台名称</th>
                                    <th>链接地址</th>
                                    <th>发起人</th>
                                    <th>审核结果</th>
                                    <th>申请时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>
                                            @if(request()->get('status', 'all') == '0')
                                                <label class="control-inline fancy-checkbox"><input type="checkbox" name="ids[]" id="ids" value="{{$item->id}}"><span></span></label>
                                            @endif
                                            {{--                                        <label class="label label-warning">{{$item->platform_name}}</label>--}}
                                            <label class="label label-info">{{$item->nick_name}}</label>
                                        </td>
                                        <td>{{$item->href}}</td>
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

                                @if($list->count()  && request()->get('status', 'all') == '0')
                                    <tr>
                                        <td colspan="6"><div class="form-group"><label class="control-inline">
                                                    <input type="checkbox" id="checkall" value="no"><span></span> 全选/反选所有 </label></div>
                                        </td>
                                    </tr>
                                @endif
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
            var ids = [];
            $(".fancy-checkbox input").each(function () {
                var that = $(this);
                that.prop('checked') ? ids.push(that.val())  : ids;
            });
            // 批量多选
            if (ids.length){
                layer.confirm(`当前已选择 ${ids.length} 个，确认要通过吗？`, {
                    offset: '200px',
                    btn: ['确认','取消']
                }, function(){
                    this.disabled = true;
                    $.ajax({
                        url: "{{route('wechat.link.update')}}",
                        type: 'post',
                        data:{id:ids, status: 1},
                        success:function()
                        {
                            layer.msg('操作成功', {
                                offset:'200px',
                                icon: 1,
                                time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                            }, function(){
                                // location.reload();
                            });
                        },
                        error:function(XMLHttpRequest, textStatus, errorThrown)
                        {
                            var err = eval("(" + XMLHttpRequest.responseText + ")");
                            layer.alert(err.message, function() {
                                // location.reload();
                            })
                        }
                    });

                }, function(){

                });
                return false;
            }
            layer.confirm('确认通过吗？', {
                offset:'200px',
                btn: ['确认','取消']
            }, function(){
                this.disabled = true;
                $.ajax({
                    url: "{{route('wechat.link.update')}}",
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
                    url: "{{route('wechat.link.update')}}",
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

        $(function () {
            selectChannelAll.init();
        });

        //// 批量全选
        var selectChannelAll=new function(){
            this.init=function(){
                $("#checkall").bind('click',function(event){
                    event.stopPropagation();
                    var checked=$(this).prop("checked");
                    _checkedItem(checked);
                });
            }
            this.getCheckNum=function(){
                var total=0;
                $('.fancy-checkbox input').each(function(index,element){
                    total+=$(this).prop('checked')?1:0;
                });
                return total;
            }
            this.clearCheck=function(){
                _checkedItem(false);
            }
            function _checkedItem(checked){
                $('.fancy-checkbox input').prop('checked',checked);
            }
        }
    </script>
@endsection
