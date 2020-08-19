@extends('layouts.app')
@push('scripts')
    <script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>用户管理</li>
@endsection

@section('pageTitle')
@endsection
@inject('auth_validate', 'App\Service\AuthValidate')
@section('content')
    <!-- 修改账号 -->
    @include('popup.userInfo.amend')
    <!-- 人员分配 -->
    @include('popup.userInfo.transGroup')
    <!-- 共享数据分配 -->
    @include('popup.userInfo.allocate')
    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form class="form-inline">
                            @include('layouts.common')

                            <div class="form-group">
                                <h5>用户名</h5>
                                <div class="input-group">
                                    <span class="add-on input-group-addon">微</span>
                                    <input type="text" class="form-control " autocomplete="off" name="name" value="{{request()->input('name')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <button type="submit" class="btn btn-default">搜索</button>
                            </div>
                            <div class="form-group pull-right">
                                <h5>&nbsp;</h5>
                                <a href="{{ url('admin_user/edit') }}">
                                    <button type="button" class=" btn-sm btn-success">新增用户</button>
                                </a>

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
                                    <th>登陆名</th>
                                    <th>主属部门</th>
                                    <th>岗位分类</th>
                                    <th>真实姓名</th>
                                    <th>注册时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{ $item->name ? $item->name : '佚名' }}</td>
                                        <td>{{$item->super_men}}</td>
                                        <td>{{ $item->userRole->name ?? '暂未分配'}}</td>
                                        <td>{{$item->real_name}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            @if(!empty($users->host))
                                            <a type="button" href="{{route('user.switch.sub_id',$item->id)}}" target="_blank" class="btn btn-sm btn-default" @if($users->id==$item->id)disabled @endif>
                                                进入子账户
                                            </a>
                                            @endif
                                            @if(Auth::user()->roles->first()->is_admin > 0)
                                                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#sharedata" data-key="{{$key}}" @if($item->userRole->is_admin > 0 || $item->role_id == 13)disabled @endif>
                                                共享数据
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#transModal" data-key="{{$key}}" @if($users->id==$item->id)disabled @endif>
                                                    <span class="glyphicon glyphicon-edit"></span> 岗位调整
                                                </button>
                                            @endif
                                            @if($auth_validate->authRouterValidate('admin_user.delete_user'))
                                                <span class="btn btn-sm btn-danger show-audit-information"  @if($users->id!=$item->id) onclick="deleteUser({{$item->id}})" @else disabled @endif >删除 </span>
                                            @endif
                                            @if($auth_validate->authRouterValidate('admin_user.change_status') && $users->id!=$item->id)
                                                @if($item->freeze==1)
                                                    <span class="btn btn-sm btn-success show-audit-information" onclick="changeUserStatus({{$item->id}},{{$item->freeze}})">解冻</span>
                                                @else
                                                    <span class="btn btn-sm btn-danger show-audit-information" onclick="changeUserStatus({{$item->id}},{{$item->freeze}})">冻结</span>
                                                @endif
                                            @endif
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#amendModal" data-key="{{$key}}">改密码</button>

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
                        {{ $list->appends($_GET)->links() }}
                    </div>
                </div>
                <!-- END DEFAULT DATATABLE -->
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var datas = @json($list)['data'];

        var role_tree = @json($roles);

    </script>
    <script>
        // 先分配部门属性
        $('#group-id').change(function () {
            changeGroupRole(role_tree);
        });

        // 修改密码
        $('#amendModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];

            var modal = $(this);
            modal.find('#username').val(data.name);
            modal.find('#cid').val(data.id);
        });

        // 人员分配
        $('#transModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var modal = $(this);

            modal.find('#username').val(data.name);
            modal.find('#cid').val(data.id);

            if($('#group-id option').length == 1){
                changeGroupRole(role_tree);
            }

        });
        // 数据共享
        $('#sharedata').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('key');
            var data = datas[recipient];
            var modal = $(this);

            var userArr = [];
            // 储存发文专员
            users.map(e => {
                if (e.role_id == 13){
                    userArr.push(e)
                }
            })

            if ($('#gid option:selected').val() > 0){
                changeUser(userArr, pdr ,modal.find("#uid"))
            }
            modal.find('#name').val(data.name);
            modal.find('#sub-id').val(data.id);

        });
        <!-- 改变分组权限 -->
        function changeGroupRole(datas) {
            var group_id = $("#group-id option:selected").val();
            var content = '';

            for (var key in datas) {
                var data = datas[key];
                if (group_id == data.gid){
                    content += `<input type="radio" name="roles" value="${data.id}">  ${data.name}<br>`;
                }
            }
            $('#role-id').html(content);
        }

        function deleteUser(id) {
            //询问框
            layer.confirm('确定要删除该用户吗？', {
                offset:'200px',
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
                    url: '/admin_user/delete_user/'+id,
                    success: function (res) {
                        layer.msg('操作成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    }
                });
            }, function () {

            });
        }
        function changeUserStatus(id,freeze) {
            //询问框
            if(freeze==0){
                var news='确定要冻结该用户吗？';
            }else {
                var news='确定要解冻该用户吗？';
            }
            layer.confirm(news, {
                offset:'200px',
                btn: ['确定', '取消'], //按钮
                area: ['320px', '186px'],
                skin: 'demo-class'
            }, function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "post",
                    dataType: "json",
                    url: '/admin_user/change_status/'+id,
                    success: function (res) {
                        layer.msg('操作成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    }
                });
            }, function () {

            });
        }
    </script>
@endsection
