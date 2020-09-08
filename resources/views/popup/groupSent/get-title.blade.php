<div id="mdl1526557124086" class="modal fade in" role="dialog" aria-labelledby="modalLabel" style="z-index: 2000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">选择标题
                    <span style="padding-left: 20px;font-size: 15px;color: #3994C7;"></span></h4>
            </div>
            <div style="    width: 100%;display: inline-block;padding: 5px;">
                <div class="col-sm-6" style="padding: 0">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchtitle" name="" value="" placeholder="请输入关键词">
                        <span class="input-group-btn">
              <button type="button" id="title-search-submit" class="btn btn-success">搜索</button>
                        </span>
                    </div>
                </div>
            </div>
{{--            <ul class="nav nav-tabs nav-justified dropdown-header-title" style="padding: 5px 0 0 0px;" data-type="title">--}}
{{--                <li data-index="1" role="presentation">--}}
{{--                    <a href="javascript:void(0);" role="tab" data-target=".title-tab1" data-type="1" data-toggle="tab">活动</a></li>--}}
{{--                <li data-index="2" role="presentation" class="active">--}}
{{--                    <a href="javascript:void(0);" data-target=".title-tab2" role="tab" data-type="2" data-toggle="tab">书名</a></li>--}}
{{--                <li data-index="3" role="presentation">--}}
{{--                    <a href="javascript:void(0);" data-target=".title-tab3" role="tab" data-type="3" data-toggle="tab">签到</a></li>--}}
{{--                <li data-index="4" role="presentation">--}}
{{--                    <a href="javascript:void(0);" data-target=".title-tab4" role="tab" data-type="4" data-toggle="tab">继续阅读</a></li>--}}
{{--            </ul>--}}
            <div class="modal-body">
                <div role="tabpanel" class="tab-pane fade title-tab1 form-horizontal active in " aria-labelledby="title-tab1">
                    <div class="select-title-box">
                        <ul>
                        </ul>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade title-tab2 form-horizontal" aria-labelledby="title-tab2">
                    <div class="select-title-box">
                        <ul>
                        </ul>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade title-tab3 form-horizontal" aria-labelledby="title-tab3">
                    <div class="select-title-box">
                        <ul>
                        </ul>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade title-tab4 form-horizontal" aria-labelledby="title-tab4">
                    <div class="select-title-box">
                        <ul>
{{--                            @forelse($res['history_title'] as $item)--}}
{{--                                <li data-type="4" data-index="{{$item->id}}">{{$item->title}}</li>--}}
{{--                            @empty--}}
{{--                                没有数据--}}
{{--                            @endforelse--}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
