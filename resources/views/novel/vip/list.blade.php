@extends('layouts.app')
@push('scripts')
    <!-- 选择框样式 -->
    <script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap-select.js?tk=1594870707812') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>小说vip管理列表</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <button class="btn btn-sm btn-primary refuse" data-toggle="modal" data-target="#addModal">
                <span class="glyphicon glyphicon-plus"></span> 新增VIP账号
            </button>
        </h2>

    </div>
@endsection

@section('content')
    <!-- 添加账号 -->
    @include('popup.account.vip.add')

    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form class="form-inline">
                            @include('layouts.common')

                            <div class="form-group">
                                <h5>公众号</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">微</span>
                                    <input type="text" class="form-control" name="pf_nick" value="{{request()->get('pf_nick')}}" placeholder="请输入公众号名称">
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <button type="submit" class="btn btn-default">搜索</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
                                    <th>小说平台</th>
                                    <th>VIP账户数</th>
                                    <th>子账户数</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item['platform_name']}}</td>
                                        <td>{{$item['count_num']}}</td>
                                        <td>{{$item['count_sub_num']}}</td>
                                        <td>
                                            <a type="button" href="{{route('vip.novel.manage', ['group' => request()->get('group'), 'pdr' => request()->get('pdr'), 'pid' => $item['pid']])}}" class="btn btn-sm btn-default">查看明细</a>
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
        {{--// 权限树--}}
        {{--var sel_datas = @json($groupTree);--}}
        {{--var sel_key = '{{request()->get('pdr')}}';--}}
        {{--var tree_content = `<option value="${sel_datas.key}" ${sel_key == sel_datas.key ? 'selected' : ''}>${sel_datas.name}</option>`;--}}

        {{--for (var i in sel_datas['datas']) {--}}
        {{--    tree_content += `<option value="${sel_datas['datas'][i].key}" ${sel_key == sel_datas['datas'][i].key ? 'selected' : ''}>┖──${sel_datas['datas'][i].name}</option>`;--}}
        {{--    for(var k = 0; k < sel_datas['datas'][i].datas.length; k++) {--}}
        {{--        tree_content += `<option value="${sel_datas['datas'][i].datas[k].key}" ${sel_key == sel_datas['datas'][i].datas[k].key ? 'selected' : ''}>┊╌╌┖──${sel_datas['datas'][i].datas[k].name}</option>`;--}}
        {{--    }--}}
        {{--}--}}
        {{--$('#pdr').html(tree_content);--}}

    </script>
@endsection
