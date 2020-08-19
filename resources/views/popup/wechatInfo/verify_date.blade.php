<div class="modal fade" id="verifyDate" tabindex="-1" role="dialog" aria-labelledby="trendModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="z-index: 19891015; width: 600px; height: 340px; top: 160px; left: 150px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">认证到期时间
                    <span style="padding-left: 20px;font-size: 15px;color: #3994C7;"></span></h4>
            </div>
            <div class="modal-body tab-content">
                <form class="form-horizontal" method="post" action="{{route('wechat.date.verify')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-3">公众号名称:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <input class="form-control" id="name" type="text" readonly>
                                                <input class="form-control" id="wid" name="id" type="hidden">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-3">认证日期:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <input type="text" class="form-control datetimepicker" id="verify_date" name="verify_date" placeholder="开始日期" value="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                </div>
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
</div>
