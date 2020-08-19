<!-- 文本消息，插入链接 -->
{{--<div id="mdl1589339055735" class="modal fade in" role="dialog" aria-labelledby="modalLabel" data-backdrop="static">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <button type="button" class="close" data-dismiss="modal">--}}
{{--                    <span aria-hidden="true">×</span>--}}
{{--                    <span class="sr-only">Close</span></button>--}}
{{--                <h4 class="modal-title" id="modalLabel">编辑标题/链接</h4></div>--}}
{{--            <div class="modal-body">--}}
{{--                <div class="form-group  input-group" style="margin:0 0 5px 0">--}}
{{--                    <input type="text" maxlength="5000" class="form-control" id="temp_title" placeholder="标题" value="" style="width:467px;"></div>--}}
{{--                <div style="font-size: 14px;color: #ccc;margin-bottom: 10px;">80字内，输入表情时，请使用微信自带的表情</div>--}}
{{--                <div class="form-group  input-group" style="margin:0 0 10px 0">--}}
{{--                    <input type="text" class="form-control" id="temp_link" placeholder="链接地址" value="">--}}
{{--                    <span class="input-group-btn">--}}
{{--            <button type="button" id="get-link-submit" class="btn btn-success " data-toggle="modal" data-target="#mdl1526557124088">选择链接</button></span>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>--}}
{{--                <button type="button" class="btn btn-success ok" data-dismiss="modal">确定</button></div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- 图文消息，标题编辑 -->
<div id="mdl1589339055735" class="modal fade in" role="dialog" aria-labelledby="modalLabel" style="padding-right: 17px;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">编辑标题/链接</h4></div>
            <div class="modal-body">
                <div class="form-group  input-group" style="margin:0 0 10px 0">
                    <input type="text" class="form-control" id="img_text_title" placeholder="标题" value="请珍惜那些劝你早睡的人吧，他们连自己都管不住还要管你">
                    <span class="input-group-btn">
            <button type="button" id="get-title-submit" class="btn btn-success" data-toggle="modal" data-target="#mdl1526557124086">选择标题</button></span>
                </div>
                <div class="form-group  input-group" style="margin:0 0 10px 0">
                    <input type="text" class="form-control temp_link" id="temp_link" placeholder="链接地址">
                    <span class="input-group-btn">
            <button type="button" id="get-link-submit" class="btn btn-success " data-toggle="modal" data-target="#mdl1526557124088">选择链接</button></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-success ok" data-dismiss="modal">确定</button></div>
        </div>
    </div>
</div>