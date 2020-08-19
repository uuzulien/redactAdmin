<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加素材标题</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.title.add',['type' => request()->input('act', 2)])}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">标题:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="title" name="title" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">消息类型:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="msgtype" name="msg_type">
                                <option value="1">图文消息</option>
                                <option value="2">文本消息</option>
                            </select>
                        </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="control-label col-xs-12 col-sm-2">任务类型:</label>--}}
{{--                        <div class="col-xs-12 col-sm-8">--}}
{{--                            <select class="form-control" autocomplete="off" id="type" name="type">--}}
{{--                                <option value="1">活动</option>--}}
{{--                                <option value="2">小说</option>--}}
{{--                                <option value="3">签到</option>--}}
{{--                                <option value="4">继续阅读</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>

        </div>
    </div>
</div>

