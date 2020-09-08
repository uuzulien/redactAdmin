<div id="mdl1526557124088" class="modal fade in" role="dialog" aria-labelledby="modalLabel" style="z-index: 2000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">选择链接
                    <span style="padding-left: 20px;font-size: 15px;color: #3994C7;"></span></h4>
            </div>
            <div style="    width: 100%;display: inline-block;padding: 5px;">
                <div class="col-sm-6" style="padding: 0">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchlink" name="" value="" placeholder="请输入关键词">
                        <span class="input-group-btn">
              <button type="button" id="link-search-submit" class="btn btn-success">搜索</button>
                        </span>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs nav-justified dropdown-header-link" style="padding: 5px 0 0 0px;" data-type="link">
                <li role="presentation">
                    <a href="javascript:void(0);" role="tab" data-target=".link-tab1" data-toggle="tab">活动</a></li>
                <li role="presentation" class="active">
                    <a href="javascript:void(0);" data-target=".link-tab2" role="tab" data-toggle="tab">书名</a></li>
{{--                <li role="presentation">--}}
{{--                    <a href="javascript:void(0);" data-target=".link-tab3" role="tab" data-toggle="tab">签到</a></li>--}}
                <li role="presentation">
                    <a href="javascript:void(0);" data-target=".link-tab4" role="tab" data-toggle="tab">继续阅读</a></li>
            </ul>
            <div class="modal-body">
                <div role="tabpanel" class="tab-pane fade link-tab1 form-horizontal" aria-labelledby="link-tab1">
                    <div class="select-link-box">
                        <ul>
                            @forelse($res['active_link'] as $link)
                                <a href="javascript:;" class="sales-item " data-type="1" data-link_id="{{$link->id}}" data-url="{{$link->href}}">
                                    <h4 class="sales-item-heading">{{$link->remark}}</h4>
                                    <p class="sales-item-text">{{$link->href}}</p>
                                </a>
                            @empty
                                请先添加活动链接
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade link-tab2 form-horizontal active in " aria-labelledby="link-tab2">
                    <div class="select-link-box">
                        <ul>
                            @forelse($res['novel_link'] as $link)
                                <a href="javascript:;" class="sales-item " data-type="2" data-link_id="{{$link->id}}" data-url="{{$link->href}}">
                                    <h4 class="sales-item-heading">{{$link->name}}</h4>
                                    <p class="sales-item-text">{{$link->href}}</p>
                                </a>
                            @empty
                                请先添加小说链接
                            @endforelse
                        </ul>
                    </div>
                </div>
{{--                <div role="tabpanel" class="tab-pane fade link-tab3 form-horizontal" aria-labelledby="link-tab3">--}}
{{--                    <div class="select-link-box">--}}
{{--                        <ul>--}}
{{--                            @forelse($res['sign_link'] as $link)--}}
{{--                                <a href="javascript:;" class="sales-item " data-type="3" data-url="{{$link->href}}">--}}
{{--                                    <p class="sales-item-text">{{$link->href}}</p>--}}
{{--                                </a>--}}
{{--                            @empty--}}
{{--                                请先添加签到链接--}}
{{--                            @endforelse--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div role="tabpanel" class="tab-pane fade link-tab4 form-horizontal" aria-labelledby="link-tab4">
                    <div class="select-link-box">
                        <ul>
                            @forelse($res['history_link'] as $link)
                                <a href="javascript:;" class="sales-item " data-type="4" data-link_id="{{$link->id}}" data-url="{{$link->href}}">
                                    <p class="sales-item-text">{{$link->href}}</p>
                                </a>
                            @empty
                                请先添加继续阅读链接
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
