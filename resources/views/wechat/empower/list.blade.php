@extends('layouts.app')
@push('custom_css')
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/bootstrap/bootstrap-datetimepicker.min.css') }}">
@endpush

@push('scripts')
    <script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datetimepicker.min.js') }}'></script>
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
            <a href="{{route('wechat.empower.add')}}" class="btn btn-sm btn-primary refuse"><span class="fa fa-plus-square" aria-hidden="true"></span> 添加公众号</a>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 投放渠道，是否投放中 -->
    @include('popup.wechatInfo.advert_sign')
    <!-- 认证到期日期 -->
    @include('popup.wechatInfo.verify_date')
    <!-- 公众号分配 -->
    @include('popup.wechatInfo.allocate')
    <!-- 公众号批量转移 -->
    @isset($wechatInfoList[0])
        @include('popup.wechatInfo.transfer')
    @endisset
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
                                    <select class="form-control" autocomplete="off" name="pt_type">
                                        <option value="-1" >所有平台</option>
                                        <option value="0" @if(request()->get('pt_type', -1) == 0) selected @endif>未选择</option>
                                        @foreach($platforms as $key => $value)
                                            <option value="{{$key}}" @if(request()->get('pt_type')==$key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>投放平台</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">投</span>
                                    <select class="form-control" autocomplete="off" name="cost_id">
                                        <option value="-1" >所有平台</option>
                                        <option value="0" @if(request()->get('cost_id', -1) == 0) selected @endif>未选择</option>
                                        @foreach($advert as $key => $value)
                                            <option value="{{$key}}" @if(request()->get('cost_id')==$key) selected @endif>{{$value}}</option>
                                        @endforeach
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
                                <h5>主体名称</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">公</span>
                                    <input type="text" class="form-control" name="company" value="{{request()->get('company')}}" placeholder="请输入主体名">
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>粉丝排序</h5>
                                <div class="input-group">
                                    <select id="select-id" class="form-control" autocomplete="off" name="fens_order">
                                        <option value="0" >默认</option>
                                        <option value="asc">升序</option>
                                        <option value="desc">降序</option>
                                    </select>
                                </div>
                            </div>
{{--                            @if( in_array(Auth::id(), [1,54]))--}}
                            <div class="form-group">
                                <h5>详情信息</h5>
                                <div class="input-group">
                                    <select id="select-id" class="form-control" autocomplete="off" name="infos">
                                        <option value="0">全部</option>
                                        <option value="1" @if(request()->get('infos') == '1') selected @endif>有</option>
                                        <option value="2" @if(request()->get('infos') == '2') selected @endif>无</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <h5>投放中</h5>
                                <div class="input-group">
                                    <select id="select-id" class="form-control" autocomplete="off" name="iscost">
                                        <option value="all" >全部</option>
                                        <option value="0" @if(request()->get('iscost','all') == '0') selected @endif>否</option>
                                        <option value="1" @if(request()->get('iscost') == '1') selected @endif>是</option>
                                    </select>
                                </div>
                            </div>
{{--                            @endif--}}
                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <button type="submit" class="btn btn-default">搜索</button>
                            </div>
                            <div class="form-group pull-right">
                                <h5>&nbsp;</h5>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#allocateModal">批量分配公众号</button>
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
                                    <th>账户logo</th>
                                    <th>运营专员</th>
                                    <th>平台名称</th>
                                    <th>投放渠道</th>
                                    <th>微信号</th>
                                    <th>状态</th>
                                    <th>类型</th>
                                    <th>主体名称</th>
                                    <th>总粉丝数</th>
                                    <th>活跃粉丝数量</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>
                                            <img class="img-responsive icon"  src="{{$item->head_img}}?ext=1591259740">
                                        </td>
                                        <td onclick="showtr({{$key}})">{{$item->user_name}}</td>
                                        <td onclick="showtr({{$key}})">

                                            <label class="label label-primary">{{['0' => '全部','1' => '男频', '2' => '女频'][$item->sex]}}</label>
                                            <label class="label label-warning">{{($origin[$item->id] ?? '') . ($item->platform_name ?? '未选择')}}</label>
                                            <label class="label label-info">{{$item->nick_name}}</label>
                                        </td>
                                        <td onclick="showtr({{$key}})">
                                            <label class="label label-warning">{{$item->advert_name ?? '无'}}</label>
                                            <label class="label label-info">{{['0' => '未投放', '1' => '投放中'][$item->is_cost]}}</label>
                                        </td>
                                        <td onclick="showtr({{$key}})">{{$item->alias}}</td>
                                        <td onclick="showtr({{$key}})">
                                            @if($item->is_power)
                                                已授权
                                            @else
                                                未授权
                                            @endif
                                        </td>
                                        <td onclick="showtr({{$key}})">
                                            <span class="label label-info">{{$item->service_type == 2 ? '服务号' : '订阅号'}}</span>
                                            <span class="label label-warning">{{$item->verify_type >= 0 ? '认证' : '未认证'}}</span>
                                        </td>
                                        <td>{{$item->principal_name}}</td>
                                        <td onclick="showtr({{$key}})">{{$item->user_total}}</td>
                                        <td onclick="showtr({{$key}})">{{$item->active_user_num}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#verifyDate" data-key="{{$key}}">认证日期</button>
                                            @if(Auth::user()->rolesDetail->first()->id != 14)
                                                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#signinModal" data-key="{{$key}}">标记</button>
                                                <a class="btn btn-sm btn-info" href="{{route('wechat.account.switch',['id' => $item->id])}}">切入</a>
{{--                                                <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteAccount({{$item->id  }})" >删除 </span>--}}
                                            @endif
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transModal" data-key="{{$key}}" @if(Auth::user()->userRole->is_admin < 1) disabled @endif>转移</button>
                                        </td>
                                    </tr>
{{--                                    @if( in_array(Auth::id(), [1,54]))--}}
                                    <tr style="padding: 0px; border-width: 0px; border-top-style: initial; border-right-style: initial; border-bottom-style: solid; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-bottom-color: rgb(221, 221, 221); border-left-color: initial; border-image: initial; display: none;" class="tr_{{$key}} rm_{{$key}} tr_only">
                                        <td colspan="17" style="padding:0px;border:0px;">

                                            <table class="table table-bordered table-striped" style="width: 100%;padding:0px;border-left: 0px;">
                                                <tbody>
                                                <tr style="border-left: 0px;">
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">原始ID</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">登录邮箱</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">邮箱密码</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">公众号密码</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">扫码机</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">认证日期</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">法人</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">管理员</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">身份证号</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">微信号</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">手机号</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">运营者</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">微信号</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">微信昵称</td>
                                                    <td style="width:100px;border-left: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">授权时常</td>

                                                </tr>
                                                <tr class="edit" data-wid="{{$item->id}}">
                                                    <td style="border-left: 0px;border-bottom: 0px;font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;">{{$item->original}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="email">{{$item->email}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="email_passwd">{{$item->email_passwd}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="wechat_passwd">{{$item->wechat_passwd}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="scan_moblie">{{$item->scan_moblie}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="verify_date">{{$item->verify_date}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="legal_person">{{$item->legal_person}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="account_manage">{{$item->account_manage}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="idcard">{{$item->idcard}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="wechat_admin_id">{{$item->wechat_admin_id}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="mobile">{{$item->mobile}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="operate_name">{{$item->operate_name}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="wechat_operate_id">{{$item->wechat_operate_id}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="wechat_operate_nick">{{$item->wechat_operate_nick}}</td>
                                                    <td style="font-size: 12px!important;border-right: 0px!important;padding:13px 14px !important;" data-type="auth_date">{{$item->auth_date}}</td>
                                                </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                    </tr>
{{--                                    @endif--}}
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

        var datas = @json($list)['data'];

        var userTree = @json($userTree);
        // 人员分配
        $('#transModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var option_group_content = '';
            var modal = $(this);

            // 公众号调动
            // for (var key in userTree) {
            //     option_group_content += `<option value="${userTree[key]['id']}" ${userTree[key]['id'] == data['user_id'] ? 'selected' : ''}>${userTree[key]['name']}</option>`;
            // }
            // modal.find("select[name='user_id']").html(option_group_content);
            if ($('#gid option:selected').val() > 0){
                changeUser(users, pdr ,modal.find("#uid"))
            }

            modal.find('#name').val(data.nick_name);
            modal.find('#wid').val(data.id);
        });
        // 投放广告的标签
        $('#signinModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var modal = $(this);

            modal.find('#name').val(data.nick_name);
            modal.find('#wid').val(data.id);
            modal.find('#is-cost').val(data.is_cost);
            modal.find('#cost-id').val(data.cost_id);
            modal.find('#sex').val(data.sex);
        });
        // 公众号认证日期
        $('#verifyDate').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var modal = $(this);

            modal.find('#name').val(data.nick_name);
            modal.find('#wid').val(data.id);
            modal.find('.verify_date').val(data.verify_date);
            if (data.verify_date && data.status != 1){
                modal.find('#verify_date').attr('disabled', true);
            }
        });

        function showtr(id,o){
            $('.tr_only').each(function () {
                if (!$(this).hasClass("tr_"+id)){
                    $(this).hide();
                }
            });
            $(".tr_"+id).toggle();
            if($(".tr_"+id).css("display")=='none'){
                $(".tr_"+id).css('border-bottom','0px solid #ddd');
            }else{
                $(".tr_"+id).css('border-bottom','0px solid #ddd');
            }
        }
        // 监听表格编辑事件
        $(".edit td").click(function () {
            var currentEle = $(this);
            var wid = currentEle.parent().data('wid');
            var type = currentEle.data('type');
            if (currentEle.index() == 0 || currentEle.index() == 5){
                return false;
            }
            var tdVal = currentEle.text();
            if (!tdVal){
                var tdVal = currentEle.children('input').val();
            }
            var content = '<input type="text" value="' + tdVal + '">';

            currentEle.html(content);
            currentEle.children('input').val("").focus().val(tdVal);

            currentEle.children('input').blur(function(){
                var input_val = $(this).val();
                console.log(wid);
                // 若未更新数据则无变化
                if (tdVal != input_val && input_val != ''){
                    editAccount(wid,type,input_val);
                }
                currentEle.html(input_val);
            });
        });
        // 更新公众号详细数据
        function editAccount(id,type,val) {
            //询问框
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "post",
                data: {'id':id,'type': type, 'value':val},
                dataType: "json",
                url: '/vv/account/edit_detail/',
                success: function (res) {
                    console.log(res);
                    layer.msg('操作成功', {
                        icon: 1,
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        // location.reload();
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
        }
        $('.datetimepicker').datetimepicker({
            forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
            language: 'zh-CN',//显示中文
            format: 'yyyy-mm-dd',//显示格式
            minView: "month",//设置只显示到月份
            autoclose: true,//选中自动关闭
            startDate: moment().format('YYYY-MM-DD')
        });
    </script>

@endsection
