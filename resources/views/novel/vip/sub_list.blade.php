@extends('layouts.app')
@push('scripts')
    <!-- 选择框样式 -->
    <script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap-select.js?tk=1594870707812') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>小说子账户vip管理</li>
@endsection

@section('pageTitle')

@endsection

@section('content')
    <!-- 关联账号 -->
    @include('popup.account.vip.related')
    <!-- 修改账号 -->
    @include('popup.account.vip.amend')
    {{--    <!-- 添加账号 -->--}}
    {{--    @include('popup.account.vip.add')--}}


    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">

                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default">


                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>管理账户</th>
                                    @if(request()->input('pid') == 3)
                                        <th>来源</th>
                                    @endif
                                    <th>子账户数</th>
                                    <th>运营专员</th>
                                    <th>更新时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item['account']}}</td>
                                        @if(request()->input('pid') == 3)
                                            <td>{{$item['origin']}}</td>
                                        @endif
                                        <td>{{$item['count_num']}}</td>
                                        <td>{{$item['user_name']}}</td>
                                        <td>{{$item['updated_at']}}</td>
                                        <td>
                                            <a type="button" href="{{route('sub.novel.config', ['group' => request()->get('group'), 'pdr' => request()->get('pdr'), 'upid' => $item['id']])}}" class="btn btn-sm btn-default">查看明细</a>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#relatedModal" data-key="{{$key}}">关联</button>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#amendModal" data-key="{{$key}}">修改</button>
                                        </td>
                                    </tr>
                                @empty
                                    没有数据
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="col-xs-12 col-md-10 col-sm-10">
                            <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >
                                第 <input type="text" class="text-center form-control" style="width: 50px;display: inline-block" data-jump="{{$list->url(1)}}" value="{{ $list->currentPage() }}" id="customPage" data-total-page="{{ $list->lastPage() }}" > 页 / 本页 {{$list->count()}} 条数据
                            </span>
                            <span>共{{$list->total()}}条数据 </span>
                        </div>
                        <div class="page">{{$list->appends($app->request->all())->links()}}</div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // 快速跳转
        $("#customPage").keydown(function(e){
            if(e.keyCode == 13){
                var jump = $("#customPage").attr('data-jump');
                jump = jump.substring(0, jump.length-1);
                var page = $('#customPage').val();
                location.href= jump + page;
            }
        });

        $(document).ready(function(){
            $('.selectpicker').selectpicker({
                'selectedText': 'cat',
                'selectedTextFormat':'count',
                'noneSelectedText':'未选择',
                'countSelectedText':'当前已勾选{0}，共{1}',
                'showSubtext': true,
            });
        });
        var datas = @json($list)['data'];
        var user_id = '{{Auth::id()}}';

        // 编辑逻辑
        $('#relatedModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var modal = $(this);

            modal.find('#account').val(data.account);
            modal.find('#cid').val(data.id);

            $(".selectpicker").val('');
            $('.selectpicker').selectpicker('refresh');

            $.get('/api/sub/account/',{'pid':data.pid, 'user_id':user_id}, function (e) {
                var res = e.data;

                var content = '';
                for (var key in res) {
                    content += `<option value="${res[key]['id']}">${res[key]['account']}</option>`;
                }
                $('#subinfo').html(content);

                $('.selectpicker').selectpicker('refresh');

                // $(".selectpicker").val(["715","723"]);

                $('.selectpicker').selectpicker('render');

            })
        });

        // 修改密码
        $('#amendModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var modal = $(this);

            modal.find('#account').val(data.account);
            modal.find('#cid').val(data.id);
            modal.find('#passwd').val(data.password);
            modal.find('#origin').val(data.origin);
        });
    </script>
@endsection
