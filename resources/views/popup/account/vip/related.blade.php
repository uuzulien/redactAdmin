<div class="modal fade" id="relatedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">关联子账户</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('vip.account.related')}}">
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
                        <label class="control-label col-xs-12 col-sm-2">子账户:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control selectpicker" autocomplete="off" id="subinfo" name="sub_id[]" multiple>
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