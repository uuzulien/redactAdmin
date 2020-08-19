<div class="modal fade" id="refuse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    ×
                </button>
                <h4 class="modal-title">拒绝原因</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.title.update', ['status' => 2])}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                    <input class="tid" name="id" type="hidden" value="">
                                    <p  style="margin: 10px 10px">
                                        <font color="red">*</font>
                                        <label class="control-label">拒绝原因:</label>
                                    </p>
                                    <textarea id="notice" class="form-control rounded-0" name="msg"  rows="12" required></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        取消
                    </button>
                    <button type="submit" class="btn btn-primary quick_refuse_reason">
                        确定
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

