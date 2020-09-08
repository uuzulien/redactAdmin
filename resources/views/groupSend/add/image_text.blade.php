@extends('layouts.app')

@push('custom_css')
    <meta name="referrer" content="never">
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
<style>
    .multi .appmsg_item:after {
        content: "\200B";
        display: block;
        height: 0;
        clear: both;
    }
    .sub_card_media{
        /*padding: 3px;*/
        position: relative;
    }
    .sub_card_media:before {
        content: " ";
        position: absolute;
        left: 15px;
        right: 78px;
        top: 0;
        height: 1px;
        background-color: #e4e8eb;
    }
    .appmsg-side__wrapper {
        box-shadow: 0 0 8px 0 rgba(229,229,229,0.5);
        margin: 0;
        position: relative;
        width: 310px;
    }
    .appmsg {
        overflow: visible;
        margin-bottom: 0;
        border: 0;
        position: relative;
        background-color: #fff;
        color: #666;
    }
    .multi .appmsg_content {
        padding: 0;
    }
    .appmsg_item_wrp.current {
        position: relative;
        z-index: 1;
    }
    .appmsg_item_wrp {
        cursor: pointer;
    }
    .appmsg_item_wrp.current .appmsg_item {
        /*border-top-width: 0;*/
        border: 2px solid #07c160;
    }
    .appmsg-side__wrapper .multi .appmsg_item {
        /*border-top: 0;*/
        padding: 0;
    }
    .appmsg_item_wrp .card_container .card_appmsg_inner {
        padding-top: 50.8%;
    }
    .card_appmsg_inner {
        position: relative;
    }
    .weui-desktop-vm_primary {
        width: 2000px;
    }
    .appmsg_item_wrp .card_appmsg_title {
        display: -webkit-box;
    }
    .appmsg_item_wrp.sub_card_media {
        padding: 0;
    }
    .card_appmsg_title {
        position: absolute;
        left: 15px;
        right: 15px;
        bottom: 15px;
        overflow: hidden;
        text-overflow: ellipsis;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        color: #fff;
        font-weight: 400;
        z-index: 1;
    }
    .weui-desktop-vm_primary {
        display: table-cell;
        vertical-align: middle;
        word-break: break-all;
    }
    .weui-desktop-vm_default {
        white-space: nowrap;
    }
    .appmsg_item_wrp.sub_card_media .card_appmsg_thumb {
        width: 48px;
        height: 48px;
    }
    .appmsg_item_wrp .card_appmsg_thumb {
        width: auto;
        height: auto;
        background-color: #e1e1e1;
    }
    .card_appmsg_thumb {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-size: cover;
        background-position: 50% 50%;
        background-repeat: no-repeat;
    }

    .create_access_primary {
        border-top-width: 0;
        line-height: 90px;
        border: 0;
        position: relative;
        margin-bottom: 0;
        border-radius: 0 0 5px 5px;
        background: #fff;
        display: block;
        font-size: 0;
        text-align: center;
    }
    .create_access_primary:before {
        content: " ";
        position: absolute;
        left: 15px;
        right: 78px;
        top: 0;
        height: 1px;
        background-color: #e4e8eb;
    }
    .preview_media_add_wrp {
        display: inline-block;
        vertical-align: middle;
        font-size: 14px;
        position: relative;
    }
    /*.preview_media_add_wrp .preview_media_add_middle {*/
    /*    line-height: 20px;*/
    /*}*/
    .icon20_common.add_gray {
        position: relative;
        top: -2px;
        vertical-align: middle;
        display: inline-block;
        background: transparent url('https://mp.weixin.qq.com/mpres/htmledition/images/icon/common/icon20_add4a184f.svg') no-repeat 0 0;
        background-size: cover;
    }
    .create_access_primary i {
        cursor: pointer;
        line-height: 300px;
        overflow: hidden;
    }
    .icon20_common {
        width: 20px;
        height: 20px;
    }
    .preview_media_add_wrp .preview_media_add_word {
        color: #9a9a9a;
        display: inline-block;
        margin-left: 2px;
    }
    .appmsg_item_wrp .card_appmsg_title {
        display: -webkit-box;
    }
    .sub_card_media .card_appmsg_title {
        color: #353535;
    }
    .sub_card_media .card_appmsg_title {
        position: static;
    }
    .appmsg_item_wrp.sub_card_media .card_container{
        padding: 12px;
    }
    .appmsg_item_wrp .card_appmsg_thumb {
        width: auto;
        height: auto;
        background-color: #e1e1e1;
    }

    .sub_card_media .weui-desktop-vm_default {
        padding-left: 15px;
    }
    .appmsg_item_wrp.sub_card_media .card_appmsg_inner {
        padding-top: 0;
    }
    .sub_card_media .card_appmsg_thumb {
        position: relative;
    }
    .sub_card_media .card_appmsg_inner {
        padding: 0;
    }
    .weui-desktop-vm_default, .weui-desktop-vm_primary {
        display: table-cell;
        vertical-align: middle;
        word-break: break-all;
    }





    .preview_media_add_panel_container_wrp {
        z-index: 117;
        position: absolute;
    }
    .preview_media_add_panel_container {
        display: flex;
        -webkit-box-pack: start;
        justify-content: flex-start;
    }
    .preview_media_add_panel_container .preview_media_add_panel_opr_wrp {
        padding: 15px 7px;
        margin: 0 10px;
        height: 100%;
        background-color: #fff;
        box-shadow: 0 1px 6px 0 #e4e8eb;
        border-radius: 25px;
    }
    .preview_media_add_panel_container .preview_media_add_panel_opr_item.first_item {
        margin-top: 0;
    }
    .preview_media_add_panel_container .preview_media_add_panel_opr_item {
        position: relative;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        -webkit-box-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        align-items: center;
    }
    a {
        text-decoration: none;
        color: #576b95;
    }
    .preview_media_add_panel_container .preview_media_add_panel_opr_item .weui-desktop-tooltip.weui-desktop-tooltip__left-center {
        bottom: auto;
        left: -30%;
        width: 3.5em;
        top: 6px;
        transform: translateX(-100%);
    }
    .preview_media_add_panel_container .preview_media_add_panel_opr_item .weui-desktop-tooltip {
        padding: 0 8px;
        margin-bottom: 10px;
        font-size: 12px;
        cursor: initial;
        white-space: pre;
        background: rgba(0,0,0,0.6);
        border-radius: 3px;
        color: #FFF;
        line-height: 2;
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        position: absolute;

        opacity: 1;
        visibility: visible;
        z-index: 50000;
    }
    .weui-desktop-tooltip.weui-desktop-tooltip__left-center, .weui-desktop-tooltip.weui-desktop-tooltip__left-top, .weui-desktop-tooltip.weui-desktop-tooltip__left-bottom {
        /* top: 50%; */
        right: 100%;
        /* bottom: auto; */
        /* left: auto; */
        margin: 0;
        margin-right: 10px;
        -ms-transform: translateY(-50%);
        /* transform: translateY(-50%); */
    }
    .weui-desktop-tooltip.weui-desktop-tooltip {
        /* opacity: 0; */
        /* visibility: hidden; */
        /* -webkit-transition: all .2s .2s; */
        transition: all .2s .2s;
    }
    .preview_media_add_panel_container .weui-desktop-icon-btn__up_icon {
        background: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cg fill='none' fill-rule='evenodd'%3E %3Cpath d='M0 0h24v24H0z'/%3E %3Cpath fill='%234A4A51' fill-rule='nonzero' d='M10.886 4.764v15.781c0 .121.098.219.219.219h1.09a.219.219 0 0 0 .219-.219V4.764h-1.528z'/%3E %3Cpath fill='%234A4A51' fill-rule='nonzero' d='M11.944 3.163l5.891 6.45a.5.5 0 0 1-.65.75L11.856 6.74a.5.5 0 0 0-.562 0l-5.329 3.623a.5.5 0 0 1-.65-.75l5.89-6.45a.5.5 0 0 1 .74 0z'/%3E %3C/g%3E%3C/svg%3E") no-repeat center center;
        background-size: contain;
    }
    .preview_media_add_panel_container .weui-desktop-icon-btn__down_icon {
        background: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cg fill='none' fill-rule='evenodd' transform='matrix(1 0 0 -1 0 24)'%3E %3Cpath d='M0 0h24v24H0z'/%3E %3Cpath fill='%234A4A51' fill-rule='nonzero' d='M10.886 4.764v15.781c0 .121.098.219.219.219h1.09a.219.219 0 0 0 .219-.219V4.764h-1.528z'/%3E %3Cpath fill='%234A4A51' fill-rule='nonzero' d='M11.944 3.163l5.891 6.45a.5.5 0 0 1-.65.75L11.856 6.74a.5.5 0 0 0-.562 0l-5.329 3.623a.5.5 0 0 1-.65-.75l5.89-6.45a.5.5 0 0 1 .74 0z'/%3E %3C/g%3E%3C/svg%3E") no-repeat center center;
        background-size: contain;
    }
    .preview_media_add_panel_container .weui-desktop-icon-btn__delete_icon {
        width: 22px;
        height: 22px;
        background: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E %3Cg fill='none' fill-rule='evenodd'%3E %3Cpath d='M0 0h24v24H0z'/%3E %3Cg transform='translate(1 1)'%3E %3Cpath d='M0 0h22v22H0z'/%3E %3Cpath fill='%234A4A51' fill-rule='nonzero' stroke='%234A4A51' stroke-width='.2' d='M6.21 5.867l.744 12.51c.023.387.344.69.732.69h6.628a.733.733 0 0 0 .732-.69l.745-12.51H6.209zm10.683 0l-.749 12.575a1.833 1.833 0 0 1-1.83 1.725H7.686c-.97 0-1.773-.756-1.83-1.725L5.107 5.867H3.208v-.642c0-.253.206-.458.459-.458h14.666c.253 0 .459.205.459.458v.642h-1.9zm-4.06-3.117c.253 0 .459.205.459.458v.642H8.708v-.642c0-.253.206-.458.459-.458h3.666zm-4.125 5.5h1.1l.459 8.25h-1.1l-.459-8.25zm3.484 0h1.1l-.459 8.25h-1.1l.459-8.25z'/%3E %3C/g%3E %3C/g%3E%3C/svg%3E") no-repeat center center;
        background-size: contain;
    }
    .preview_media_add_panel_container .preview_media_add_panel_opr_item.current {
        background-color: #ededed;
    }
    .preview_media_add_panel_container .weui-desktop-icon-btn {
        width: 20px;
        height: 20px;
    }
    .weui-desktop-icon-btn {
        padding: 0;
        border-width: 0;
        vertical-align: middle;
        font-size: 0;
        display: inline-block;
        cursor: pointer;
    }

</style>

@section('pageTitle')
    <div class="page-title">

    </div>
@endsection

@section('content')
    <!-- 点击修改 -->
    @include('popup.imgtext.link')
    <!-- 选择标题 -->
    @include('popup.imgtext.get-title')
    <!-- 图片选择框 1 -->
    @include('popup.imgtext.image')
    <!-- 选择链接 1 -->
    @include('popup.imgtext.get-link')
    <!-- 插入链接 1 -->
{{--        @include('popup.groupSent.insert.link')--}}

{{--    <!-- 插入活动 -->--}}
{{--        @include('popup.groupSent.insert.active')--}}



    <!-- 添加标题 1 -->
{{--        @include('popup.groupSent.add-title')--}}


    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body" style="background-color: rgb(243, 245, 248);">
                <div role="tabpanel" class="tab-pane active" style="padding:10px 0">
                    <form class="form-horizontal" id="editform">
                        <div class="main0 col-sm-12" style="background:#fff;margin-left: 0;padding: 10px 10px 0 10px;margin-bottom:20px;">
                            <div class="form-group" style="padding: 10px 0;background:#fff;margin: 0">
                                <div class="col-sm-12">
                                    <span>同步到其它帐号：</span>
                                    <span class="btn btn-sm btn-info use-checkbox">更新</span>
                                    <label class="control-inline fancy-checkbox"><input type="checkbox" name="" id="select_channel" value=""><span>全选/反选所有</span></label>
                                    <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="批量客服消息只能添加，不能修改，创建结束后，修改任一一条消息都与其它同步消息互不影响"></i>
                                </div>

                                <div class="form-group col-md-12 channel-all-checkedbox">
{{--                                                                        @foreach($vipall as $value)--}}
{{--                                                                            <label class="control-inline fancy-checkbox-list fancy-checkbox">--}}
{{--                                                                                <input type="checkbox" name="vipall[]" class="channel" id="id[]" value="{{$value->id}}">--}}
{{--                                                                                <span>{{$value->nick_name}}</span>--}}
{{--                                                                            </label>--}}
{{--                                                                        @endforeach--}}
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
                                        {{--                                        @foreach($platforms as $val)--}}
                                        {{--                                            <option value="{{$val->id}}">{{$val->platform_name}}</option>--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                </div>

                                <div class="col-sm-6" style="line-height: 34px;">推荐名称规则：日期+群发类型+推送内容+操作人
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="remark" style="text-align: left;" class="col-sm-1 control-label">发送时间</label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control datetimepicker" id="send_time" name="data[send_time]" placeholder="" value="{{$item->send_time ?? null}}" autocomplete="off">
                                </div>
                                <div class="form-group col-md-8" style="margin: 20px 0 0 0 ;background:#fff;width: 100%;border-top: 1px solid #eee;padding-top: 10px;">
                                    【<a href="javascript:;" onclick="setDataValue(10,'minutes')">10分钟后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(30,'minutes')">30分钟后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(1,'hours')">1小时后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(2,'hours')">2小时后</a>】
                                    【<a href="javascript:;" onclick="setDataValue(3,'hours')">3小时后</a>】
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
                                    <div class="col-sm-12 " id="tmplate_msgtype_text" style="display: none;">
                                        <div class="form-group" style="margin:0 0 10px 0"><textarea style="height:400px;" class="form-control" id="temp_text" name="data[temp_text]" placeholder="请填写消息内容,限600个字，1200个字符" ></textarea>
                                        </div>
                                        <span class="inputtip">使用{wx_name}占位符, 发送时会被替换为用户昵称</span>
                                        <div data-toggle="modal" data-target="#mdl1589359911750"><a href="javascript:void(0);" style="display: inline-block;    padding: 5px 10px;    border: 1px solid #eaeaea;    border-radius: 5px;">+插入链接</a></div>
                                    </div>
                                    <!--文本消息 END -->
                                    <!--图文消息ex -->
                                    <div class="col-sm-102 news" id="tmplate_msgtype_news" style="padding: 0px;">
                                        <div class="box">
                                            <div>
                                                <span class="msg-title top-title">标题</span>
                                                <a href="javascript:void(0);" title="点击修改">
                                                    <i class="fa fa-edit" aria-hidden="true" data-toggle="modal" data-target="#mdl1589359911750"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <span class="msg-url top-url color-red">链接未配置</span>                                                </div>
                                            <div style="margin-top:10px;display: flex">
                                                <div class="form-group" style="flex:1;margin:0 10px 0px 0"><textarea style="height:100px;" class="form-control top-content" id="temp_content" placeholder="图文简介"></textarea>
                                                </div>
                                                <div class="msg-picurl" style="height: 100px;" data-toggle="modal" data-target="#mdl152755712400"><img class="msg-img  top-picurl" src="">
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
                            <div class="form-group">

                                <div class="col-sm-12">
                                    <div class="appmsg-side__wrapper">
                                        <div class="appmsg multi has_first_cover editing">
                                            <div id="js_appmsg_preview" class="appmsg_content" style="max-height: 500px;overflow: hidden;overflow-y: auto;">
                                                <div id="appmsgItem" data-fileid="" data-id="" data-msgindex="0" class="js_appmsg_item appmsg_item_wrp has_thumb current">
                                                    <div class="appmsg_item" title="第一篇图文">
                                                        <div class="card_appmsg card_container">
                                                            <div class="card_appmsg_inner">
                                                                <div class="weui-desktop-vm_primary card_appmsg_hd">
                                                                    <strong class="card_appmsg_title js_appmsg_title">标题</strong></div>
                                                                <div class="weui-desktop-vm_default card_appmsg_bd">
                                                                    <div class="card_appmsg_thumb js_appmsg_thumb" data-url="" data-link_id=""></div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="add_appmsg_container" style="" class="create_access_primary">
                                            <div class="preview_media_add_wrp js_readonly">
                                                <div id="js_add_appmsg" class="preview_media_add_middle" data-action="add">
                                                    <i class="icon20_common add_gray">增加一条</i>
                                                    <span class="preview_media_add_word">新建消息</span></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
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

                        <span class="control-label col-sm-3" style="text-align: left;line-height: 34px;">用户必须关注本号</span>
                        <span class="control-label col-sm-2 green tipmsg" style="line-height: 34px;">&nbsp;</span>
                        <button type="button" id="msg-submit" class="col-sm-2 btn btn-success" style="width:100px;">保存
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="preview_media_add_panel_container_wrp js_action_container" style="top: 496.469px; left: 1140px; display: none;">
        <div class="preview_media_add_panel_container">
            <div class="preview_media_add_panel_opr_wrp js_appmsg_action">

                <div class="preview_media_add_panel_opr_item js_action_btn js_up first_item" style="display: flex;">
                    <div class="weui-desktop-icon-btn__up">
                        <a href="javascript:;">
                            <div class="weui-desktop-icon-btn weui-desktop-icon-btn__up_icon"></div>
                        </a>
                        <span class="weui-desktop-tooltip weui-desktop-tooltip__left-center js_tooltips" style="display: none;">上移</span>
                    </div>
                </div>

                <div class="preview_media_add_panel_opr_item js_action_btn js_down" style="display: flex;">
                    <div class="weui-desktop-icon-btn__down">
                        <a href="javascript:;">
                            <div class="weui-desktop-icon-btn weui-desktop-icon-btn__down_icon"></div>
                        </a>
                        <span class="weui-desktop-tooltip weui-desktop-tooltip__left-center js_tooltips" style="display: none;">下移</span>
                    </div>
                </div>

                <div class="preview_media_add_panel_opr_item js_action_btn js_del" style="display: none;">
                    <div class="weui-desktop-icon-btn__down">
                        <a href="javascript:;" id="js_del_title">
                            <div class="weui-desktop-icon-btn weui-desktop-icon-btn__delete_icon"></div>
                        </a>
                        <span class="weui-desktop-tooltip weui-desktop-tooltip__left-center js_tooltips" style="display: none;">删除</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/comm.min.js') }}"></script>
    <script>
        // // 图文消息 -> 标题选择
        $('#mdl1589359911750').on('show.bs.modal', function (event) {
            var model = $(this),
                title = $('.top-title').text() || '标题',
                link = '链接未配置',
                temp_link = $('.top-url').text() != link ? $('.top-url').text() : '链接地址';

            model.find('.temp_link').val(temp_link);

            model.find('#img_text_title').val(title);

            // 关闭事件
            model.click(function (e) {
                title = model.find('#img_text_title').val() || title;
                link = model.find('#temp_link').val() || link;
                if (e.target.classList.contains('ok')){
                    $('.msg-title').text(title);
                    $('.msg-url').text(link);
                    $('#title').val(title);
                    $('.current .js_appmsg_title').text(title);
                    invail_channel = true;
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
                $('.current .card_appmsg_thumb').css("background-image", "url(" + img_href + ")");
                model.modal('hide');
                e.preventDefault();
            })
        });

        function sendData(t, i, a) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
        };
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
        var erro_msg;
        var invail_channel;
        function _getNewsData() {

            var json = [];
            erro_msg = null;
            $('.js_appmsg_item').each(function () {
                var _title = $(this).find('.js_appmsg_title').text(),
                    _link_id = $(this).find('.card_appmsg_thumb').data('link_id'),
                    _link = $(this).find('.card_appmsg_thumb').data('url'),
                    _src = $(this).find('.js_appmsg_thumb').css('background-image');

                if (_src.indexOf("url") !== -1){
                    _src = _src.split('("')[1].split('")')[0];
                }

                if (_title.length < 5){
                    erro_msg = '标题不符合要求';
                    return false;
                }

                if (!_link_id){
                    erro_msg = '链接不存在';
                    return false;
                }
                if (_src.indexOf('http') == -1){
                    erro_msg = '封面图片不符合要求';
                    return false;
                }
                json.push({
                    title: _title,
                    link_id: _link_id,
                    href: _link,
                    src: _src
                });
            });

            return JSON.stringify({'sub_item': json});

        }
        function getNewsArr() {

            var json = [];
            $('.js_appmsg_item').each(function () {
                var _link = $(this).find('.card_appmsg_thumb').data('link_id');

                json.push({
                    link_id: _link,
                });
            });

            return JSON.stringify(json);

        }


        function _postTemplateMsg(){
            var msgtype = $("#msgtype input:radio:checked").val();

            if (msgtype == '1') {
                var data = _getNewsData();
            } else {
                var data = '';
            }

            if (erro_msg){
                toastr.error(erro_msg);
                return false;
            }

            if (selectChannelAll.getCheckNum()>0 && invail_channel){
                toastr.error('请更新可同步的公众号！');
                return false;
            }

            var d = $('#editform').serialize();
            d += "&data[dataInfo]=" + encodeURIComponent(data);

            sendData('{{route('wechat.image_text.save')}}', d, function (data) {
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
                        window.location.href = '/group_send/list/imagetext';
                    }, 1500);

                }
            });
        };

        function _testSendMsg() {
            var msgtype = $("#msgtype input:radio:checked").val();
            if (msgtype == '0' && $("#temp_text").val().length == 0) {
                toastr.error("消息内容不能为空");
                return false;
            }
            if (msgtype == '1') {
                var data = _getNewsData();
            } else {
                var data = '';
            }
            if ($("#test_userid").val().length == 0) {
                toastr.error('粉丝ID不能为空');
                return false;
            }
            if (!/^\d+$/.test($("#test_userid").val())) {
                toastr.error('粉丝ID必须为数字');
                return false;
            }
            if (erro_msg){
                toastr.error(erro_msg);
                return false;
            }

            var d = $('#editform').serialize();
            d += "&data[test_user]=" +  $("#test_userid").val() + "&data[dataInfo]=" + encodeURIComponent(data);
            api.submitStatus(true, "#test-submit", '发送中');
            sendData('{{route('wechat.image_text.test_send')}}', d, function (data) {
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

        var AppmsgContainer = new function (){
            var that = this;


            this.init = function () {
                $('#js_appmsg_preview').on('click','.appmsg_item_wrp', function (event) {
                    var _this = $(this);

                    that.syncLoadData(_this);
                    that.actionFlex(_this);
                    that.toggle(_this);
                    event.preventDefault();
                    that.deltitle(_this);

                });
                $('#add_appmsg_container').bind('click',function (event) {
                    var _count = $('#js_appmsg_preview').children().length + 1;

                    var content = `<div id="appmsgItem" data-fileid="" data-id="" data-msgindex="${_count}" class="js_appmsg_item appmsg_item_wrp has_thumb sub_card_media">
                                                    <div class="appmsg_item" title="第二篇图文">
                                                        <div class="card_appmsg card_container">
                                                            <div class="card_appmsg_inner">
                                                                <div class="weui-desktop-vm_primary card_appmsg_hd">
                                                                    <strong class="card_appmsg_title js_appmsg_title">标题${_count}</strong></div>
                                                                <div class="weui-desktop-vm_default card_appmsg_bd">
                                                                    <div class="card_appmsg_thumb js_appmsg_thumb" data-link_id=""></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;

                    $('#js_appmsg_preview').append(content);
                    if (_count >= 8){
                        $(this).hide();
                    }
                    event.preventDefault();
                });
                $("#msg-submit").bind('click',function(event){
                    event.preventDefault();
                    _postTemplateMsg();
                });
                $("#test-submit").bind('click',function(event){
                    event.preventDefault();
                    _testSendMsg();
                });

            };
            // 删除信息
            this.deltitle = function (_click) {
              $('.js_del').unbind('click').bind('click', function (event) {
                  _click.remove();
              });
            };
            // 切换当前选中
            this.toggle = function (_click) {
                $('.appmsg_item_wrp.current').removeClass('current');
                _click.addClass('current');
            };
            // 召唤可操作按钮
            this.actionFlex = function (_click) {
                if (_click.hasClass('sub_card_media')){
                    $('.js_del').show();
                }else {
                    $('.js_del').hide();
                }
                var width_c = _click.offset().left + 10;
                var height_c = _click.offset().top + 10;
                $('.js_action_container').css({'left':width_c, 'top': height_c,'display': 'block'})
            };
            // 同步数据至消息类型中
            this.syncLoadData = function (_click) {
                var _bk = _click.find('.js_appmsg_thumb').css('background-image'),
                    _src = '',
                    _url = _click.find(".card_appmsg_thumb").data('url') || '链接未配置';

                if (_bk.indexOf("url") !== -1){
                    _src = _bk.split('("')[1].split('")')[0];
                }
                $('.top-title').text(_click.find('.js_appmsg_title').text()); // 标题
                $('.top-picurl').attr('src', _src); // 图片
                $('.top-url').text(_url); // 链接
            }
        };
        var __TitleList = @json($titleInfo);

        var selectTitle = new function(){
            var _dialogid = 'mdl1526557124086';
            var that = this;

            this.init = function () {
                setTimeout(function () {
                    that.loadData();
                }, 100);
            };
            // 绑定点击事件
            this.bind = function () {
                $('#searchtitle').keypress(function (e) {
                    if (e.keyCode == 13) {
                        that._showSearch();
                    }
                });
                $("#title-search-submit").unbind('click').bind('click', function (event) {
                    event.preventDefault();
                    that._showSearch();
                });
            };
            // 设置标题
            this.setTitle = function (title) {
                $("#img_text_title").val(title);
            };
            // 隐藏当前对话框
            this.hide = function () {
                $("#" + _dialogid).modal('hide');
            };
            // 加载数据
            this.loadData = function (key=null,action='search') {
                var html = '';
                $(".select-title-box li").unbind('click');
                for (var index = 0; index < __TitleList.length; index++) {
                    var item = __TitleList[index];
                    // 如果key不存在，进行初始化
                    if (action == 'search' && key) {
                        if (item.title.indexOf(key) !== -1) {
                            html += '<li data-type="' + item.categoryid + '" data-index="' + item.id + '">' + item.title + '</li>';
                        }
                    }else{
                        html += '<li data-type="' + item.categoryid + '" data-index="' + item.id + '">' + item.title + '</li>';
                    }

                }
                $(".select-title-box ul").html(html);
                $(".select-title-box li").bind('click', function (event) {
                    event.preventDefault();
                    that.setTitle($(this).text());
                    that.hide();
                });
            };
            // 展示搜索后的内容
            this._showSearch = function () {
                var key = $("#searchtitle").val();
                that.loadData(key);
            };
        };
        // 链接选择
        var selectLink = new function(){
            var _dialogid = 'mdl1526557124088';
            var that = this;

            this.init = function () {
                setTimeout(function () {
                    that.loadData();
                }, 100);
                // that._dropdownMenuTabEvent();
            };
            // 绑定点击事件
            this.bind = function () {
                $('#searchtitle').keypress(function (e) {
                    if (e.keyCode == 13) {
                        that._showSearch();
                    }
                });
                $("#link-search-submit").unbind('click').bind('click', function (event) {
                    event.preventDefault();
                    that._showSearch();
                });
            };
            // 设置链接
            this.setLink = function (href, link_id) {
                $("#temp_link").val(href);
                $(".current .card_appmsg_thumb").data('url', href);
                $(".current .card_appmsg_thumb").data('link_id', link_id);
            };
            // 隐藏当前对话框
            this.hide = function () {
                $("#" + _dialogid).modal('hide');
            };
            // 加载数据
            this.loadData = function (key=null,action='search') {
                // var html = '';
                // $(".select-link-box li").unbind('click');
                // for (var index = 0; index < __TitleList.length; index++) {
                //     var item = __TitleList[index];
                //     // 如果key不存在，进行初始化
                //     if (action == 'search' && key) {
                //         if (item.title.indexOf(key) !== -1) {
                //             html += '<li data-type="' + item.categoryid + '" data-index="' + item.id + '">' + item.title + '</li>';
                //         }
                //     }else{
                //         html += '<li data-type="' + item.categoryid + '" data-index="' + item.id + '">' + item.title + '</li>';
                //     }
                //
                // }
                // $(".select-link-box ul").html(html);
                $(".select-link-box a").bind('click', function (event) {
                    event.preventDefault();
                    that.setLink($(this).data('url'),$(this).data('link_id'));
                    that.hide();
                });
            };
            // 展示搜索后的内容
            this._showSearch = function () {
                var key = $("#searchlink").val();
                that.loadData(key);
            };
            this._dropdownMenuTabEvent = function () {
                $(".dropdown-header-link").bind('click', function (event) {
                    event.stopPropagation();
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
            };
            this.getCheckNum=function(){
                var total=0;
                $('.channel-all-checkedbox input').each(function(index,element){
                    total+=$(this).prop('checked')?1:0;
                });
                return total;
            };
            this.clearCheck=function(){
                _checkedItem(false);
            };
            function _checkedItem(checked){
                $('.channel-all-checkedbox input').prop('checked',checked);
            }
        };

        var vip_all = @json($vipall);

        // 更新可同步账号
        var useChannelAll = new function(){
            var that = this;
            this.init = function () {
                $(".use-checkbox").bind('click', function (event) {
                    invail_channel = false;
                    that.getUseChannelData();
                });
            };
            this.getUseChannelData = function () {
                var _arr = getNewsArr();
                $.get('/api/getChannel/datas/' + encodeURIComponent(_arr), function (e) {
                    var res = e.data;
                    that.updateAccount(res);
                })
            };
            this.updateAccount = function (arr) {
                var content = '';
                var pid = $('#pid option:selected').val();
                for(var key = 0; key < vip_all.length; key++){
                    var val = vip_all[key];
                    if (arr == []){
                        continue;
                    }
                    if (pid != val['pid']  && pid != 0){
                        continue;
                    }
                    if (arr.indexOf(val['id']) == -1){
                        continue;
                    }

                    content += `<label class="control-inline fancy-checkbox-list fancy-checkbox">
                    <input type="checkbox" name="vipall[]" class="channel" value="${val['id']}">
                    <span>${val['nick_name']}</span>
                    </label>`;
                }
                $('.channel-all-checkedbox').html(content);

            };

        };


        $(function () {
            AppmsgContainer.init();
            selectTitle.init();
            selectLink.init();
            useChannelAll.init();
            selectChannelAll.init();
        });


    </script>

@endsection
