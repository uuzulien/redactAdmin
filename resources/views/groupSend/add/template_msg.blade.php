@extends('layouts.app')
<style type="text/css">
    strong {
        margin-bottom: 5px;
        display: inline-block;
    }
    .panel {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -moz-box-shadow: 0 2px 6px rgba(0,0,0,.08);
        -webkit-box-shadow: 0 2px 6px rgba(0,0,0,.08);
        box-shadow: 0 2px 6px rgba(0,0,0,.08);
        background-color: #fff;
        margin-bottom: 30px;
    }
    .panel .panel-body {
        padding-top: 10px;
        padding-bottom: 15px;
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

    .select2-container .select2-selection--single {
        height: 34px !important;
        border: 1px solid #eaeaea;
        background-color: #fcfcfc;
    }
    .main3 .tooltip-inner{
        width: 200px;
        max-width: 200px;
    }

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
    .form-control {
        -moz-box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        -webkit-box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        border-color: #eaeaea;
        background-color: #fcfcfc;
    }
    .mynav, .panel-header-bottom {
        border-bottom: 1px solid #eff2f7;
    }
    .panel .panel-heading {
        padding: 10px;
        position: relative;
    }
</style>
@section('breadcrumb')
    <li><a class="fa fa-home" href="{{ route('home') }}"> 首页</a></li>
    <li>客服消息</li>
@endsection

@section('pageTitle')
    <div class="page-title">

    </div>
@endsection

@section('content')
    <div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <div role="tabpanel" class="tab-pane active" style="padding:10px 0">
                    <form class="form-horizontal main3" id="editform">
                        <input type="hidden" class="form-control" name="data[id]" value="">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">任务名称</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="title" name="data[title]" placeholder="请输入内容，限50字" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">选择模版</label>
                            <textarea name="data[content]" id="tmplate_content" style=" display:none; "></textarea>
                            <div class="col-sm-4">
                                <div class="col-sm-12 " id="template_list" style="padding: 0px;">
                                    <div class="input-group">
                                        <input type="hidden" id="template_id" name="data[template_id]" value="">
                                        <select class="form-control" onchange="tmplateMsg.templateChange()"><option value=""></option><option value="0">会员开通成功提醒</option></select>
                                        <span class="input-group-btn">
                              <button type="button" class="btn btn-success tmpMsg-btn-refresh" data-cache="1">更新</button>
                              </span>
                                    </div>
                                </div>
                                <div class="col-sm-12" id="template_empty" style="padding:0; display: none; ">
                                    <div class="alert alert-info">
                                        请先到公众号后台申请模版，如已申请，请点击按钮刷新
                                        <p style="margin-top:10px">
                                            <button type="button" class="btn btn-success tmpMsg-btn-refresh" data-cache="1">更新</button>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-sm-12" id="template_edit" style="padding:0; display:none ;background: #F5F5F5; ">
                                    <div class="box">
                                        <div class="msg-title"></div>
                                        <p>05月13日</p>
                                        <div class="msg-main"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">跳转链接</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="url" name="data[url]" placeholder="" value=""><span class="inputtip">仅限您小说网站内部的链接；为空消息则不能点击</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">发送时间</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control datetimepicker" id="send_time" name="data[send_time]" placeholder="" value="" autocomplete="off">
                                <div style="padding-top:5px;">
                                    【<a href="javascript:;" onclick="setDataValue(10,'minutes')">10分钟后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(30,'minutes')">30分钟后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(1,'hours')">1小时后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(2,'hours')">2小时后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(3,'hours')">3小时后</a>】
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">测试粉丝ID</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="test_userid" name="data[test_user]" value="">
                                    <span class="input-group-btn"><button type="button" id="test-submit" class="btn btn-success">测试发送</button></span>
                                </div>
                            </div>

                        </div>
                        <div class="grouptypes" style="margin-top: 10px;background: #fff;height: auto;">
                            <div class="form-group" style="">
                                <label for="inputPassword3" class="col-sm-2 control-label" style="">发送用户群</label>
                                <div class="col-sm-3">
                                    <select id="grouptype" class="form-control" name="data[filter_type]">
                                        <option value="0" selected="">所有粉丝</option>
                                        <option value="1">条件粉丝</option>
                                    </select>
                                </div>
                            </div>

                            <!--条件粉丝-->
                            <div class="grouptype2" style="display: none">
                                <div class="form-group" style="">
                                    <label class="col-sm-2 control-label" style="padding-left: 0;padding-right: 15px;">粉丝性别</label>
                                    <div class="col-sm-10 " style="">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn1 active ">
                                                <input type="radio" name="data[sex]" value="-1" checked="">
                                                全部
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[sex]" value="1"> 男
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[sex]" value="2"> 女
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[sex]" value="0"> 未知
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <label class="col-sm-2 control-label" style="padding-left: 0;padding-right: 15px;">支付状态</label>
                                    <div class="col-sm-10 " style=" ">
                                        <div class="btn-group paystatus" data-toggle="buttons">
                                            <label class="btn btn1 active ">
                                                <input type="radio" name="data[pay]" value="-1" checked=""> 全部
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[pay]" value="0"> 未充值
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[pay]" value="1"> 已充值
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[pay]" value="2"> 年费会员
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group rechargemoney" style="display: none;">
                                    <label class="col-md-2 control-label" style="padding-left: 0;padding-right: 15px;">充值金额</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="qf-num1 form-control" name="data[totalmoney_from]" value="" oninput="if(value<0)value=0">
                                    </div>
                                    <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="qf-num2 form-control" name="data[totalmoney_to]" value="" oninput="if(value<0)value=0">
                                    </div>

                                    <label class="col-md-2 control-label" style="text-align: left;padding-left: 0;padding-right: 0;width: auto;">剩余书币:
                                        <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="不填，表示无限制"></i> &nbsp;
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="number" class="qf-num1 form-control" name="data[balance_from]" value="" oninput="if(value<0)value=0">
                                    </div>
                                    <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="qf-num2 form-control" name="data[balance_to]" value="--" oninput="if(value<0)value=0">

                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <label for="inputPassword3" class="col-md-2 control-label" style="padding-left: 0;padding-right: 15px;">最后阅读时间</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control datetimepicker1" id="readtime_from" name="data[readtime_from]" placeholder="" value="" autocomplete="off">
                                    </div>
                                    <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control datetimepicker1" id="readtime_to" name="data[readtime_to]" placeholder="" value="" autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group" style="margin: 20px 0 20px 0;">
                                    <label class="col-sm-2 control-label" style="padding-left: 0;padding-right: 15px;">互动时间</label>
                                    <div class="col-sm-10 " style="">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn1 active ">
                                                <input type="radio" name="data[recent_interact]" value="-1" checked=""> 全部
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[recent_interact]" value="1"> 48小时内
                                            </label>
                                            <label class="btn btn1 ">
                                                <input type="radio" name="data[recent_interact]" value="2"> 48小时外
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group" style="">
                                    <label for="inputPassword3" class="col-md-2 control-label" style="padding-left: 0;padding-right: 15px;">关注时间</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control datetimepicker1" id="stime" name="data[stime]" placeholder="" value="" autocomplete="off">
                                    </div>
                                    <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control datetimepicker1" id="etime" name="data[etime]" placeholder="" value="" autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <label class="col-md-2 control-label" style="padding-left: 0;padding-right: 15px;">群发次数&nbsp;<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="指本月高级群发。仅能监测到平台内群发次数"></i></label>
                                    <div class="col-sm-2">
                                        <input type="number" class="qf-num1 form-control" name="data[qnum1]" value="" oninput="if(value<0)value=0">
                                    </div>
                                    <!--                                            <span class="col-md-1 control-label" style="padding:0;margin: 0 0px;">-</span>-->
                                    <label class="col-md-1 control-label" style="text-align: center;padding-left: 0;padding-right: 0;width: 3px;">-</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="qf-num2 form-control" name="data[qnum2]" value="" oninput="if(value>=4)value=4;if(value<0)value=0;">
                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <label class="col-md-2 control-label" style="padding-left: 0;padding-right: 15px;">阅读记录</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="data[tag_type]">
                                            <option value="1" selected="">排除</option>
                                            <option value="2">包含</option>
                                        </select>                                    </div>
                                    <div class="col-md-2">
                                        <select id="tag_id" name="data[tag_id]" class="form-control select2-hidden-accessible" data-select2-id="tag_id" tabindex="-1" aria-hidden="true">
                                        </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="1" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-tag_id-container"><span class="select2-selection__rendered" id="select2-tag_id-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder"><div></div></span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                            </div>

                            <!--精准粉丝-->
                            <div class="grouptype3" style="display: none;margin: 20px 0 0 0;">
                                <div class="form-group" style="margin-top: 50px;">
                                    <div class="col-sm-10 col-sm-offset-1 " id="tmplate_msgtype_text" style="padding:0;">
                                        <div class="form-group" style="margin:0 0 10px 0"><textarea style="height:200px;" class="form-control" name="data[member_ids]" placeholder="请输入本公众号粉丝的会员ID，一行一个，最多1万个，最少2个"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <label class="col-md-2 control-label" style="padding: 0;"> 预计送达人数 </label>
                                <div class="col-sm-10 " style="" id="plan-to-send">
                                    <input type="hidden" class="form-control" name="data[plan_to_send]" value="4198">
                                    <span>4198人</span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <!--<a class="btn btn-default" style="width:100px;">返回</a>-->
                                <button type="button" id="msg-submit" class="btn btn-success" style="width:100px;">保存
                                </button>
                                <span class="green tipmsg">&nbsp;</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/comm.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.zhangdu520.com/s/js/select2/js/select2.min.js?ver=20200427000010"></script>

<script>
let querySel = document.querySelector('#grouptype');

querySel.onchange = function () {
    if (this.selectedIndex === 0){
        $('.grouptype2').hide();
        return false;
    }
    $('.grouptype2').show();
};
$(".datetimepicker1").datepicker({
    forceParse: 0,//设置为0，时间不会跳转1899，会显示当前时间。
    language: 'zh-CN',//显示中文
    format: 'yyyy-mm-dd',//显示格式
    minView: "month",//设置只显示到月份
    initialDate: new Date(),//初始化当前日期
    autoclose: true,//选中自动关闭
    todayBtn: true//显示今日按钮
});
</script>
<script>
    function setDataValue(value, type) {
        $("#send_time").val(moment().add(value, type).format('YYYY-MM-DD HH:mm'));
    }

    $(function () {
        inputPopup.init();
        tmplateMsg.init();
        selectTag.init();

        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            locale: moment.locale('zh-CN'),
            useCurrent: false
        });
        $('.datetimepicker1').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-CN'),
            useCurrent: false
        });

        //条件改变，获取预计送达人数
        $('.main3 .grouptypes input').change(function () {
            tmplateMsg.planToSend();
        });
        $('.main3 .grouptypes select').change(function () {
            tmplateMsg.planToSend();
        });
        $('.main3 .grouptypes textarea').blur(function () {
            tmplateMsg.planToSend();
        });
        var stime = '';
        var etime = '';
        var readtime_from = '';
        var readtime_to = '';
        $('.main3 .grouptypes input.datetimepicker1').bind('blur', function(){
            if ($(this).attr('id') == 'stime') {
                if ($(this).val() != stime) {
                    stime = $(this).val();
                    tmplateMsg.planToSend();
                }
            } else {
                if ($(this).val() != etime) {
                    etime = $(this).val();
                    tmplateMsg.planToSend();
                }
            }
        });
        $('#readtime_from').bind('blur', function(){
            if ($(this).val() != readtime_from) {
                readtime_from = $(this).val();
                tmplateMsg.planToSend();
            }
        });
        $('#readtime_to').bind('blur', function(){
            if ($(this).val() != readtime_to) {
                readtime_to = $(this).val();
                tmplateMsg.planToSend();
            }
        });

        changePayStatus();
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
    })

    var inputPopup = new function () {
        var _element_popup = '.input-popup-box';
        this.init = function (element) {
            var that = this;
            $(document).click(function (event) {
                var _con = $(_element_popup);  // 设置目标区域
                if (!_con.is(event.target) && _con.has(event.target).length === 0) { // Mark 1
                    that.hide();
                }

            });
        }
        this.show = function (obj) {
            var top = $(obj).offset().top;
            var left = $(obj).offset().left;
            var width = $(obj).parent().width();
            var height = $(obj).parent().height();
            top = top + height - $(window).scrollTop();
            $(_element_popup).css({display: 'block', top: top, left: left, width: width});


        }
        this.hide = function () {
            $(_element_popup).css('display', 'none');
        }
        this.isEmpty = function () {
            if ($(_element_popup).find('li').length > 0) {
                return false;
            }
            return true;
        }
        this.loadData = function (fun) {
            if (!this.isEmpty()) {
                return;
            }
            var data = {action: 'get-idispatch-toplist'};
            sendData('/idispatch/api?action=get-idispatch-toplist', data, function (data) {
                if (data == 'error') {
                    $(_element_popup + ' ul').html('网络繁忙，请稍候再试');
                    return;
                }
                if (data.err != 0) {
                    $(_element_popup + ' ul').html(data.msg);
                    return;
                }
                $(_element_popup + ' ul').html('');
                $.each(data.data, function (index, val) {
                    $(_element_popup + ' ul').append(itemHtml(val));
                });
                $(_element_popup + ' li').unbind('click').bind('click', function (event) {
                    event.stopPropagation();
                    fun(this);
                });

            });
        }

        function itemHtml(row) {
            return '<li><span>' + row.title + '</span><p>' + row.url + '</p></li>';
        }
    }
    selectTag = new function () {
        function formatRepoProvince(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div>" + repo.text + "</div>";
            return markup;
        }
        this.init = function () {
            $("#tag_id").select2({
                //theme: "bootstrap",
                language: "zh-CN",
                width: '100%',
                placeholder: "",
                placeholderOption: "first",
                allowClear: true,
                ajax: {
                    url: "/group-send/api",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            action: 'get-tag-list',
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        data = data.data;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 20) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatRepoProvince, // omitted for brevity, see the source of this page
                templateSelection: formatRepoProvince // omitted for brevity, see the source of this page
            });
        }
    }

    var tmplateMsg = new function () {


        var _edit_templatekey = "_4TLtwR91Q2eQHZoK_JiQHC699exaflpE6CXjXYHFso";
        var _edit_templatecontent ={"first":{"value":"\ud83d\udea3\ud83c\udffb\u7aef\u5348\u4e66\u57ce\u6709\u793c\uff0c\u60ca\u7206\u9650\u65f6\u79d2\u6740\uff01","color":"#000000"},"keyword1":{"value":"\u514512\u900113 \uff0c\u9650\u65f6\u5f00\u542f","color":"#ff0000"},"keyword2":{"value":"\u23f02020-06-25","color":"#000000"},"keyword3":{"value":"\ud83d\udcda\u4e66\u57ce\u4e2d\u5fc3","color":"#0000ff"},"remark":{"value":"\u540d\u989d\u6709\u9650\uff0c\u70b9\u51fb\u7ea2\u8272\u5b57\u4f53\u7acb\u5373\u9886\u53d6~","color":"#ff0000"}};
        var _index = '';
        var _dataTemplate = null;
        var _selectObj = $("#template_list select");
        var _editObj = $("#template_edit");


        this.init = function () {
            _loadNetTemplateMsg(0);
            $(".tmpMsg-btn-refresh").bind('click', function (event) {
                event.preventDefault();
                var cache = $(this).data('cache');
                _loadNetTemplateMsg(cache);
            });
            $("#msg-submit").bind('click', function (event) {
                event.preventDefault();
                _postTemplateMsg();
            });
            $("#test-submit").bind('click', function (event) {
                event.preventDefault();
                _testSendMsg();
            });
            _initControler();
            tmplateMsg.filterTypeChange()
            tmplateMsg.planToSend()
        }

        this.planToSend = function () {
            var d = $('#editform').serialize();
            sendData('api?action=get-num-plan-to-send', d, function (data) {
                if (data == 'error') {
                    toastr.error('网络繁忙，请稍候再试');
                    return;
                }
                if (data.err == 0) {
                    $("#plan-to-send>span").html(data.data.count + '人');
                    $("#plan-to-send>input").val(data.data.count);
                } else if(data.err == -12 || data.err == -17) {
                    $("#plan-to-send>span").html('0人');
                    $("#plan-to-send>input").val(data.data.count);
                }else{
                    toastr.error(data.msg);
                }
            });
        }

        this.filterTypeChange = function () {
            var index = $("#grouptype").val();
            if (index == '0') {
                $(".grouptype2").hide();
                $(".grouptype3").hide();
                $(".grouptype1").show();
            } else if (index == '1') {
                $(".grouptype1").hide();
                $(".grouptype3").hide();
                $(".grouptype2").show();
            } else {
                $(".grouptype1").hide();
                $(".grouptype2").hide();
                $(".grouptype3").show();
            }
        }

        function _initControler() {
            function callback(obj) {
                $("#url").val($(obj).find('p').text());
                inputPopup.hide();
            }

            $("#url").unbind('click').bind('click', function (event) {
                event.stopPropagation();
                inputPopup.show(this);
            });
            $("#url").unbind('focus').bind('focus', function (event) {
                event.stopPropagation();
                inputPopup.show(this);
            });
            inputPopup.loadData(callback);
        }

        this.templateChange = function () {
            _index = _selectObj.val();
            if (_index == '') {
                _editObj.hide();
                return;
            }
            _editObj.show();
            _loadPreviewData(_index);
        }

        function _loadNetTemplateMsg(cache) {
            // api.submitStatus(true, ".tmpMsg-btn-refresh", '更新中');
            sendData('api?action=gettemplate', {cache: cache}, function (data) {
                api.submitStatus(false, ".tmpMsg-btn-refresh", '更新');
                if (data == 'error') {
                    toastr.error('网络繁忙，请稍候再试');
                    return;
                }
                if (data.err < 0) {
                    toastr.error(data.msg);
                } else if (data.err == 0) {
                    _dataTemplate = data.data.template_list;
                    if (_dataTemplate.length > 0) {
                        template_empty(false);
                        _SelectCrontrollInit();
                    } else {
                        template_empty(true);
                    }
                }
            });
        }

        function _SelectCrontrollInit() {
            _index = -1;
            var temp_index = 0;
            var list = _dataTemplate;
            var itemstr = '<option value=""></option>';
            $.each(list, function (index, item) {
                var selected = '';
                if (item.template_id == _edit_templatekey) {
                    selected = 'selected';
                    temp_index = index;
                }
                itemstr += '<option value="' + index + '" ' + selected + '>' + item.title + '</option>';
            });
            _selectObj.html(itemstr);
            if (_edit_templatekey != '') {
                var params = [];
                $.each(_edit_templatecontent, function (index, item) {
                    params.push({field: index, value: item.value, color: item.color});
                });
                _dataTemplate[temp_index].params = params;
                tmplateMsg.templateChange();
            }
        }

        function _loadPreviewData(index) {
            var template = _dataTemplate[index];
            var html = template.content.replace(/\n/g, '<br/>');
            html = html.replace(/\{\{([\w\_\-]+)\.DATA\}\}/ig, function () {
                return '<span class="msg-field" _field="' + arguments[1] + '"><span class="msg-value" _color=""></span> <i class="fa fa-file-o" aria-hidden="true" title="点击修改"></i></span>';
            });
            _editObj.find('.msg-title').html(template.title);
            _editObj.find('.msg-main').html(html);
            var params = _dataTemplate[_index].params;
            if (params && params.length > 0) {
                $.each(params, function (index, item) {
                    _updateField(item.field, item.value, item.color);
                });
            }
            _bandEditClick();
        }

        function _bandEditClick() {
            _editObj.find('.msg-field i').unbind('click').bind('click', function (event) {
                event.preventDefault();
                var parent = $(this).parent();
                var field = parent.attr('_field');
                var value = parent.find('.msg-value').text();
                var color = parent.find('.msg-value').attr('_color');
                console.log(color);
                var htmlDlg = '\
          <div class="input-group field_name"  _field="' + field + '"><input type="text" class="form-control field_value" value="' + value + '">\
          <span class="input-group-btn"><select class="form-control field_color" style="width:100px;">\
          <option value="#000000" ' + (color == '#000000' ? 'selected' : '') + '>黑</option>\
          <option value="#ff0000" ' + (color == '#ff0000' ? 'selected' : '') + '>红</option>\
          <option value="#9370db" ' + (color == '#9370db' ? 'selected' : '') + '>紫</option>\
          <option value="#0000ff" ' + (color == '#0000ff' ? 'selected' : '') + '>蓝</option>\
          <option value="#ff1493" ' + (color == '#ff1493' ? 'selected' : '') + '>粉</option>\
          </select></span></div><span style="padding: 5px;display: inline-block;">使用{wx_name}占位符, 发送时会被替换为用户昵称</span>';
                var dialog = Ewin.dialog({
                    btnok: '确定',
                    isbtncl: false,
                    title: "配置模版消息",
                    message: htmlDlg,
                });
                dialog.on(function (result) {
                    if (result) {///确定
                        var obj = $("#" + dialog.id).find('.modal-body');
                        var filed = obj.find('.field_name').attr('_field');
                        var value = obj.find('.field_value').val();
                        var color = obj.find('.field_color').val();
                        _updateField(filed, value, color);
                        _dataTemplate[_index].params = _getFields();
                    }
                });

            });
        }

        function _updateField(filed, value, color) {
            var obj = _editObj.find('.msg-field[_field="' + filed + '"]');
            obj.find('.msg-value').text(value).attr('_color', color).css('color', color);
        }

        function _getFields() {
            var param = [];
            _editObj.find('.msg-field').each(function () {
                var field = $(this).attr('_field');
                var value = $(this).find('.msg-value').text().trim();
                var color = $(this).find('.msg-value').attr('_color');
                if (field && value && color) {
                    param.push({field: field, value: value, color: color});
                }
            });
            return param;
        };

        function _handleContent() {

            var temp = _dataTemplate[_index];
            var content = {
                title: temp.title,
                content: temp.params,
                template_id: temp.template_id,
            }
            $("#tmplate_content").val(JSON.stringify(content));
            $("#template_id").val(temp.template_id);
            return true;
        }

        function _postTemplateMsg() {

            if ($("#title").val().length == 0) {
                $(".tipmsg").html("任务标题不能为空");
                return false;
            }
            //if($("#url").val().length==0){
            //    $(".tipmsg").html("消息跳转链接不能为空");
            //     return false;
            //}
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

            if (_index == -1 || !_dataTemplate || !_dataTemplate[_index].params) {
                $(".tipmsg").html("模版未正确设置");
                return false;
            }

            if (!_handleContent()) {
                return;
            }
            var d = $('#editform').serialize();
            $(".tipmsg").html("");
            api.submitStatus(true, "#msg-submit", '');
            sendData('api?action=save-task', d, function (data) {
                if (data == 'error') {
                    toastr.error('网络繁忙，请稍候再试');
                    api.submitStatus(false, "#msg-submit", '保存');
                    return;
                }
                if (data.err < 0) {
                    toastr.error(data.msg);
                    api.submitStatus(false, "#msg-submit", '保存');
                } else if (data.err == 0) {
                    toastr.success('提交成功');
                    setTimeout(function () {
                        window.location.href = '/templatemsg/list';
                    }, 1500);

                }
            });
        }

        function _testSendMsg() {

            if ($("#test_userid").val().length == 0) {
                toastr.error('粉丝ID不能为空');
                return false;
            }
            if (!/^\d+$/.test($("#test_userid").val())) {
                toastr.error('粉丝ID必须为数字');
                return false;
            }
            if (_index == -1 || !_dataTemplate || !_dataTemplate[_index].params) {
                toastr.error('模版未正确设置');
                return false;
            }

            if (!_handleContent()) {
                return;
            }
            var d = $('#editform').serialize();
            api.submitStatus(true, "#test-submit", '发送中');
            sendData('api?action=test-send', d, function (data) {
                api.submitStatus(false, "#test-submit", '测试发送');
                if (data == 'error') {
                    toastr.error('网络繁忙，请稍候再试');
                    return;
                }
                if (data.err < 0) {
                    toastr.error(data.msg);
                } else if (data.err == 0) {
                    toastr.success('已发送成功,请检查是否收到');
                }
            });
        }
    }

    function template_empty(status) {
        $("#template_edit").hide();
        if (status) {
            $("#template_empty").show();
            $("#template_list").hide();
        } else {
            $("#template_empty").hide();
            $("#template_list").show();
        }

    }

</script>
@endsection
