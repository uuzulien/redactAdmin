@extends('layouts.app')
<meta name="referrer" content="never">
<style>
    .icon {
        width: 60px!important;
        height: 60px!important;
    }
    .plupload-preview {
        padding: 0 10px;
        margin-bottom: 0;
    }
    .list-inline > li {
        display: inline-block;
        padding-left: 5px;
        padding-right: 5px;
    }
    .plupload-preview li {
        margin-top: 15px;
    }
    .plupload-preview a:first-child {
        height: 90px;
        width: 90px;
    }
    .plupload-preview .thumbnail {
        margin-bottom: 10px;
    }
    .plupload-preview a {
        display: block;
        width: 90px;
    }
    .plupload-preview a img {
        height: 80px;
        object-fit: cover;
    }
    .plupload-preview a {
        display: block;
    }
    .btn {
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 1px solid transparent;
    }
    .icon:hover {
        transform: scale(3.5);
        transition: all 0.5s;
    }
</style>

@section('breadcrumb')
    <link rel="stylesheet" type="text/css" href="https://www.layuicdn.com/layui/css/layui.css" />
    <li><a href="{{ route('home') }}">首页</a></li>
    <li>素材-图片</li>
@endsection

@section('pageTitle')
    <div class="page-title">
        <h2>
            <a href="javascript:void(0);" class="btn btn-sm btn-danger plupload btn_upload_img">
                <i class="fa fa-upload"></i>图片上传
            </a>
        </h2>

    </div>
@endsection

@section('content')

    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">

                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- Nav tabs -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>图片ID</th>
                                    <th>图片</th>
                                    <th>录入人员</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key => $item)
                                    <tr>
                                        <td>{{$item->img_num}}</td>
                                        <td style="width: 100px;"><img class="img-responsive icon"  src="{{$item->img_href}}?ext=1591259740" onerror="this.src='https://tool.fastadmin.net/icon/'+'/uploads/20200508/dc8e079d29fdb70605364afef4e1659d.jpg'.split('.').pop()+'.png';this.onerror=null;"></td>
                                        <td>{{$item->user_name}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger deleteRes" data-id="{{$item->id}}">删除</button>
                                        </td>
                                    </tr>
                                @empty
                                    没有数据
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="col-xs-12 col-md-10 col-sm-10">
                            <span data-toggle="tooltip" data-placement="bottom" title="输入页码，按回车快速跳转" >
                                第 <input type="text" class="text-center form-control" style="width: 50px;display: inline-block" data-jump="{{$list->url(1)}}" value="{{ $list->currentPage() }}" id="customPage" data-total-page="{{ $list->lastPage() }}" > 页 / 本页 {{$list->count()}} 条数据
                            </span>
                            <span>共{{$list->total()}}条数据 </span>
                        </div>
                        <div class="page">{{$list->appends($app->request->all())->links()}}</div>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="https://www.layuicdn.com/layui/layui.js" charset="utf-8"></script>
    <!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
    <script type="text/javascript">
        layui.use('upload', function(){
            var upload = layui.upload;
            //普通图片上传
            upload.render({
                elem: '.btn_upload_img'
                ,headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
                // ,exts: 'jpg|png|gif' //设置一些后缀，用于演示前端验证和后端的验证
                //,auto:false //选择图片后是否直接上传
                ,accept:'images' //上传文件类型
                ,url: '/wechat/uploads'
                ,done: function(res){
                    //如果上传失败
                    if(res.code == 1){
                        layer.msg('上传成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    }
                }
                ,error: function(){
                    //演示失败状态，并实现重传
                    return layer.msg('上传失败,请检查图片大小或规格', {
                        offset:'200px',
                        icon: 1,
                        time: 3000 , //2秒关闭（如果不配置，默认是3秒）,
                    });
                }
            });
        });

        $('.deleteRes').click(function () {
            var id = $(this).data('id');

            layer.confirm('是否确定要删除该素材图片？', {
                btn: ['确认','取消'],
                offset: '200px',
                skin: 'demo-class'
            }, function(){
                this.disabled = true;
                $.ajax({
                    url: "/material/delete/img/list/" + id,
                    type: 'delete',
                    success:function()
                    {
                        layer.msg('操作成功', {
                            offset:'200px',
                            icon: 1,
                            time: 1000 , //2秒关闭（如果不配置，默认是3秒）,
                        }, function(){
                            location.reload();
                        });
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        var err = eval("(" + XMLHttpRequest.responseText + ")");
                        layer.alert(err.message, function() {
                            location.reload();
                        })
                    }
                });
                return false;
            }, function(){

            });
            return false;
        });

    </script>

@endsection
