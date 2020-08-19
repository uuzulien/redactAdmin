<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">添加小说数据</h4>
            </div>
            <form class="form-horizontal" method="post" action="{{route('wechat.novel.add')}}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">平台:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="pid" name="data[pid]">
                                <option value="1">掌读</option>
                                <option value="2">阳光</option>
                                <option value="3">阅文</option>
                                <option value="4">腾文</option>
                                <option value="5">掌中云</option>
                                <option value="6">掌阅</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group typeid">
                        <label class="control-label col-xs-12 col-sm-2">分类:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="typeid" name="data[typeid]">
                                <option value="1">都市异能</option>
                                <option value="2">玄幻仙侠</option>
                                <option value="3">社会风云</option>
                                <option value="4">架空历史</option>
                                <option value="5">游戏科幻</option>
                                <option value="6">都市言情</option>
                                <option value="7">古代言情</option>
                                <option value="8">青春纯爱</option>
                                <option value="9">出版物</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">性别频度:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select class="form-control" autocomplete="off" id="sex" name="data[sex]">
                                <option value="1">男频</option>
                                <option value="2">女频</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">书名:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="title" name="data[name]" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">书号:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="title" name="data[number]" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">字数:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="title" name="data[word_count]" type="number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">热度:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input class="form-control" id="title" name="data[hot]" type="text" required>
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

