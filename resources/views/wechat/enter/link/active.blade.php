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
            <button class="btn btn-info" data-toggle="modal" data-target="#addModal">新建链接</button>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 公众号分配 -->
    @include('popup.material.link.active')
    <div class="row">
        <div class="col-md-12">



            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>活动名称</th>
                                <th>发起人</th>
                                <th>审核结果</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $item)
                                <tr>
                                    <td>{{$item->remark}}</td>
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
        var book_control = $("#type");
        var book_sel = $("#book_id");

        book_control.change(function () {
            var pid = $("#type option:selected").val();
            if (pid == 2){
                $('.book_style').show();
            }else{
                $('.book_style').hide();
            }
            if (pid != 1){
                $('.remark').hide();
            }else {
                $('.remark').show();
            }
        });

        book_sel.change(function () {
            var book_id = $("#book_id option:selected").val();
            var option_chapter_content = '';
            console.log(book_id);
            $.get('/material/enter/list/get_chapter?book_id='+ book_id, function (res) {
                var data = res.data;

                for (var key in data) {
                    option_chapter_content += `<option value="${data[key]['id']}"}>${data[key]['name']}</option>`;
                }
                if (option_chapter_content){
                    $('#chapter').html(option_chapter_content);
                }
                console.log(data);
            })
        });
        // var kinds=$("#book_name option:selected").val();

        // console.log(kinds);
    </script>
@endsection
