@extends('layouts.app')
<style type="text/css">
    strong {
        margin-bottom: 5px;
        display: inline-block;
    }

    .color-red {
        color: red;
    }
    #tmplate_msgtype_news{padding:1px; margin:15px auto;}
    #tmplate_msgtype_news .box {        background: #fff;/*border: 1px solid #ccc;*/margin:15px;padding:15px;font-size: 16px;word-break: break-all;}
    #tmplate_msgtype_news .box .msg-title {color: #000;font-weight: 400;margin-bottom: 0;}
    #tmplate_msgtype_news .box .fa {font-size: 16px;}
    #tmplate_msgtype_news .box .msg-picurl {position: relative;width: 100px; height: 100px;}
    #tmplate_msgtype_news .box .msg-picurl:hover .pic-overlay {display: block;}
    #tmplate_msgtype_news .box .msg-picurl img {width: 100px; height: 100px;}
    #tmplate_msgtype_news .box .pic-overlay {display: none;position: absolute;top: 0;left: 0;width: 100%;height: 100%;text-align: center;background: rgba(0, 0, 0, .2);}
    #tmplate_msgtype_news .box .pic-overlay a {margin-top: 30%;}
    #tmplate_msgtype_news .box .sub-items {margin-top: 10px;font-size: 16px;}
    #tmplate_msgtype_news .box .sub-item {border-top: 1px solid #eee;padding: 6px 0;}
    #tmplate_msgtype_news .box .sub-item .delete i {font-size: 20px;margin-top: 40%;}
    #tmplate_msgtype_news .add-sub-item {padding-bottom: 10px;text-align: center;}

    .news{width: 500px;padding: 0;background: #F5F5F5;}
    .news .box{background: #fff;border: 1px solid #ccc;margin: 15px;padding: 15px;font-size: 16px;word-break: break-all;}



    .input-popup-box {
        position: fixed;
        display: none;
        height: 350px;
        background: #fff;
        z-index: 250000;
        border: 1px solid #CCCCCC;
        border-radius: 5px;
        overflow: hidden;
        overflow-y: auto;
    }

    .input-popup-box ul {
        padding: 0px;
    }

    .input-popup-box li {
        display: block;
        background: #fff;
        padding: 5px 10px;
    }

    .input-popup-box li:hover {
        background: #EEEEEE;
    }

    .input-popup-box li span {
        display: block;
    }

    .input-popup-box li p {
        color: #777;
        margin: 0;
    }

    .btn1 {
        /*border: 1px solid #eee;*/
        margin-left: 0 !important;
    }

    .btn1.active {
        border: 1px solid rgb(67, 180, 45);
        color: rgb(67, 180, 45);
    }

    .grouptype1 {
        width: 100%;
        height: 250px;
        background-image: url("//cdn.zhangdu520.com/s/manage/images/custommsg-1.svg?ver=20200427000010");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 120px;

    }

    #msgtype {
        display: flex;
        width: 90%;
        margin: 10px auto;
        justify-content: space-around;
        align-items: center;
    }

    .select2-container .select2-selection--single {
        height: 34px !important;
        border: 1px solid #eaeaea;
        background-color: #fcfcfc;
    }
    .main3 .tooltip-inner{
        width: 200px;
        max-width: 200px;
    }
    .fancy-checkbox{display:inline-block;}
    .fancy-checkbox-list{display:inline-block;margin-right: 10px;width: 200px;}
    .btn {
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        -moz-box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
        -webkit-box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
        padding: 6px 22px;
    }
    .fancy-checkbox input[type=checkbox]{
        display: inline-block;
        vertical-align: middle;
        position: relative;
        bottom: 1px;
        width: 18px;
        height: 18px;
        margin-right: 5px;
        content: "";
        border: 1px solid #ccc;
    }
    .sales-item-heading {
        margin-top: 0;
        margin-bottom: 5px;
        font-size: 16px;
    }
    .sales-item {
        position: relative;
        display: block;
        padding: 10px 15px;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
        color: #333;
    }
    .modal-body {
        position: relative;
        padding: 15px;
        max-height: 500px;
        overflow: hidden;
        overflow-y: auto;
        font-size: 16px;
    }
    .select-cover-box {
        margin: 0 -15px;
    }
    .select-cover-box ul {
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }
    .select-cover-box ul > li {
        width: 120px;
        /* height: 75px; */
        list-style: none;
        margin-bottom: 10px;
    }
    .select-cover-box ul > li img {
        width: 120px;
        /* height: 75px; */
    }
    .tab-pane{
        display: none;
    }
    .tab-pane.active{
        display: block;
    }
    .select-title-box ul {
        padding: 0px;
    }
    .select-title-box {
        margin: -15px;
    }
    .select-title-box ul > li {
        padding: 5px;
        border-bottom: 1px solid #E5E5E5;
        list-style: none;
        padding-left: 15px;
        white-space: nowrap;
        display: block;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
@section('breadcrumb')
    <li><a class="fa fa-home" href="{{ route('home') }}"> 首页</a></li>
    <li>添加高级群发任务</li>
@endsection

@section('pageTitle')
    <div class="page-title">

    </div>
@endsection

@section('content')
    <!-- 插入链接 1 -->
    @include('popup.groupSent.insert.link')
    <!-- 获取链接 1 -->
    @include('popup.groupSent.get-link')
    <!-- 插入活动 -->
    @include('popup.groupSent.insert.active')
    <!-- 选择标题 1 -->
    @include('popup.groupSent.get-title')
    <!-- 图片选择框 1 -->
    @include('popup.groupSent.edit.image')
    <!-- 获取链接框 1 -->
    @include('popup.groupSent.edit.link')
    <!-- 添加标题 1 -->
    @include('popup.groupSent.add-title')
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

                                <div class="form-group col-md-12 channel-all-checkedbox" style="margin: 10px 0 0 0 ;background:#fff;width: 100%;border-top: 1px solid #eee;padding-top: 10px;">
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="1456">
                                        <span>备诚书坊</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="1457">
                                        <span>青丘书刊</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="5771">
                                        <span>寒冰书榜</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="5772">
                                        <span>影心书舍</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="6550">
                                        <span>黑石好文</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="6581">
                                        <span>黑梦书桩</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="9759">
                                        <span>魂天阁</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="10282">
                                        <span>隐士书刊</span></label>
                                    <label class="control-inline fancy-checkbox-list fancy-checkbox">
                                        <input type="checkbox" name="vipall[]" id="id[]" value="10701">
                                        <span>忆灵书坊</span></label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="data[id]" value="">
                        <div class="main1 col-sm-12" style="background:#fff;margin-left: 0;padding: 10px 10px 0 10px;">
                            <div class="form-group">
                                <label for="title" style="text-align: left;" class="col-sm-1 control-label">任务名称</label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="title" name="data[title]" placeholder="请输入内容，限50字" value="">
                                </div>

                                <div class="col-sm-6" style="line-height: 34px;">推荐名称规则：日期+群发类型+推送内容+操作人
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="remark" style="text-align: left;" class="col-sm-1 control-label">备注</label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="remark" name="data[remark]" placeholder="请输入内容，限100字" value="">
                                </div>

                            </div>
                        </div>

                        <div class="main2 col-xs-12 col-sm-6 " id="main2-id" style="background:#fff;margin-left: 0;padding: 10px 10px 0 10px; margin-top: 20px;">
                            <div class="form-group" style="border-bottom: 1px solid #eee;">
                                <label for="inputEmail3" id="msg-type" class="col-sm-10 control-label" style="text-align: left;">消息类型</label>
                                <div class="col-sm-12">
                                    <div id="msgtype" onchange="MsgTypeChange()">
                                        <label><input type="radio" name="data[msgtype]" value="1" > 图文消息</label>
                                        <label><input type="radio" name="data[msgtype]" value="0" checked=""> 文本消息</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">
                                    <!--文本消息-->
                                    <div class="col-sm-12 " id="tmplate_msgtype_text" style="padding: 0px;">
                                        <div class="form-group" style="margin:0 0 10px 0"><textarea style="height:400px;" class="form-control" id="temp_text" name="data[temp_text]" placeholder="请填写消息内容,限600个字，1200个字符"></textarea>
                                        </div>
                                        <span class="inputtip">使用{wx_name}占位符, 发送时会被替换为用户昵称</span>
                                        <div data-toggle="modal" data-target="#mdl1589339055735"><a href="javascript:void(0);" style="display: inline-block;    padding: 5px 10px;    border: 1px solid #eaeaea;    border-radius: 5px;">+插入链接</a></div>
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
                                                <div class="msg-picurl" style="height: 100px;" data-toggle="modal" data-target="#mdl152755712400"><img class="msg-img  top-picurl" src="https://cdn.zhangdu520.com/s/upload/msgres/cover/2019/1202/a17b3d47e13f1248b7dae5afa1419451.jpg">
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
                                <span class="input-group-btn"><button type="button" id="test-submit" class="btn btn-success">测试发送</button></span>
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
    <script>
        let querySel = document.querySelector('#grouptype');
        function MsgTypeChange() {
            var index = $("#msgtype input:radio:checked").val();
            if (index === '0') {
                $("#tmplate_msgtype_news").hide();
                $("#tmplate_msgtype_text").show();
            } else {
                $("#tmplate_msgtype_news").show();
                $("#tmplate_msgtype_text").hide();

            }
        }

        $(".datetimepicker1").datepicker({
            forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
            language: 'zh-CN',//显示中文
            format: 'yyyy-mm-dd',//显示格式
            minView: "month",//设置只显示到月份
            initialDate: new Date(),//初始化当前日期
            autoclose: true,//选中自动关闭
            todayBtn: true//显示今日按钮
        });
        querySel.onchange = function () {
            if (this.selectedIndex === 0){
                $('.grouptype2').hide();
                return false;
            }
            $('.grouptype2').show();
        };
        <!-- 设置时间 -->
        function setDataValue(value, type) {
            $("#send_time").val(moment().add(value, type).format('YYYY-MM-DD HH:mm'));
        }

        $('.datetimepicker').datetimepicker({
            forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
            language: 'zh-CN',//显示中文
            format: 'yyyy-mm-dd',//显示格式
            minView: "month",//设置只显示到月份
            initialDate: new Date(),//初始化当前日期
            autoclose: true,//选中自动关闭
            todayBtn: true//显示今日按钮
        });
        function _testSendMsg() {
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
            // var msgtype = $("#msgtype input:radio:checked").val();
            // if (msgtype == '0' && $("#temp_text").val().length == 0) {
            //     toastr.error("消息内容不能为空");
            //     return false;
            // }
            // if (msgtype == '1') {
            //     var data = _getNewsData();
            // } else {
            //     var data = _getTextData();
            // }
            // if ($("#test_userid").val().length == 0) {
            //     toastr.error('粉丝ID不能为空');
            //     return false;
            // }
            // if (!/^\d+$/.test($("#test_userid").val())) {
            //     toastr.error('粉丝ID必须为数字');
            //     return false;
            // }
            //
            // var d = $('#editform').serialize();
            // d += "&data[dataInfo]=" + encodeURIComponent(data);
            // api.submitStatus(true, "#test-submit", '发送中');
            // sendData('api?action=test-send', d, function (data) {
            //     api.submitStatus(false, "#test-submit", '测试发送');
            //     if (data == 'error') {
            //         toastr.error('网络繁忙，请稍候再试');
            //         return;
            //     }
            //     if (data.err < 0) {
            //         toastr.error(data.msg);
            //     } else if (data.err == 0) {
            //         toastr.success('已发送成功,请检查是否收到');
            //     }
            // });
        }


        function sendData(t, i, a) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: t,
                data: i,
                dataType: "json",
                success: function(t) {
                    try {
                        a(t)
                    } catch (t) {
                        console.log(t),
                            a("error")
                    }
                    return !1
                },
                error: function(t, i, e) {
                    a("error")
                }
            })
        }
    </script>

@endsection
