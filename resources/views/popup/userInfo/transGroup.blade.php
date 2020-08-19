<div class="modal fade" id="transModal" tabindex="-1" role="dialog" aria-labelledby="trendModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="z-index: 19891015; width: 600px; height: 480px; top: 160px; left: 150px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">人员分配
                    <span style="padding-left: 20px;font-size: 15px;color: #3994C7;"></span></h4>
            </div>
            <div class="modal-body tab-content">
                <form class="form-horizontal" method="post" action="{{route('info_user.staff.edit')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-2">用户名:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <input class="form-control" id="username" type="text" readonly>
                                                <input class="form-control" id="cid" name="id" type="hidden">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-2">部门间分配:</label>
                                            <div class="col-xs-12 col-sm-8">
                                                <select class="form-control" autocomplete="off" id="group-id" name="gid">
                                                    @if($groups->count() > 1)
                                                    <option value="0" >请分配员工归属部门</option>
                                                    @endif
                                                    @foreach($groups as $item)
                                                        <option value="{{$item->id}}" >{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-xs-12 col-sm-2">角色:</label>
                                            <div class="col-xs-12 col-sm-8" id="role-id">
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
