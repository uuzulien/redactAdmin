<!-- 图文消息，图片编辑 -->
<div id="mdl152755712400" class="modal fade in" role="dialog" aria-labelledby="modalLabel" style="z-index: 2000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalLabel">选择图片</h4></div>
            <div class="modal-body">
                <div class="select-cover-box">
                    <ul>
                        @forelse($image as $val)
                            <li data-index="{{$val->id}}">
                                <img src="{{$val->img_href}}"></li>
                        @empty
                            请先添加活动链接
                        @endforelse


                    </ul>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>