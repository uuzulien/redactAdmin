<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加小说账号</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('account.add_novel')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">公众号名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="pf-name" name="pfname">
                                <option value=""></option>
                            </select>
                            <input class="form-control" id="cid" name="id" type="hidden">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">平台来源:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="pt-type" name="pt_type">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">账号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="username" name="username" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">密码:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="passwd" name="passwd" type="text">
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

