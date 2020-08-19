@extends('layouts.app')
@section('breadcrumb')
    <li><a href="{{ route('home') }}">首页</a></li>
    <li><a href="{{ url('auth/role_list') }}">角色</a></li>

    <li>权限</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="{{ url('auth/role_list') }}" class="btn btn-primary"><span class="fa fa-arrow-circle-o-left"></span> 角色列表</a>
        </h2>
    </div>
@endsection

@section('content')
    {{--{{dd($data->role_permission)}}--}}
    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" method="post" action="{{ url('auth/save_role_permissions') }}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            权限
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        </h3>

                        <label class="switch">
                            <input class="all-checkbox" type="checkbox">
                            <span></span>
                        </label>
                    </div>
                    <div class="row"><h2 class="col-md-3 col-md-offset-4">角色名 {{ $data->name }}</h2></div>
                    <div class=" panel panel-info">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                                @foreach($permission as  $items)
                                    <div class="form-group panel panel-body">
                                    <div class="row">
                                        <label class="col-xs-1 control-label">{{ $items['display_name'] }} </label>
                                        <div class="col-xs-1">
                                        <label class="switch">
                                            <input class="item-checkbox" type="checkbox" name="permission_id[]"
                                                   value="{{ $items['id'] }}"
                                                    {{ in_array($items['id'], $data->role_permission->pluck('permission_id')->toArray())? 'checked' : '' }}
                                            >
                                            <span></span>
                                        </label>
                                        </div>
                                    </div>
                                            @if(isset($items['children']))
                                            @foreach($items['children'] as $item)
                                                    <label class="col-xs-2 control-label">{{ $item['display_name'] }} </label>
                                                    <div class="col-xs-1">
                                                        <label class="switch">
                                                            <input class="item-checkbox" type="checkbox" name="permission_id[]"
                                                                   value="{{ $item['id'] }}"
                                                                    {{ in_array($item['id'], $data->role_permission->pluck('permission_id')->toArray())? 'checked' : '' }}
                                                            >
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    @if(isset($item['children']))
                                                        @foreach($item['children'] as $item1)
                                                            <label class="col-xs-2 control-label">{{ $item1['display_name'] }} </label>
                                                            <div class="col-xs-1">
                                                                <label class="switch">
                                                                    <input class="item-checkbox" type="checkbox" name="permission_id[]"
                                                                           value="{{ $item1['id'] }}"
                                                                            {{ in_array($item1['id'], $data->role_permission->pluck('permission_id')->toArray())? 'checked' : '' }}
                                                                    >
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @endif


                                            @endforeach
                                        @endif

                                    <div class="clearfix "></div>
                                    </div>
                                @endforeach
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
        //全选
        $('.all-checkbox').click(function () {
            if ($(this).is(':checked')) {
                $(this).parent().parent().parent().find("[type='checkbox']").prop('checked', true);
            } else {
                $(this).parent().parent().parent().find("[type='checkbox']").prop('checked', false);
            }
        })
        //如果其中有一项不是选中状态则把全选按钮取消勾选
        //如果全部是勾选 则把全选按钮也勾选
        $('.item-checkbox').click(function () {
            var allChecked = true;
            $(this).parents(".form-group").find("[type='checkbox']").each(function () {
                if (!$(this).is(':checked')) {
                    $('.all-checkbox').prop('checked', false);
                    allChecked = false;
                }
            })
            if (allChecked) {
                $('.all-checkbox').prop('checked', true);
            }
        })
    </script>
@endsection