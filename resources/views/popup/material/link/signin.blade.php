<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加签到链接</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.link.add')}}">
                {{ csrf_field() }}
                <div class="modal-body">

                    <input class="form-control" name="data[wid]" type="hidden" value="{{$wid}}">
                    <input class="form-control" name="data[typeid]" type="hidden" value="3">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">链接:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="href" name="data[href]" type="text" required>
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

