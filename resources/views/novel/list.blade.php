@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>小说账户配置</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <button
                    class="btn btn-sm btn-primary refuse" data-toggle="modal" data-target="#addModal">
                <span class="glyphicon glyphicon-plus"></span> 新增账号
            </button>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 修改账号 -->
    @include('popup.account.amend')
    <!-- 添加账号 -->
    @include('popup.account.add')

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
                                <h5>所有状态</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">@</span>
                                    <select id="select-id" class="form-control" autocomplete="off" name="status">
                                        <option value="0" >所有状态</option>
                                        <option value="1" @if(request()->get('status')=='1') selected @endif>正常</option>
                                        <option value="2" @if(request()->get('status')=='2') selected @endif>异常</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>公众号</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">微</span>
                                    <input type="text" class="form-control" name="pf_nick" value="{{request()->get('pf_nick')}}" placeholder="请输入公众号名称">
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
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>
                                            <span class="label label-warning">{{$item->platform}}</span>
                                            <span class="label label-info">{{$item['platform_nick']}}</span>
                                        </td>
                                        <td>{{ $item['account']}}</td>
                                        <td>{{ $item->operator}}</td>
                                        <td @if($item['status'] == '2') style="color: red;" @endif>{{ [0=>'初始化',1=>'正常', 2 => '异常'][$item['status']]}}</td>
                                        <td>{{ $item->msg }}</td>
                                        <td>{{ $item['updated_at'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#amendModal" data-key="{{$key}}">修改</button>
                                            {{--                                        <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteAccount({{$item->id  }})" >删除 </span>--}}
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

        var datas = @json($data);

        // 编辑逻辑
        $('#amendModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas['datas'][recipient];
            var option_content = '';
            var pid = datas['datas'][recipient].pid;
            // var option_userid = '';
            // var user_id = datas['datas'][recipient].user_id;

            var modal = $(this);
            modal.find('#pfname').val(data.platform_nick);
            modal.find('#cid').val(data.id);
            modal.find('#username').val(data.account);
            modal.find('#passwd').val(data.password);
            modal.find('#cid').val(data.id);
            var option = modal.find("select[name='pt_type']");

            // 平台来源
            for (var key in datas['platforms']) {
                option_content += `<option value="${key}" ${key == pid ? 'selected' : ''}>${datas['platforms'][key]}</option>`;
            }
            // // 账号管理者
            // for (var key in datas['groups']) {
            //     option_userid += `<option value="${key}" ${key == user_id ? 'selected' : ''}>${datas['groups'][key]}</option>`;
            // }
            // $('#user_id').html(option_userid); // 添加下拉框

            option.html(option_content); // 添加下拉框
        });

        // 添加小说账号
        var option = $("#pt-type");
        var option_name = $("#pf-name");

        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var option_content = '';
            var option_nick_content = '';

            var modal = $(this);

            modal.find('#pfname').val('');
            modal.find('#username').val('');
            modal.find('#passwd').val('');
            modal.find('#act').attr('action',"{{route('account.add_novel')}}");
            console.log(datas['platforms']);
            // 平台来源
            for (var key in datas['platforms']) {
                option_content += `<option value="${key}">${datas['platforms'][key]}</option>`;
            }
            // 公众号名称
            for (var key2 in datas['nick_name']) {
                option_nick_content += `<option value="${datas['nick_name'][key2]}">${datas['nick_name'][key2]}</option>`;
            }
            option.html(option_content); // 添加下拉框
            option_name.html(option_nick_content); // 添加下拉框
        });

        // 删除账号
        function deleteAccount(id) {
            //询问框
            layer.confirm('确定要删除该项吗？', {
                btn: ['确定', '取消'], //按钮
                area: ['320px', '186px'],
                skin: 'demo-class'
            }, function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "delete",
                    dataType: "json",
                    url: '/account/config/del/'+id,
                    success: function (res) {
                        console.log(res);
                        layer.msg('操作成功', {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
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
