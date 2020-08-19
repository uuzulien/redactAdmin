@extends('layouts.app')

@section('breadcrumb')

@endsection

@section('content')
    <!-- 通知模板 -->
    @include('popup.notice.index')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel">
                    <div class="panel-heading mynav">
                        <h3 class="panel-title"><i class="fa fa-home" aria-hidden="true"></i>首页 &gt; 通知公告</h3>
                    </div>
                    <div class="panel-body"><form id="formlist">
                            <table class="table table-striped table-hover ">

                                <thead>
                                <tr>
                                    <th width="85%">标题</th>
                                    <th width="15%">日期</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>
                                            <label class="control-inline fancy-checkbox">
                                                <input type="checkbox" name="ids" id="id[]" value="{{$item->id}}"><span></span>
                                            </label>
                                            <span class="notice_{{$item->id}}">{{['未读','已读'][$item->checked]}}</span>
                                            <a data-toggle="modal" data-target="#mdl1597307464231" style="@if($item->color) color: {{$item->color}} @endif;font-weight:normal" data-key="{{$key}}">{{$item->title}}</a>
                                        </td>
                                        <td>{{$item->addtime}}</td>
                                    </tr>
                                @empty
                                    没有数据
                                @endforelse
                                <tr>
                                    <td><div class="form-group"><label class="control-inline fancy-checkbox" onclick="CheckAll(this.form)">
                                                <input type="checkbox" name="chkall" value="no"><span></span>全选
                                            </label> <a class="label label-success" href="javascript:readsubmit()">设为已读</a></div></td>
                                    <td></td>
                                </tr>

                                </tbody>

                            </table>
                        </form>
                        <div class="col-xs-12 col-md-10 col-sm-10">
                                <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >
                                    第 <input type="text" class="text-center form-control" style="width: 50px;display: inline-block" data-jump="{{$list->url(1)}}" value="{{ $list->currentPage() }}" id="customPage" data-total-page="{{ $list->lastPage() }}" > 页 / 本页 {{$list->count()}} 条数据
                                </span>
                            <span>共{{$list->total()}}条数据 </span>
                        </div>
                        <div class="page">{{$list->appends($app->request->all())->links()}}</div>
{{--                        <div>--}}
{{--                            <ul class="pagination pagination-sm" style="float:right;"><li class="first disabled"><span>首页</span></li>--}}
{{--                                <li class="prev disabled"><span>上一页</span></li>--}}
{{--                                <li class="active"><a href="https://novel.zhangdu520.com/notice/list?page=1" data-page="0">1</a></li>--}}
{{--                                <li><a href="https://novel.zhangdu520.com/notice/list?page=2" data-page="1">2</a></li>--}}
{{--                                <li><a href="https://novel.zhangdu520.com/notice/list?page=3" data-page="2">3</a></li>--}}
{{--                                <li><a href="https://novel.zhangdu520.com/notice/list?page=4" data-page="3">4</a></li>--}}
{{--                                <li><a href="https://novel.zhangdu520.com/notice/list?page=5" data-page="4">5</a></li>--}}
{{--                                <li class="next"><a href="https://novel.zhangdu520.com/notice/list?page=2" data-page="1">下一页</a></li>--}}
{{--                                <li class="last"><a href="https://novel.zhangdu520.com/notice/list?page=13" data-page="12">尾页</a></li></ul>--}}
{{--                            <span>共242条数据 共13页 20条/页</span>--}}

{{--                        </div>--}}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        var datas = @json($list)['data'];
        var user_id = {{Auth::id()}};

        var notice = @json($notice);

        if (notice){
            $('.modal-title').text(notice.title);
            $('.content p').html(`<strong>【${notice.nick_name}】：</strong>${notice.content}`);
            $('#mdl1597307464231').modal('show');
            updateChecked(notice.id);

        }


        $('#mdl1597307464231').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];

            var modal = $(this);

            modal.find('.modal-title').text(data.title);
            modal.find('.content p').html(`<strong>【${data.nick_name}】：</strong>${data.content}`);
            if (data.checked == 0 && user_id == data.user_id){
                updateChecked(data.id);
            }
        });

        function updateChecked(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "post",
                dataType: "json",
                url: '/notice_check/update?msgid='+id,
                success: function (res) {
                    $(".notice_"+id).html('已读');
                }
            });
        }
    </script>
@endsection
