<div class="modal fade" id="allocateModal" tabindex="-1" role="dialog" aria-labelledby="trendModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">可批量分配的账号</h4></div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{route('wechat.account.transfer')}}">
                    {{ csrf_field() }}
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-body user-permission">
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-3">指定归属人:</label>
                                <div class="col-xs-12 col-sm-8">
                                    <select class="form-control" autocomplete="off" name="user_id">
                                        @foreach ($userTree as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <div class="permission-heading wxv" style="height:auto;" id="permission-heading_wxv">
                                    @foreach ($wechatInfoList as $item)
                                        <span>
                                            <input class="wx-check" name="wxv[]" type="checkbox" id="checkbox-{{$item['id']}}" value="{{$item['id']}}">
                                            <label style="padding-left:20px;line-height:25px;padding-right:10px;">{{$item['nick_name']}}</label>
                                        </span>
                                    @endforeach

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

