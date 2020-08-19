@extends('layouts.app')
@push('scripts')
    <!-- 选择框样式 -->
    <script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap-select.js?tk=1594870707812') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins.js?tk=1594870707812') }}"></script>
@endpush

@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li><a href="{{ url('auth/permissions_list') }}">权限</a></li>

    @if(isset($data->id))
        <li>更新权限</li>
    @else
        <li>新增权限</li>
    @endif
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
            <a href="{{ url('auth/permissions_list') }}" class="btn btn-primary"><span
                        class="fa fa-arrow-circle-o-left"></span> 权限列表</a>
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

            <form class="form-horizontal" method="post" action="{{ url('auth/permissions_save') }}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            @if(isset($data->id))
                                更新权限
                                <input type="hidden" name="id" value="{{ $data->id }}">
                            @else
                                新增权限
                            @endif
                        </h3>
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">路由别名</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" name="name"
                                       value="{{ $data->name ?? old('route')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">是否为视图菜单</label>
                            <div class="col-md-6 col-xs-12">
                                <label class="switch">
                                    <input class="item-checkbox" type="checkbox" name="is_menu"
                                           value="1" @if(isset($data)&&$data->is_menu==1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">权限显示名称</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" name="display_name"
                                       value="{{ $data->display_name ?? old('category')  }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">所属分类</label>
                            <div class="col-md-6 col-xs-12">
                                <select id="city" class="select" name="pid">
                                    @foreach($permission_top as $permission)
                                        @if(!isset($data->id) || $permission['id'] != $data->id)
                                        <option value="{{$permission['id']}}"
                                                @if(isset($data)&&$data->pid==$permission['id']) selected @endif>{{$permission['display_name']}}</option>
                                        @endif
                                        @if(!empty($permission['children']))
                                            @foreach($permission['children'] as $permission_children)
                                                @if(!isset($data->id) || $permission_children['id'] != $data->id)
                                                <option value="{{$permission_children['id']}}"
                                                        @if(isset($data)&&$data->pid==$permission_children['id']) selected @endif>-- {{$permission_children['display_name']}}</option>
                                                    @endif

                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">权限描述</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" name="description"
                                       value="{{ $data->description ?? old('description') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">优先级</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" class="form-control" name="sort"
                                       value="{{ $data->sort ?? old('sort')  }}">
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
