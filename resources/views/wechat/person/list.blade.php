@extends('layouts.app')
@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>公众号授权</li>
    <style>
        .icon {
            width: 60px!important;
            height: 60px!important;
        }
        tr td{
            vertical-align:middle!important;
        }
        .qrcode:hover {
            transform: scale(3.5);
            transition: all 0.5s;
        }
        .user-permission .permission-heading {
            height: 40px;
            line-height: 40px;
            padding-left: 30px;
            border-bottom: 1px solid #e7e7eb;
            cursor: pointer;
        }
        .wxv{
            width: 500px;
        }
        .wxv span{
            display: inline-block;
        }
        .wx-check {
            width: 18px;
            height: 18px;
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            line-height: 18px;
            position: relative;
            margin-right: 10px;
        }

        .wx-check::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            background: #fff;
            width: 100%;
            height: 100%;
            border: 1px solid #d9d9d9
        }
        .wx-check:hover::before {
            content: "\2713";
            background-color: #fff;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            color: rgb(201,202,207);
            font-size: 12px;
            font-weight: bold;
        }
        .wx-check:checked::before {
            content: "\2713";
            background-color: #fff;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            color: rgb(92,144,202);
            font-size: 12px;
            font-weight: bold;
        }

    </style>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <div class="btn btn-sm btn-primary refuse" data-toggle="modal" data-target="#addPerson"><span class="fa fa-plus-square" aria-hidden="true"></span> 添加个人号</div>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 添加账号 -->
    @include('popup.account.person.add')
    <!-- 账户信息修改 -->
    @include('popup.account.person.amend')
    <!-- 关联平台 -->
{{--    @include('popup.account.person.tag')--}}

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
                                    <th>微信昵称</th>
                                    <th>微信号</th>
                                    <th>密码</th>
                                    <th>用途</th>
                                    <th>归属扫码机</th>
                                    <th>关联授权号</th>
                                    <th>手机号</th>
                                    <th>实名姓名</th>
                                    <th>身份证号</th>
                                    <th>支付密码</th>
                                    <th>好友数量</th>
                                    <th>录入人员</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item['nick_name']}}</td>
                                        <td>{{$item['account']}}</td>
                                        <td>{{$item['password']}}</td>
                                        <td>{{$item['purpose']}}</td>
                                        <td>{{$item['scan_moblie']}}</td>
                                        <td>{{$item['count_num']}}</td>
                                        <td>{{$item['phone']}}</td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['idcard']}}</td>
                                        <td>{{$item['payment_code']}}</td>
                                        <td>{{$item['fens_num']}}</td>
                                        <td>{{$item['user_name']}}</td>
                                        <td>
                                            <span type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#amendModal" data-key="{{$key}}">编辑</span>
                                            @if($item['count_num'])
                                            <a type="button" href="{{route('person.wechat.detail', ['act' => $item['scan_moblie']])}}" class="btn btn-sm btn-primary">查看明细</a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger deleteRes" data-id="{{$item['id']}}">删除</button>
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
        $('.deleteRes').click(function () {
            var id = $(this).data('id');

            layer.confirm('删除不可恢复，确定要删除吗？', {
                btn: ['确认','取消'],
                offset: '200px',
                skin: 'demo-class'
            }, function(){
                this.disabled = true;
                $.ajax({
                    url: "/vv/personal/delete/" + id,
                    type: 'delete',
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

        var datas = @json($list)['data'];

        // 编辑的逻辑
        $('#amendModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];

            var modal = $(this);
            modal.find('#person-id').val(data.id);
            modal.find('#nick-name').val(data.nick_name);
            modal.find('#account').val(data.account);
            modal.find('#password').val(data.password);
            modal.find('#fens-num').val(data.fens_num);
            modal.find('#purpose').val(data.purpose);
            modal.find('#scan-moblie').val(data.scan_moblie);
            modal.find('#phone').val(data.phone);
            modal.find('#name').val(data.name);
            modal.find('#idcard').val(data.idcard);
            modal.find('#payment-code').val(data.payment_code);

        });
    </script>

@endsection
