<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加素材标题</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.link.add')}}">
                {{ csrf_field() }}
                <div class="modal-body">

                    <input class="form-control" name="data[wid]" type="hidden" value="{{$wid}}">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">链接:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="href" name="data[href]" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">类型:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="type" name="data[typeid]">
                                <option value="1">活动链接</option>
                                <option value="2">书名链接</option>
                                <option value="3">签到链接</option>
                                <option value="4">继续阅读链接</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group remark">
                        <label class="control-label col-xs-12 col-sm-2">活动说明:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="data[remark]" type="text">
                        </div>
                    </div>
                    <div class="form-group book_style" hidden>
                        <label class="control-label col-xs-12 col-sm-2">书名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="book_id" name="data[book_id]">
                                <option value="0">请选择书名</option>
                            @forelse($books as $key => $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @empty
                                    <option value="0">当前无可选书名</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-group book_style" hidden>
                        <label class="control-label col-xs-12 col-sm-2">章节:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="chapter" name="data[chapter_id]">
                                <option value="0">当前无可选章节</option>
                            </select>
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

