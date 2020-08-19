<div class="modal fade" id="amendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">修改VIP信息</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('vip.passwd.amend')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input class="form-control" id="cid" name="id" type="hidden">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">VIP账户名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="account" type="text" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">密码:</label>
                        <div class="col-xs-12 col-sm-8">
                            @if(Auth::user()->roles->first()->is_admin > 1)
                                <input type="text" class="form-control" id="passwd" name="password" required autocomplete="off">
                            @else
                                <input type="password" class="form-control" id="passwd" name="password" required placeholder="Password" autocomplete="off">
                            @endif
                        </div>
                    </div>

                    <div class="form-group" @if(request()->input('pid') != 3) hidden @endif>
                        <label class="control-label col-xs-12 col-sm-2">来源:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="origin" type="text" name="origin">
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
