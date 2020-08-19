@extends('layouts.app')
@section('activeUrl', url('auth/role_list'))

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>角色</li>

{{--    @if(isset($data->id))--}}
{{--        <li>更新角色</li>--}}
{{--    @else--}}
{{--        <li>新增角色</li>--}}
{{--    @endif--}}
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
    <div class="page-title">
        <h2>
            <a href="{{ url('auth.group_add') }}" class="btn btn-primary"><span class="fa fa-arrow-circle-o-left"></span> 角色列表</a>
        </h2>
    </div>
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" method="post" action="{{ url('auth/role_save') }}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
{{--                            @if(isset($data->id))--}}
{{--                                岗位调整--}}
{{--                                <input type="hidden" name="id" value="{{ $data->id }}">--}}
{{--                            @else--}}
{{--                                新增岗位--}}
{{--                            @endif--}}
                        </h3>
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">职位名称</label>
                            <div class="col-md-6 col-xs-12">
{{--                                <input type="text" class="form-control" name="name" value="{{ $data->name ?? old('name') }}">--}}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">描述</label>
                            <div class="col-md-6 col-xs-12">
{{--                                <input type="text" class="form-control" name="description" value="{{ $data->description ?? old('description') }}">--}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">管理员</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="radio" name="roles" value="1" >  是<br>
                                <input type="radio" name="roles" value="0" checked>  否<br>
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
