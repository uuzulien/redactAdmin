@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>账号权限授权</li>
    <style>
        .icon {
            width: 60px!important;
            height: 60px!important;
        }
        tr td{
            vertical-align:middle!important;
        }
        .qrcode:hover {
            transform: scale(3.5);
            transition: all 0.5s;
        }
        .user-permission .permission-heading {
            height: 40px;
            line-height: 40px;
            padding-left: 30px;
            border-bottom: 1px solid #e7e7eb;
            cursor: pointer;
        }
        .wxv{
            width: 500px;
        }
        .wxv span{
            display: inline-block;
        }
        .wx-check {
            width: 18px;
            height: 18px;
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            line-height: 18px;
            position: relative;
            margin-right: 10px;
        }

        .wx-check::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            background: #fff;
            width: 100%;
            height: 100%;
            border: 1px solid #d9d9d9
        }
        .wx-check:hover::before {
            content: "\2713";
            background-color: #fff;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            color: rgb(201,202,207);
            font-size: 12px;
            font-weight: bold;
        }
        .wx-check:checked::before {
            content: "\2713";
            background-color: #fff;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            color: rgb(92,144,202);
            font-size: 12px;
            font-weight: bold;
        }

    </style>
@endsection

@section('pageTitle')
    <div class="page-title">

    </div>
@endsection

@include('popup.empower.auth')

@section('content')
    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-inline">
                            <div class="form-group">
                                <h5>运营管理	</h5>
                                <select  class="form-control" name="pdr" id="pdr" style="width:300px;">
                                </select>
                            </div>


                            <div class="form-group">
                                <h5>&nbsp;</h5>
                                <button type="submit" class="btn btn-default">搜索</button>
                            </div>
                            <div class="form-group pull-right">
                                <h5>&nbsp;</h5>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#allocateModal">批量账号授权</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
                                    <th>用户名</th>
                                    <th>角色</th>
                                    <th>状态</th>
                                    <th>拥有权限</th>
                                    <th>注册时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->role_name}}</td>
                                        <td>{{['正常','冻结'][$item->freeze]}}</td>
                                        <td>{{$item->user_name}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            <span class="btn btn-sm btn-danger show-audit-information" onclick="deleteUser({{$item->id}})" >删除 </span>
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
    // 权限树
    var sel_datas = @json($groupTree);
    var sel_key = '{{request()->get('pdr')}}';
    var tree_content = `<option value="${sel_datas.key}" ${sel_key == sel_datas.key ? 'selected' : ''}>${sel_datas.name}</option>`;

    for (var i in sel_datas['datas']) {
        tree_content += `<option value="${sel_datas['datas'][i].key}" ${sel_key == sel_datas['datas'][i].key ? 'selected' : ''}>┖──${sel_datas['datas'][i].name}</option>`;
        for(var k = 0; k < sel_datas['datas'][i].datas.length; k++) {
            tree_content += `<option value="${sel_datas['datas'][i].datas[k].key}" ${sel_key == sel_datas['datas'][i].datas[k].key ? 'selected' : ''}>┊╌╌┖──${sel_datas['datas'][i].datas[k].name}</option>`;
        }
    }
    $('#pdr').html(tree_content);

    function deleteUser(id) {
        //询问框
        layer.confirm('确定要删除该用户吗？', {
            btn: ['确定', '取消'], //按钮
            offset: '200px',
            area: ['320px', '186px'],
            skin: 'demo-class'
        }, function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "delete",
                dataType: "json",
                url: '/empower/del_account/'+id,
                success: function (res) {
                    console.log(res);
                    location.reload();
                }
            });
        }, function () {

        });
    }
</script>

@endsection
