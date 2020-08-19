<div class="modal fade" id="addChapter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加小说数据</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.chapter.add')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">书名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="title" name="name" type="text" readonly>
                            <input class="form-control" id="bookid" name="book_id" type="hidden">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">书号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="num" name="number" type="text" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">章节:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="chapter" name="chapter" type="text" required>
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

