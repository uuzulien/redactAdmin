@extends('layouts.app')
@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>用户</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="{{ url('auth/permissions_edit') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> 新增权限</a>
        </h2>
    </div>
@endsection

@inject('auth_validate', 'App\Service\AuthValidate')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline">
                        <div class="form-group">
                            <label>权限分类:
                                <select id="permission-select">
                                    <option value="0">-全部-</option>
                                    @foreach($permission as $value)
                                        <option value="{{$value['id']}}" id="select_permission_id_{{$value['id']}}">{{$value['display_name']}}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>/
                                <select name="pid" id="permission-select-children">
                                    <option value="0">-全部-</option>
                                </select>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-default">搜索</button>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>路由别名</th>
                                <th>权限名</th>
                                <th>创建时间</th>
                                <th>所属分类</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $item)
                                <tr id="U{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->display_name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>
                                        <a href="{{ url('auth/permissions_edit') }}?id={{$item->id}}" class="btn btn-sm btn-info">
                                            <span class="glyphicon glyphicon-edit"></span> 编辑
                                        </a>
                                        <a href="{{ url('auth/permissions_role') }}?id={{$item->id}}" class="btn btn-sm btn-primary">
                                            <span class="glyphicon glyphicon-edit"></span> 把权限添加到角色
                                        </a>
                                        @if($auth_validate->authRouterValidate('auth.delete_permissions'))
                                        <a href="javascript:" class="btn btn-sm btn-danger mb-control"
                                           onclick="deletePermission({{ $item->id }})"
                                        >
                                            <span class="glyphicon glyphicon-edit"></span> 删除
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                没有权限
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="float: right">
                    {{ $list->appends($_GET)->links() }}
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
    </div>

@endsection

@section('js')
    <script>
        function deletePermission(id) {
            //询问框
            layer.confirm('确定要删除该权限吗？', {
                btn: ['确定', '取消'], //按钮
                offset:'200px',
                area: ['320px', '186px'],
                skin: 'demo-class'
            }, function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "delete",
                    dataType: "json",
                    url: '/auth/delete_permissions/' + id,
                    success: function (res) {
                        layer.msg('操作成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    },
                    error(res) {
                        console.log(res.responseJSON.msg)
                        layer.open({
                            title: false,
                            content: '<span>' + res.responseJSON.msg + '</span>',
                            btn: false,
                            time: 3000,
                            closeBtn: 0,
                        });
                    }
                });
            });
        }
        var permission = {!! json_encode($permission) !!};
        console.log(permission);
        function defaultSelect(pid) {
          let parent_content = {id: 0};
          for (let i = 0; i < permission.length; i++) {
            if (permission[i].id == pid) {
              parent_content = permission[i]
              $("#select_permission_id_"+parent_content.id).attr("selected",true)
            }
            if(permission[i].children){
              for (let j = 0; j < permission[i].children.length; j++) {
                if (permission[i].children[j].id == pid) {
                  parent_content = permission[i]
                  $("#select_permission_id_"+parent_content.id).attr("selected",true)
                }
              }
            }
          }
          console.log(parent_content);
          let optionHtml = `<option value="${parent_content.id}">-全部-</option>`;
          if (parent_content.children) {
            for (let i = 0; i < parent_content.children.length; i++) {
              optionHtml += `<option value="${parent_content.children[i].id}" ${parent_content.children[i].id==pid?'selected':''} >${parent_content.children[i].display_name}</option>`;
            }
          }
          $("#permission-select-children").html(optionHtml);
        }

        $(document).ready(function() {
          $("#permission-select").change(function () {
            let pater_id = $(this).val()
            defaultSelect(pater_id)
          });
          defaultSelect({{request('pid')}})
        })
    </script>
@endsection