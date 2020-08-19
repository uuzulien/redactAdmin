@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>用户管理</li>

    <li>新增用户</li>

@endsection
@section('css')
    <style type="text/css">
        .panel-heading {
            height: 70px;
        }
        .panel-footer {
            height: 50px;
        }
    </style>
@endsection
@section('pageTitle')
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" method="post" action="{{route('admin_user.save')}}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            新增用户
                        </h3>
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">用户名</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" name="name" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">密码</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="password" class="form-control" name="password" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">真实姓名</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" name="real_name" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">所属部门</label>
                            <div class="col-md-6 col-xs-12">
                                <select id="group-id" class="form-control" name="gid" autocomplete="off">
                                    <option value="0" >请分配员工归属部门</option>
                                    @foreach($groups as $item)
                                        <option value="{{$item->id}}" >{{$item->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">角色</label>
                            <div class="col-md-6 col-xs-12" id="role-id">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-primary pull-right">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var datas = @json($roles);
        // 先分配部门属性
        $('#group-id').change(function () {
            var group_id = $("#group-id option:selected").val();
            var content = '';

            for (var key in datas) {
                data = datas[key];
                if (group_id == data.gid){
                    content += `<input type="radio" name="roles" value="${data.id}">  ${data.name}<br>`;
                }
            }
            $('#role-id').html(content);
        });
    </script>
@endsection
