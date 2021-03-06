<div class="modal fade" id="addChapter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加群发消息链接</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.msgtype.update',['typeid' => 2, 'wid' => $wid, 'msgtype' => 2])}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">书名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control title" type="text" readonly>
                            <input class="form-control bookid" name="book_id" type="hidden">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">书号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control num" type="text" readonly>
                        </div>
                    </div>
                    <div class="form-group chapter_num">
                        <label class="control-label col-xs-12 col-sm-2">章节:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" name="chapter_num">
                                <option value="2">第二章</option>
                                <option value="3">第三章</option>
                                <option value="4">第四章</option>
                                <option value="5">第五章</option>
                                <option value="6">第六章</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">链接:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control service-link" name="href" type="text" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>

        </div>
    </div>
</div>

