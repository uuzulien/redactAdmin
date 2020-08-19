<div class="modal fade" id="amendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">个人号信息修改</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.person.amend')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input class="form-control" name="row[id]" id="person-id" type="hidden">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">微信昵称:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[nick_name]" id="nick-name" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">微信号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[account]" id="account" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">密码:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[password]" id="password" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">好友数量:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[fens_num]" id="fens-num" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">使用用途:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[purpose]" id="purpose" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">归属扫码机:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[scan_moblie]" id="scan-moblie" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">手机号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[phone]" id="phone" type="text" maxlength="11">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">实名姓名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[name]" id="name" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">身份证号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[idcard]" id="idcard" type="text" maxlength="18">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">支付密码:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="row[payment_code]" id="payment-code" type="text">
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

