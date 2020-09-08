@extends('layouts.app')

@push('custom_css')
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/toastr/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/group-sent.css') }}">
    <link rel="stylesheet" href="//cdn.zhangdu520.com/s/channel/css/media.css?ver=20200427000010">
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/bootstrap/bootstrap-datetimepicker.min.css') }}">
@endpush

@push('scripts')
    <script type='text/javascript' src='{{ asset('js/plugins/bootstrap/bootstrap-datetimepicker.min.js') }}'></script>
    <script type="text/javascript" src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a class="fa fa-home" href="{{ route('home') }}"> 首页</a></li>
    <li>添加客服消息任务</li>
@endsection

@section('pageTitle')
    <div class="page-title">

    </div>
@endsection

@section('content')
    <!-- 插入链接 1 -->
    {{--    @include('popup.groupSent.insert.link')--}}
    <!-- 选择链接 1 -->
    @include('popup.groupSent.get-link')
    <!-- 插入活动 -->
    {{--    @include('popup.groupSent.insert.active')--}}
    <!-- 选择标题 1 -->
    @include('popup.groupSent.get-title')
    <!-- 图片选择框 1 -->
    @include('popup.groupSent.edit.image')
    <!-- 点击修改 1 -->
    @include('popup.groupSent.edit.link')
    <!-- 添加标题 1 -->
    {{--    @include('popup.groupSent.add-title')--}}
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body" style="background-color: rgb(243, 245, 248);">
                <div role="tabpanel" class="tab-pane active" style="padding:10px 0">
                    <form class="form-horizontal" id="editform">
                        <div class="main0 col-sm-12" style="background:#fff;margin-left: 0;padding: 10px 10px 0 10px;margin-bottom:20px;">
                            <div class="form-group" style="padding: 10px 0;background:#fff;margin: 0">
                                <div class="col-sm-12">
                                    <span>同步到其它帐号：</span>
                                    <label class="control-inline fancy-checkbox"><input type="checkbox" name="" id="select_channel" value=""><span>全选/反选所有</span></label>
                                    <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="批量客服消息只能添加，不能修改，创建结束后，修改任一一条消息都与其它同步消息互不影响"></i>
                                </div>

                                <div class="form-group col-md-12 channel-all-checkedbox">

                                    {{--                                    @foreach($vipall as $value)--}}
                                    {{--                                        <label class="control-inline fancy-checkbox-list fancy-checkbox">--}}
                                    {{--                                            <input type="checkbox" name="vipall[]" class="channel" id="id[]" value="{{$value->id}}">--}}
                                    {{--                                            <span>{{$value->nick_name}}</span>--}}
                                    {{--                                        </label>--}}
                                    {{--                                    @endforeach--}}

                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="data[id]" value="">
                        <div class="main1 col-sm-12" style="background:#fff;margin-left: 0;padding: 10px 10px 0 10px;">
                            <div class="form-group">
                                <label for="title" style="text-align: left;" class="col-sm-1 control-label">小说平台</label>

                                <div class="col-sm-4">
                                    <select id="pid" class="form-control">
                                        <option value="0">全部</option>
                                        @foreach($platforms as $val)
                                            <option value="{{$val->id}}">{{$val->platform_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6" style="line-height: 34px;">推荐名称规则：日期+群发类型+推送内容+操作人
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tasktp" style="text-align: left;" class="col-sm-1 control-label">任务类型</label>

                                <div class="col-sm-4">
                                    <select id="tasktype" class="form-control" name="data[task_type]">
                                        <option value="1">活动</option>
                                        <option value="2" selected>书名</option>
                                        <option value="3">签到</option>
                                        <option value="4">继续阅读</option>
                                    </select>
                                </div>
                                <div class="col-sm-6" style="line-height: 34px;">任务类型：标题+链接必须是同种类型
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="remark" style="text-align: left;" class="col-sm-1 control-label">备注</label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="title" name="data[title]" placeholder="无" value="" readonly>
                                    <input type="hidden" class="form-control" id="book-id" name="data[book_id]">
                                </div>

                            </div>
                        </div>

                        <div class="main2 col-xs-12 col-sm-6 " id="main2-id" style="background:#fff;margin-left: 0;padding: 10px 10px 0 10px; margin-top: 20px;">
                            <div class="form-group" style="border-bottom: 1px solid #eee;">
                                <label for="inputEmail3" id="msg-type" class="col-sm-10 control-label" style="text-align: left;">消息类型</label>
                                <div class="col-sm-12">
                                    <div id="msgtype" onchange="customMsg.MsgTypeChange()">
                                        <label><input type="radio" name="data[msgtype]" value="1" checked=""> 图文消息</label>
                                        <label><input type="radio" name="data[msgtype]" value="0"> 文本消息</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">
                                    <!--文本消息-->
                                    <div class="col-sm-12 " id="tmplate_msgtype_text" style="padding: 0px;">
                                        <div class="form-group" style="margin:0 0 10px 0"><textarea style="height:400px;" class="form-control" id="temp_text" name="data[temp_text]" placeholder="请填写消息内容,限600个字，1200个字符" ></textarea>
                                        </div>
                                        <span class="inputtip">使用{wx_name}占位符, 发送时会被替换为用户昵称</span>
                                        <div data-toggle="modal" data-target="#mdl1589359911750"><a href="javascript:void(0);" style="display: inline-block;    padding: 5px 10px;    border: 1px solid #eaeaea;    border-radius: 5px;">+插入链接</a></div>
                                    </div>
                                    <!--文本消息 END -->
                                    <!--图文消息ex -->
                                    <div class="col-sm-102 news" id="tmplate_msgtype_news" style="display: none;">
                                        <div class="box">
                                            <div>
                                                <span class="msg-title top-title">@{wx_name}，充值18送19，祝您要发、要久~</span>
                                                <a href="javascript:void(0);" title="点击修改">
                                                    <i class="fa fa-edit" aria-hidden="true" data-toggle="modal" data-target="#mdl1589359911750"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <span class="msg-url top-url color-red">链接未配置</span>                                                </div>
                                            <div style="margin-top:10px;display: flex">
                                                <div class="form-group" style="flex:1;margin:0 10px 0px 0"><textarea style="height:100px;" class="form-control top-content" id="temp_content" placeholder="图文简介"></textarea>
                                                </div>
                                                <div class="msg-picurl" style="height: 100px;" data-toggle="modal" data-target="#mdl152755712400"><img class="msg-img  top-picurl" src="https://cdn.zhangdu520.com/s/upload/msgres/cover/2020/0430/e3fc9b40ff4d58588405346303788343.jpg">
                                                    <div class="pic-overlay">
                                                        <a class="btn btn-success btn-xs"><i class="fa fa-edit"></i>
                                                            更换图片</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--图文消息ex end-->


                                </div>
                            </div>

                        </div>

                        <div class="main3 col-sm-6 " id="main3-id" style=" margin-top: 20px;padding-right: 0px;">
                            <!-- 发送用户群 -->
                            @include('popup.groupSent.user')
                        </div>


                    </form></div>

                <div class="main4 col-xs-12 col-sm-12" style="background:#fff;margin-left: 0;padding-left: 0;margin-top: 20px;padding: 10px;">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-1 control-label" style="line-height: 34px;">测试粉丝ID</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="test_userid" name="data[test_user]" value="">
                                <span class="input-group-btn">
                                    <button type="button" id="test-submit" class="btn btn-success">测试发送</button>
                                </span>
                            </div>
                        </div>

                        <span class="control-label col-sm-3" style="text-align: left;line-height: 34px;">用户必须关注本号，且48小时内有互动</span>
                        <span class="control-label col-sm-2 green tipmsg" style="line-height: 34px;">&nbsp;</span>
                        <button type="button" id="msg-submit" class="col-sm-2 btn btn-success" style="width:100px;">保存
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>


@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/comm.min.js') }}"></script>

    <script>
        // 选择标题
        $(".select-title-box li").click(function (e) {
            var typeid = $('#tasktype option:selected').val();
            if ($(this).data('type') != typeid){
                toastr.error('任务类型不匹配');
                return false;
            }
        });
        function checktask(that){
            var typeid = $('#tasktype option:selected').val();
            if (that.data('type') != typeid){
                toastr.error('任务类型不匹配');
                return true;
            }
        }

        // 选择链接
        $(".select-link-box a").click(function (e) {
            if (checktask($(this))){
                return false;
            }

            var link = $(this).data('url');
            $('#mdl1589359911750 .temp_link').val(link);
            $('#mdl1589359911750 .temp_link').data('bookid', $(this).data('bookid'));
            $("#mdl1526557124088").modal('hide');

            e.preventDefault();
        });

        // // 图文消息 -> 标题选择
        $('#mdl1589359911750').on('show.bs.modal', function (event) {
            var model = $(this),
                title = '标题未配置',
                link = '链接未配置';

            // 关闭事件
            model.click(function (e) {
                title = model.find('#img_text_title').val() || title;
                link = model.find('#temp_link').val() || link;
                if (e.target.classList.contains('ok')){
                    $('.msg-title').text(title);
                    $('.msg-url').text(link);
                    $('#title').val(title);
                    $('#book-id').val(model.find('.temp_link').data('bookid'));
                }
                e.preventDefault();
            });
            selectTitle.bind();

        });
        // 图文消息 -> 图片选择
        $('#mdl152755712400').on('show.bs.modal', function () {
            var model = $(this);

            $(".select-cover-box li img").click(function (e) {
                var img_href = $(this).context.currentSrc;
                $('.msg-img').attr('src',img_href);
                model.modal('hide');
                e.preventDefault();
            })
        });
        function toggleNav(current){
            $('#mdl1526557124088 .active').toggleClass('active in')
            var linktab = '#mdl1526557124088 .link-tab' + (current+1)
            $(linktab).toggleClass('active in');
        }

        document.querySelector('#tasktype').onchange = function () {
            // 切换tabs
            toggleNav(this.selectedIndex);

            selectTitle.loadData(this.selectedIndex+1);
            var content = '';
            var pid = $('#pid option:selected').val();
            if (this.selectedIndex === 2){
                var vip_all = @json($vipall->get());
                for(var key = 0; key < vip_all.length; key++){
                    var val = vip_all[key];
                    if (pid != val['pid']  && pid != 0){
                        continue;
                    }
                    content += `<label class="control-inline fancy-checkbox-list fancy-checkbox">
                    <input type="checkbox" name="vipall[]" class="channel" value="${val['id']}">
                    <span>${val['nick_name']}</span>
                    </label>`;
                }
            }
            else if (this.selectedIndex === 3){
                var vip_all = @json($vipall->whereIn('id', $history_wid)->get());

                for(var key = 0; key < vip_all.length; key++){
                    var val = vip_all[key];
                    if (pid != val['pid']  && pid != 0){
                        continue;
                    }
                    content += `<label class="control-inline fancy-checkbox-list fancy-checkbox">
                        <input type="checkbox" name="vipall[]" class="channel" id="id[]" value="${val['id']}">
                        <span>${val['nick_name']}</span>
                        </label>`;
                }
            }
            else {
                $('.channel-all-checkedbox').html('');
                return false;
            }
            $('.channel-all-checkedbox').html(content);
            // 平台切换
            document.querySelector('#pid').onchange = function () {
                var content = '';
                var pid = $('#pid option:selected').val();
                for(var key = 0; key < vip_all.length; key++){
                    var val = vip_all[key];
                    if (pid != val['pid'] && pid != 0){
                        continue;
                    }
                    content += `<label class="control-inline fancy-checkbox-list fancy-checkbox">
                        <input type="checkbox" name="vipall[]" class="channel" id="id[]" value="${val['id']}">
                        <span>${val['nick_name']}</span>
                        </label>`;
                }
                $('.channel-all-checkedbox').html(content);

            };

        };

        let querySel = document.querySelector('#grouptype');
        querySel.onchange = function () {
            if (this.selectedIndex === 0){
                $('.grouptype2').hide();
                return false;
            }
            $('.grouptype2').show();
        };

        $(".datetimepicker1").datetimepicker({
            forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
            language: 'zh-CN',//显示中文
            format: 'yyyy-mm-dd hh:ii',//显示格式
            // minView: "month",//设置只显示到月份
            // initialDate: new Date(),//初始化当前日期
            autoclose: true,//选中自动关闭
            todayBtn: true,//显示今日按钮

        });
        function changePayStatus() {
            var pay = $(".paystatus input:checked").val();
            if (pay == '1') {
                $('.rechargemoney').show()
            } else {
                $('.rechargemoney').hide()
            }
        }

        $('.paystatus input').change(function () {
            changePayStatus()
        })
        <!-- 设置时间 -->
        function setDataValue(value, type) {
            $("#send_time").val(moment().add(value, type).format('YYYY-MM-DD HH:mm'));
        }

        $('.datetimepicker').datetimepicker({
            forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
            language: 'zh-CN',//显示中文
            format: 'yyyy-mm-dd hh:ii',//显示格式
            // minView: "month",//设置只显示到月份
            // initialDate: new Date(),//初始化当前日期
            autoclose: true,//选中自动关闭
            todayBtn: true,//显示今日按钮
            startDate: moment().format('YYYY-MM-DD HH:mm'),
            endDate: moment().add(3, 'day').format('YYYY-MM-DD') + ' 23:59'
        });

        function _postTemplateMsg() {
            var msgtype = $("#msgtype input:radio:checked").val();
            // if ($("#title").val().length == 0) {
            //     $(".tipmsg").html("任务标题不能为空");
            //     return false;
            // }
            if (msgtype == '0' && $("#temp_text").val().length == 0) {
                $(".tipmsg").html("消息内容不能为空");
                return false;
            }
            if (selectChannelAll.getCheckNum()>0 && $("#temp_text").val().indexOf('http') != -1){
                toastr.error("批量签到内容，不能包含任何链接");
                return false;
            }
            if (selectChannelAll.getCheckNum()>0 && msgtype != 1 && $('#tasktype option:selected').val() == 4){
                toastr.error("批量继续阅读，不能发送文本消息");
                return false;
            }

            if ($('.msg-url').text().indexOf('http') == -1 && msgtype == '1'){
                toastr.error("链接配置不正确");
                return false;
            }
            if (msgtype == '1') {
                var data = _getNewsData();
            } else {
                var data = _getTextData();
            }
            if ($("#send_time").val().length == 0) {
                $(".tipmsg").html("发送时间不能空");
                return false;
            }

            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            if ((start_date == '' && end_date != '') || (start_date != '' && end_date == '')) {
                $(".tipmsg").html("发送用户群中的开始/结束日期:要么都不填，要么都要填");
                return false;
            }
            // if(selectChannelAll.getCheckNum()>0 && $("#grouptype").val()==2){
            //     //$(".tipmsg").html("发送给精准粉丝时，无法使用同步其它帐号功能");
            //     _checkBatchDlg();
            //     return false;
            // }
            var d = $('#editform').serialize();
            d += "&data[dataInfo]=" + encodeURIComponent(data);
            $(".tipmsg").html("");
            api.submitStatus(true, "#msg-submit", '');
            sendData('{{route('wechat.custom_msg.save')}}', d, function (data) {
                if (data == 'error') {
                    toastr.error('网络繁忙，请稍候再试');
                    api.submitStatus(false, "#msg-submit", '保存');
                    return;
                }
                if (data.code < 0) {
                    toastr.error(data.errmsg);
                    api.submitStatus(false, "#msg-submit", '保存');
                } else if (data.code == 1) {
                    toastr.success('提交成功');
                    setTimeout(function () {
                        window.location.href = '/group_send/list/custommsg';
                    }, 1500);

                }
            });
        }

        function _testSendMsg() {
            var msgtype = $("#msgtype input:radio:checked").val();
            if (msgtype == '0' && $("#temp_text").val().length == 0) {
                toastr.error("消息内容不能为空");
                return false;
            }
            if (msgtype == '1') {
                var data = _getNewsData();
            } else {
                var data = _getTextData();
            }
            if ($("#test_userid").val().length == 0) {
                toastr.error('粉丝ID不能为空');
                return false;
            }
            if (!/^\d+$/.test($("#test_userid").val())) {
                toastr.error('粉丝ID必须为数字');
                return false;
            }

            var d = $('#editform').serialize();
            d += "&data[test_user]=" +  $("#test_userid").val() + "&data[dataInfo]=" + encodeURIComponent(data);
            api.submitStatus(true, "#test-submit", '发送中');
            sendData('{{route('wechat.custom_msg.test_send')}}', d, function (data) {
                api.submitStatus(false, "#test-submit", '测试发送');
                if (data == 'error') {
                    toastr.error('网络繁忙，请稍候再试');
                    return;
                }
                if (data.errcode != 0) {
                    toastr.error(data.errmsg);
                } else if (data.errcode == 0) {
                    toastr.success('已发送成功,请检查是否收到');
                }
            });
        }

        function _getNewsData() {
            var data = {
                'title': $('.top-title').text(),
                'picurl': $('.top-picurl').attr('src'),
                'linkurl': $('.top-url').text(),
                'content': $('.top-content').length > 0 ? $('.top-content').val() : '',
            };
            var json = [];
            $(".sub-items .sub-item").each(function () {
                json.push({
                    title: $(this).find(".msg-title").text(),
                    linkurl: $(this).find(".msg-url").text()
                });
            });
            return JSON.stringify({'top-item': data, 'sub-item': json});
        }

        function _getTextData() {
            var data = {
                text: $("#temp_text").val(),
            }
            return JSON.stringify(data);
        }

        // 客服消息相关
        var customMsg = new function(){
            this.planToSend = function () {
                var d = $('#editform').serialize();
                sendData('{{route('wechat.kf.get_send_num')}}', d, function (data) {
                    if (data == 'error') {
                        toastr.error('网络繁忙，请稍候再试');
                        return;
                    }
                    if (data.err == 0) {
                        $("#plan-to-send>span").html(data.data.count + '人');
                        $("#plan-to-send>input").val(data.data.count);
                    } else if(data.err == -12) {
                        $("#plan-to-send>span").html('0人');
                        $("#plan-to-send>input").val(data.data.count);
                    }else{
                        toastr.error(data.msg);
                    }
                });
            }

            this.init=function(){
                this.MsgTypeChange();

                $("#msg-submit").bind('click',function(event){
                    event.preventDefault();
                    _postTemplateMsg();
                });
                $("#test-submit").bind('click',function(event){
                    event.preventDefault();
                    _testSendMsg();
                });
            };
            // 消息类型切换
            this.MsgTypeChange=function(){
                var index = $("#msgtype input:radio:checked").val();
                if (index === '0') {
                    $("#tmplate_msgtype_news").hide();
                    $("#tmplate_msgtype_text").show();
                } else {
                    $("#tmplate_msgtype_news").show();
                    $("#tmplate_msgtype_text").hide();
                }
            }
        };
        $(function () {
            // inputPopup.init();
            customMsg.init();
            selectTitle.init();
            // selectCover.init();
            // selectLink.init();
            // setSaleaTimeDlg.init();
            // addbtn.init();

            selectChannelAll.init();

            // customMsg.filterTypeChange()
            changePayStatus();
            customMsg.planToSend();
            // selectTag.init();
        });

        //条件改变，获取预计送达人数
        $('.main3 .grouptypes input').change(function () {
            customMsg.planToSend();
        });
        $('.main3 .grouptypes select').change(function () {
            customMsg.planToSend();
        });
        $('.main3 .grouptypes textarea').blur(function () {
            customMsg.planToSend();
        });
        var stime = '';
        var etime = '';
        var readtime_from = '';
        var readtime_to = '';
        $('.main3 .grouptypes input.datetimepicker1').bind('blur', function(){
            if ($(this).attr('id') == 'stime') {
                if ($(this).val() != stime) {
                    stime = $(this).val();
                    customMsg.planToSend();
                }
            } else {
                if ($(this).val() != etime) {
                    etime = $(this).val();
                    customMsg.planToSend();
                }
            }
        });

        $('#readtime_from').bind('blur', function(){
            if ($(this).val() != readtime_from) {
                readtime_from = $(this).val();
                customMsg.planToSend();
            }
        });
        $('#readtime_to').bind('blur', function(){
            if ($(this).val() != readtime_to) {
                readtime_to = $(this).val();
                customMsg.planToSend();
            }
        });

        //支付状态修改
        function changePayStatus() {
            var pay = $(".paystatus input:checked").val();
            if (pay == '1') {
                $('.rechargemoney').show()
            } else {
                $('.rechargemoney').hide()
            }
        }

        $('.paystatus input').change(function () {
            changePayStatus()
        });

        var __TitleList = @json($titleInfo);

        //选择标题
        var selectTitle = new function () {
            //var __TitleList=null;
            var _dialogid = 'mdl1526557124086';
            this.init = function () {
                setTimeout(function () {
                    selectTitle.loadData(2);
                }, 100);
                _dropdownMenuTabEvent(this);
            };
            this.bind = function () {
                $('#searchtitle').keypress(function (e) {
                    if (e.keyCode == 13) {
                        selectTitle._showSearch();
                    }
                });
                $("#title-search-submit").unbind('click').bind('click', function (event) {
                    event.preventDefault();
                    selectTitle._showSearch();
                });
            };
            this.getData = function () {
                return __TitleList;
            };
            this.show = function () {
                $("#" + _dialogid).modal('show');
            };
            this.hide = function () {
                $("#" + _dialogid).modal('hide');
            };
            this.setTitle = function (title) {
                $("#img_text_title").val(title);
            };
            this.getTitle = function () {
                return $("#img_text_title").val();
            };
            this.getRandomdata = function () {
                var min = 0;
                var max = __TitleList.length - 1;
                var index = api.GetRandomNum(min, max);
                return __TitleList[index].title;
            };
            this.TopDataRandom = function () {
                var title = $('.msg-title').text();
                if (title != '') {
                    return;
                }
                var min = 0;
                var max = __TitleList.length - 1;
                var index = api.GetRandomNum(min, max);
                // console.log(__TitleList[index].title);
                $('.msg-title').text(__TitleList[index].title);
            };
            this.loadData = function (categoryid, key,action='tab') {
                var that = this;
                var html = '';
                that.TopDataRandom();
                $(".select-title-box li").unbind('click');
                for (var index = 0; index < __TitleList.length; index++) {
                    var item = __TitleList[index];
                    if (categoryid == item.categoryid || categoryid == 'all') {
                        if (action == 'search') {////搜索
                            if (item.title.indexOf(key) !== -1) {
                                html += '<li data-type="' + item.categoryid + '" data-index="' + item.id + '">' + item.title + '</li>';
                            }
                        }else{
                            html += '<li data-type="' + item.categoryid + '" data-index="' + item.id + '">' + item.title + '</li>';
                        }
                    }

                }
                $(".select-title-box ul").html(html);
                $(".select-title-box li").bind('click', function (event) {
                    event.preventDefault();
                    if(checktask($(this))){
                        return false;
                    };
                    that.setTitle($(this).text());
                    that.hide();
                });
            };
            this._showSearch = function () {
                var that = this;
                // var categoryid = $(".dropdown-header-title li[class='active']").attr('data-index');
                var categoryid = $("#tasktype option:selected").val();
                var key = $("#searchtitle").val();
                that.loadData(categoryid, key,'search');
            };

            function _dropdownMenuTabEvent(that) {
                $(".dropdown-header-title").bind('click', function (event) {
                    event.stopPropagation();
                });
                $(".dropdown-header-title li").bind('click', function (event) {
                    event.stopPropagation();
                    // var categoryid = $(this).parent().attr('data-type');
                    var categoryid = $("#tasktype option:selected").val();
                    var index = $(this).attr('data-index');
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                    switch (categoryid) {
                        case 'title':
                            that.loadData(index, '');
                            break;
                    }
                });
            }
        };

        ////同步帐号选择
        var selectChannelAll=new function(){
            this.init=function(){
                $("#select_channel").bind('click',function(event){
                    event.stopPropagation();
                    var checked=$(this).prop("checked");
                    _checkedItem(checked);
                });
            }
            this.getCheckNum=function(){
                var total=0;
                $('.channel-all-checkedbox input').each(function(index,element){
                    total+=$(this).prop('checked')?1:0;
                });
                return total;
            }
            this.clearCheck=function(){
                _checkedItem(false);
            }
            function _checkedItem(checked){
                $('.channel-all-checkedbox input').prop('checked',checked);
            }
        }
    </script>

@endsection
