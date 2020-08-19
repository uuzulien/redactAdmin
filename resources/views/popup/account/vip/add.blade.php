<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加小说账号</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('vip.config.add')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">平台来源:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="pid" name="pid">
                                @foreach($platforms as $val)
                                <option value="{{$val->id}}">{{$val->platform_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">VIP账号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="username" name="account" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">密码:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="passwd" name="password" type="text">
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
