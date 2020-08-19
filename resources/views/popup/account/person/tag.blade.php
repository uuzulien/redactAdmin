<div class="modal fade" id="addTagsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加个人号用途</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('tag.person.add')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input class="form-control" id="gid" name="group_id" type="hidden">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">用途:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" name="name" type="text" required>
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

