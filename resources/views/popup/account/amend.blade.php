<div class="modal fade" id="amendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">修改小说账号</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('account.config.amend')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">公众号名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="pfname" name="pfname" type="text" readonly>
                            <input class="form-control" id="cid" name="id" type="hidden">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">平台来源:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="ptype" name="pt_type">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="control-label col-xs-12 col-sm-2">小说管理者:</label>--}}
{{--                        <div class="col-xs-12 col-sm-8">--}}
{{--                            <select class="form-control" autocomplete="off" id="user_id" name="user_id" readonly="">--}}
{{--                                <option value=""></option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">账号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="username" name="username" type="text" autocomplete="off" @if(Auth::user()->userRole->first()->is_admin < 1) readonly @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">密码:</label>
                        <div class="col-xs-12 col-sm-8">
                            @if(Auth::user()->userRole->first()->is_admin > 1)
                                <input type="text" class="form-control" id="passwd" name="passwd" required autocomplete="off">
                            @else
                                <input type="password" class="form-control" id="passwd" name="passwd" required placeholder="Password" autocomplete="off">
                            @endif

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

