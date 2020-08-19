@extends('layouts.app')

@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>角色</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="{{ url('auth/role_edit') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> 新增角色</a>
        </h2>
    </div>
@endsection

@inject('auth_validate', 'App\Service\AuthValidate')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>岗位名称</th>
                                <th>所属分类</th>
                                <th>级别level</th>
                                <th>权限描述</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $item)
                                <tr id="U{{ $item->id }}">
                                    <td>{{ $item->name }}</td>
                                    <td>{{$item->job_name}}</td>
                                    <td>{{['普通专员','组长','主管','超级管理员'][$item->is_admin]}}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ url('auth/role_permissions/'.$item->id) }}"  class="btn btn-sm btn-primary">
                                            <span class="glyphicon glyphicon-edit"></span> 路由权限分配
                                        </a>
                                        <a href="{{ url('auth/role_edit') }}?id={{$item->id}}"  class="btn btn-sm btn-info">
                                            <span class="glyphicon glyphicon-edit"></span> 编辑
                                        </a>
                                        @if($auth_validate->authRouterValidate('auth.delete_role'))
                                        <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteUser({{$item->id}})" >删除 </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                没有用户
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="float: right">
                    {{ $list->links() }}
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
@endsection

@section('js')
    <script>
        function deleteUser(id) {
            //询问框
            layer.confirm('确定要删除该角色吗？', {
                btn: ['确定', '取消'], //按钮
                area: ['320px', '186px'],
                skin: 'demo-class'
            }, function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "delete",
                    dataType: "json",
                    url: '/auth/delete_role/'+id,
                    success: function (res) {
                        console.log(res)
                        location.reload();
                    },
                    error(res){
                        console.log(res.responseJSON.msg)
                        layer.open({
                            title:false,
                            content:'<span>'+res.responseJSON.msg+'</span>',
                            btn:false,
                            time:3000,
                            closeBtn:0,
                        });
                    }
                });
            }, function () {

            });
        }
    </script>
@endsection
