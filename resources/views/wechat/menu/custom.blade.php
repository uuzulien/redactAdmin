@extends('layouts.app')

@push('custom_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/menu/cymain.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/menu/menu.css') }}" />
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/toastr/toastr.min.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
@endpush
@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>自定义菜单</li>
@endsection

@section('pageTitle')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="main">
                    <div class="container">
                        <div id="wxuserContent">
                            <style>.btnGreens, .btnGreen, .btnGrayS, .a_upload, .a_choose { background-image: none !important; border: none !important; text-shadow: none !important; margin-left: 5px; padding: 2px 8px !important; cursor: pointer !important; display: inline-block !important; overflow: visible !important; border-radius: 2px !important; -moz-border-radius: 2px !important; -webkit-border-radius: 2px !important; background-color: #44b549 !important; color: #fff !important; font-size: 14px !important; /* line-height: 1.5 !important; */ } #version-wrapper { } #version-wrapper h2 { font-size: 18px; margin-bottom: 10px; } #version-wrapper select { width: 300px; } #version-wrapper > .line { display: flex; }</style>
                                <script type="text/javascript">
                                    var originalMenu = '{!! $originalMenu !!}';
                                    var wxusers = JSON.parse('[{"wxname":"爱书室","token":"afttsc1520303723"},{"wxname":"久年以后","token":"tubecb1530772959"},{"wxname":"广益家居","token":"wdludf1531732037"}]');
                                    var currentUser = "afttsc1520303723";
                                    var postURL = "{{route('wechat.menu.save_custom')}}";
                                    var loadURL = "{{route('wechat.menu.get_custom')}}";
                                    var imageProcessURL = "/index.php?m=Diymen&a=upload_image_to_wechat&token=afttsc1520303723";
                                    var lazyReadMenusURL = "{{route('wechat.lazy.menu')}}";

                                    var uploadURL = "/index.php?m=Upyun&a=uploadNew&token=afttsc1520303723";
                                    var fetchURL = "/index.php/material/fetch.html";
                                    var staticURL = "";
                                    var syncURL = "/index.php/material/sync.html";
                                    var syncCheckURL = "/index.php/material/synccheck.html";
                                    var addNewsURL = "/index.php?m=Gallery&a=news_edit&token=afttsc1520303723";
                                    var clearHistoryURL = "/index.php/selfmenu/clear_history.html";
                                </script>

                            <param id="menuid" class="edit-menu" data-id="" change="0" data-count="" data-edit="" />
                            <div class="content" style=" background:none; border:none; margin-bottom:30px; margin-top:30px;">
                                <a name="main"></a>
                                <div id="version-wrapper">
                                    <h2>菜单版本</h2>
                                    <div class="line">
                                        <select class="form-control" id="menu-version">
                                            <option value="37088">2020-01-07 21:12的菜单（使用中）</option>
                                            <option value="36368">2020-01-02 17:35的菜单</option>
                                            <option value="35006">2019-12-10 17:46的菜单</option>
                                            <option value="34581">2019-12-09 17:43的菜单</option>
                                            <option value="31157">2019-11-18 16:58的菜单</option>
                                            <option value="31055">2019-11-14 15:54的菜单</option>
                                            <option value="29593">2019-11-12 16:09的菜单</option>
                                            <option value="28836">2019-11-04 16:50的菜单</option>
                                            <option value="28274">2019-10-28 19:53的菜单</option>
                                            <option value="27543">2019-10-18 18:18的菜单</option>
                                            <option value="27542">2019-10-18 18:17的菜单</option>
                                            <option value="27541">2019-10-18 18:14的菜单</option>
                                            <option value="27476">2019-10-17 20:28的菜单</option>
                                            <option value="24760">2019-08-12 17:39的菜单</option></select>
                                        <button id="getMenuByVersion" class="btn btn-success">读取历史菜单</button>&nbsp;&nbsp;
                                        <button id="clearHistory" class="btn btn-success">一键清除历史菜单</button></div>
                                </div>
                                <hr>
                                <div id="diy-menu">
                                    <div id="diy-menu-left">
                                        <div id="diy-menu-phone">
                                            <div id="diy-menu-info"></div>
                                            <div id="diy-menu-top-bar">
                                                <div id="diy-menu-top-bar-return"></div>
                                                <div id="diy-menu-top-bar-name" class="wechat_name">测试账户</div>
                                                <div id="diy-menu-top-bar-user"></div>
                                                <div class="diy-menu-clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="diy-menu-right">
                                        <div id="diy-panel">
                                            <h2>菜单配置</h2>
                                            <div class="ftip" style="margin:10px auto;padding-top:20px;border-radius:10px;line-height:30px;">注意及时保存菜单配置，菜单只有发布后才会在手机侧显示
                                                <br>微信对于公众号自定义菜单有一定缓存时间，发布菜单后如果想及时看到菜单修改，可以取消关注再重新关注公众号快速地看到新菜单。
                                                <br></div></div>
                                    </div>
                                    <div class="diy-menu-clearfix"></div>
                                </div>
                            </div>
                        </div>

                    </div>

            </div>

        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/resource/image.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/resource/menu.js') }}"></script>
@endsection
