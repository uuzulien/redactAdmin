<!-- 图文消息，标题编辑 -->
<div id="mdl1589359911750" class="modal fade in" role="dialog" aria-labelledby="modalLabel" style="padding-right: 17px;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">编辑标题/链接</h4></div>
            <div class="modal-body">
                <div class="form-group  input-group" style="margin:0 0 10px 0">
                    <input type="text" class="form-control img_text_title" id="img_text_title" placeholder="标题" value="" readonly>
                    <span class="input-group-btn">
            <button type="button" id="get-title-submit" class="btn btn-success" data-toggle="modal" data-target="#mdl1526557124086">选择标题</button></span>
                </div>
                <div class="form-group  input-group" style="margin:0 0 10px 0">
                    <input type="text" class="form-control temp_link" id="temp_link" data-bookid="" placeholder="链接地址" @if(!in_array($wid, [423,438,472,516,517,518,590,610,611,635,636,637,695,459,200,185,166,199,206,170,8,204,191,203,21,416,320,319,368,412,411,309,283,232,235,308,180,224,153,151,311,150,152,228,310,410,369,364])) readonly @endif>
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
