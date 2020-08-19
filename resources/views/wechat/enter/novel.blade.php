@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>小说库列表</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <button class="btn btn-info" data-toggle="modal" data-target="#addModal">新建小说</button>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 新建小说 -->
    @include('popup.material.novel')
    <!-- 录入群发消息链接 -->
    @include('popup.material.group_message')
    <!-- 录入客服消息链接 -->
    @include('popup.material.service_message')
    <div class="container-padding"  >
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form class="form-inline">
                            <input type="hidden" class="form-control" value="{{request()->get('act', 1)}}" name="act">
                            <div class="form-group">
                                <h5>客服消息	</h5>
                                <select  class="form-control" name="service_status" id="status" style="width:200px;">
                                    <option value="all">全部</option>
                                    <option value="0" @if(request()->get('service_status','all')=='0') selected @endif>未审核</option>
                                    <option value="1" @if(request()->get('service_status')=='1') selected @endif>通过</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h5>群发消息	</h5>
                                <select  class="form-control" name="group_status" id="status" style="width:200px;">
                                    <option value="all">全部</option>
                                    <option value="0" @if(request()->get('group_status','all')=='0') selected @endif>未审核</option>
                                    <option value="2" @if(request()->get('group_status')=='2') selected @endif>通过</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h5>小说标题</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">标题</span>
                                    <input type="text" class="form-control" value="{{request()->get('book_name')}}" name="book_name"  placeholder="请输入小说书名">
                                </div>
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
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>小说平台</th>
                                        <th>书名</th>
                                        <th>书号</th>
                                        <th>字数</th>
                                        <th>性别频度</th>
                                        <th>分类</th>
                                        <th>热度</th>
                                        <th>客服消息</th>
                                        <th>群发消息</th>
{{--                                        <th>录入人员</th>--}}
                                        <th>录入时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $key => $item)
                                        <tr>
                                            <td>{{$item['platform_name']}}</td>
                                            <td>{{$item['name']}}</td>
                                            <td>{{$item['number']}}</td>
                                            <td>{{round($item['word_count']/10000, 1)}}万</td>
                                            <td>{{['1' => '男频', '2' => '女频'][$item['sex']]}}</td>
                                            <td>{{$item['type_name']}}</td>
                                            <td>{{$item['hot']}}℃</td>
                                            <td>{{$item['service_link'] ? '有' : '无'}}({{['0' => '未审核','1' => '通过', '2' => '拒绝'][$item['service_status']]}})</td>
                                            <td>{{$item['group_link'] ? '有' : '无'}}</td>
{{--                                            <td>{{$item['user_name']}}</td>--}}
                                            <td>{{$item['created_at']}}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#NovelLink" data-key="{{$key}}">客服消息</button>
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addChapter" data-key="{{$key}}">群发消息</button>
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
            location.href= jump + page ;
        }
    });
    var datas = @json($list)['data'];
    var is_admin = {{Auth::user()->roles->first()->is_admin ?? 0}};

    // 群发消息
    $('#addChapter').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('key');
        var data = datas[recipient];
        var modal = $(this);

        modal.find('.title').val(data.name);
        modal.find('.num').val(data.number);
        modal.find('.bookid').val(data.id);
        modal.find('.service-link').val(data.group_link);
        if (data.chapter_id - 2 > 0){
            $('.chapter_num option')[data.chapter_id - 2].selected = true;
        }else {
            $('.chapter_num option')[0].selected = true;
        }
        // 已通过的禁止修改
        if (data.service_status == 1 && is_admin < 3){
            modal.find('.btn-primary').prop('disabled', true);

        }else{
            modal.find('.btn-primary').prop('disabled', false);
        }
    });
    // 客服消息
    $('#NovelLink').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('key');
        var data = datas[recipient];
        var modal = $(this);

        modal.find('.title').val(data.name);
        modal.find('.num').val(data.number);
        modal.find('.bookid').val(data.id);
        modal.find('.group-link').val(data.service_link);
        // 已通过的禁止修改
        if (data.service_status == 1 && is_admin < 1){
            modal.find('.btn-primary').prop('disabled', true);

        }else{
            modal.find('.btn-primary').prop('disabled', false);
        }
    });

    var platform_sel = $("#pid");
    platform_sel.change(function () {
        var pid = $("#pid option:selected").val();
        var option_chapter_content = '';

        $.get('/material/enter/novel/get_type?pid='+ pid, function (res) {
            var data = res.data;

            for (var key in data) {
                option_chapter_content += `<option value="${data[key]['id']}" ${data[key]['id'] == 47 ? 'selected' :''}>${data[key]['name']}</option>`;
            }
            if (!option_chapter_content){
                option_chapter_content = `<option value="0">系统未录入分类标签</option>`;
            }
            $('#typeid').html(option_chapter_content);

            console.log(data);
        })
    });

</script>
@endsection
