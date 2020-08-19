<div class="modal fade" id="SwitchModal" tabindex="-1" role="dialog" aria-labelledby="trendModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">请选择您要操作的公众号</h4></div>
            <div class="modal-body">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="cut-header ng-scope" style="margin-top:5px;margin-bottom:5px;">
                            <div class="form-inline">
                                <div class="form-group  has-success has-feedback">
                                    <div class="input-group">
                                        <span class="input-group-addon">微</span>
                                        <input type="text" class="form-control" value="" name="nick" id="wechat_nick"  placeholder="请输入微信公众号名称">
                                        <span class="input-group-btn"><button id="btn" class="btn btn-default button"  style="height: 36px;"><i class="fa fa-search"></i></button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cut-list clearfix" id="wechat_list" style="margin-top:10px;">
{{--                            @forelse($wechatInfoList as $key => $item)--}}
{{--                                <div class="item ng-scope show-{{$key}}" style="width:123px;height:150px;">--}}
{{--                                    <div class="content">--}}
{{--                                        <img class="icon-account" src="{{$item['head_img']}}?pk=1594870707812" style="margin:15px 0 5px 0;width:80px;height:80px;">--}}
{{--                                        <div class="name ng-binding">{{$item['nick_name']}}</div>--}}
{{--                                        <div class="type">--}}
{{--                                            <span class="ng-scope">{{$item['verify_type']}}({{[0=>'未授权',1=>'已授权'][$item['is_power']] ?? '未知'}})</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="mask">--}}
{{--                                        <a class="entry" href="{{route('wechat.account.switch',['id' => $item['id']])}}">--}}
{{--                                            <div>进入公众号&nbsp;<i class="fa fa-angle-right"></i></div>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @empty--}}
{{--                                没有数据--}}
{{--                            @endforelse--}}

                    </div>
                </div>

            </div>
        </div>

        </div>
    </div>
</div>
