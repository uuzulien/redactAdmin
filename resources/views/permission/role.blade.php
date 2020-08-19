@extends('layouts.app')

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li><a href="{{ url('auth/permissions_list') }}">权限</a></li>

    <li>权限</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="{{ url('auth/permissions_list') }}" class="btn btn-primary"><span class="fa fa-arrow-circle-o-left"></span> 角色列表</a>
        </h2>
    </div>
@endsection

@section('content')
    {{--{{dd($data->role_permission)}}--}}
    <div class="row">
        <div class="col-md-12">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form class="form-horizontal" method="post" action="{{ url('auth/permissions_role_save') }}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            权限
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        </h3>
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">权限名</label>
                            <div class="col-md-6 col-xs-12">
                                <p class="form-control-static">{{ $data->display_name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">角色</label>
                            <div class="col-md-6 col-xs-12">
                                @foreach($roles as  $item)
                                        <div class="col-md-6">
                                            <label class="col-xs-6 control-label">{{ $item->name }}</label>

                                            <div class="col-xs-6">
                                                <label class="switch">
                                                    <input type="checkbox" name="permission_id[]"
                                                           value="{{ $item->id }}"
                                                            {{ in_array($item->id, $data->permission_role->pluck('role_id')->toArray())? 'checked' : '' }}
                                                    >
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    <div class="clearfix "></div>
                                @endforeach
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