@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>部门</li>

@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                <span class="glyphicon glyphicon-plus"></span> 新增部门
            </button>
        </h2>

    </div>
@endsection
<!-- 新建部门 -->
@include('popup.groups.add')
<!-- 部门角色修改 -->
@include('popup.groups.edit')

@section('content')
    <!-- 公众号分配 -->
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
                                    <th>部门名称</th>
                                    <th>内设职位</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item['name']}}</td>
                                        <td>
                                            @foreach($item['role_name'] as $role_name)
                                                -- {{$role_name}}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>{{$item['created_at']}}</td>
                                        <td>
                                            <span class="btn btn-sm btn-default" data-toggle="modal" data-target="#editModal" data-key="{{$key}}">编辑 </span>
                                            <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteUser({{$item['id']}})" >删除 </span>
                                        </td>
                                    </tr>
                                @empty
                                    没有数据
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <li class="disabled"><span>共{{$list->total()}}条记录</span></li>
                        <div class="page">{{$list->appends($app->request->all())->links()}}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var datas = @json($list)['data'];
        var roles = @json($roles);

        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];


            var modal = $(this);

            modal.find('#group-id').val(data.id);
            modal.find('#group-name').val(data.name);
            modal.find('#role-user input').prop('checked',false);

            for (var krol in data['role_name']) {
                var query = "input[type='checkbox'][value=" + krol + "]";
                modal.find(query).prop("checked",true);
            }

        });
    </script>

@endsection
