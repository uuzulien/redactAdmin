<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="trendModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">职位修改</h4></div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{route('group.auth.edit_save')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body user-permission">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">部门名称</label>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="text" class="form-control" id="group-name" name="name" value="">
                                        <input type="hidden" class="form-control" id="group-id" name="group_id" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">角色</label>
                                    <div class="col-md-6 col-xs-12" id="role-user">
                                        @foreach($roles as $val)
                                            <input type="checkbox" name="role_id[]" value="{{$val->id}}"> {{$val->name}}<br>
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

