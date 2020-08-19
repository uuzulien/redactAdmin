<div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="trendModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="z-index: 19891015; width: 600px; height: 450px; top: 160px; left: 150px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">编辑投放渠道/是否投放中
                    <span style="padding-left: 20px;font-size: 15px;color: #3994C7;"></span></h4>
            </div>
            <div class="modal-body tab-content">
                <form class="form-horizontal" method="post" action="{{route('wechat.advert.info')}}">
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
                                            <label class="control-label col-xs-12 col-sm-3">投放渠道:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <select class="form-control" id="cost-id" name="cost_id">
                                                    <option value="0">无</option>
                                                    @foreach($advert as $key => $value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-3">投放中:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <select class="form-control" id="is-cost" name="is_cost">
                                                    <option value="0">未投放</option>
                                                    <option value="1">投放中</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-3">性别:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <select class="form-control" id="sex" name="sex">
                                                    <option value="0">全部</option>
                                                    <option value="1">男频</option>
                                                    <option value="2">女频</option>
                                                </select>
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
