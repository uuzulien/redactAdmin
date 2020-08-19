function object(o) {
    var F = function () {};
    F.prototype = o;
    return new F();
}

function Gallery(params)
{
    this.inModal = true;
    if (params) {
        this.uploadURL = params.uploadURL;
        this.fetchURL = params.fetchURL;
        this.selectCallback = params.selectCallback;
        this.staticURL = params.staticURL;
        this.syncURL = params.syncURL;
        this.removeURL = params.removeURL;
        this.needSync = params.needSync;
        this.syncCheckURL = params.syncCheckURL;
        this.inModal = params.inModal !== undefined ? params.inModal : this.inModal;
        this.$renderTarget = params.renderTarget;
        this.pageSize = params.pageSize || 10;
        this.pageSize = 10;
        this.page = 1;
    }
}
Gallery.prototype = {
    syncIcon: '<img width="16px" height="16px" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDQ1OSA0NTkiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ1OSA0NTk7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0ic3luYy1wcm9ibGVtIj4KCQk8cGF0aCBkPSJNMCwyMjkuNWMwLDU2LjEsMjIuOTUsMTA3LjEsNjEuMiwxNDIuOEwwLDQzMy41aDE1M3YtMTUzbC01Ni4xLDU2LjFDNjguODUsMzExLjEsNTEsMjcyLjg1LDUxLDIyOS41ICAgIGMwLTY2LjMsNDMuMzUtMTIyLjQsMTAyLTE0NS4zNXYtNTFDNjYuMyw1Ni4xLDAsMTM1LjE1LDAsMjI5LjV6IE0yMDQsMzU3aDUxdi01MWgtNTFWMzU3eiBNNDU5LDI1LjVIMzA2djE1M2w1Ni4xLTU2LjEgICAgYzI4LjA1MSwyNS41LDQ1LjksNjMuNzUsNDUuOSwxMDcuMWMwLDY2LjMtNDMuMzUsMTIyLjQtMTAyLDE0NS4zNVY0MjguNGM4Ni43LTIyLjk1LDE1My0xMDIsMTUzLTE5Ni4zNTEgICAgYzAtNTYuMS0yMi45NS0xMDcuMS02MS4yLTE0Mi44TDQ1OSwyNS41eiBNMjA0LDI1NWg1MVYxMDJoLTUxVjI1NXoiIGZpbGw9IiNGRkZGRkYiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />',
    removeIcon: '<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDc3NC4yNjYgNzc0LjI2NiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNzc0LjI2NiA3NzQuMjY2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTY0MC4zNSw5MS4xNjlINTM2Ljk3MVYyMy45OTFDNTM2Ljk3MSwxMC40NjksNTI2LjA2NCwwLDUxMi41NDMsMGMtMS4zMTIsMC0yLjE4NywwLjQzOC0yLjYxNCwwLjg3NSAgICBDNTA5LjQ5MSwwLjQzOCw1MDguNjE2LDAsNTA4LjE3OSwwSDI2NS4yMTJoLTEuNzRoLTEuNzVjLTEzLjUyMSwwLTIzLjk5LDEwLjQ2OS0yMy45OSwyMy45OTF2NjcuMTc5SDEzMy45MTYgICAgYy0yOS42NjcsMC01Mi43ODMsMjMuMTE2LTUyLjc4Myw1Mi43ODN2MzguMzg3djQ3Ljk4MWg0NS44MDN2NDkxLjZjMCwyOS42NjgsMjIuNjc5LDUyLjM0Niw1Mi4zNDYsNTIuMzQ2aDQxNS43MDMgICAgYzI5LjY2NywwLDUyLjc4Mi0yMi42NzgsNTIuNzgyLTUyLjM0NnYtNDkxLjZoNDUuMzY2di00Ny45ODF2LTM4LjM4N0M2OTMuMTMzLDExNC4yODYsNjcwLjAwOCw5MS4xNjksNjQwLjM1LDkxLjE2OXogICAgIE0yODUuNzEzLDQ3Ljk4MWgyMDIuODR2NDMuMTg4aC0yMDIuODRWNDcuOTgxeiBNNTk5LjM0OSw3MjEuOTIyYzAsMy4wNjEtMS4zMTIsNC4zNjMtNC4zNjQsNC4zNjNIMTc5LjI4MiAgICBjLTMuMDUyLDAtNC4zNjQtMS4zMDMtNC4zNjQtNC4zNjNWMjMwLjMyaDQyNC40MzFWNzIxLjkyMnogTTY0NC43MTUsMTgyLjMzOUgxMjkuNTUxdi0zOC4zODdjMC0zLjA1MywxLjMxMi00LjgwMiw0LjM2NC00LjgwMiAgICBINjQwLjM1YzMuMDUzLDAsNC4zNjUsMS43NDksNC4zNjUsNC44MDJWMTgyLjMzOXoiIGZpbGw9IiM3NTc1NzUiLz4KCQk8cmVjdCB4PSI0NzUuMDMxIiB5PSIyODYuNTkzIiB3aWR0aD0iNDguNDE4IiBoZWlnaHQ9IjM5Ni45NDIiIGZpbGw9IiM3NTc1NzUiLz4KCQk8cmVjdCB4PSIzNjMuMzYxIiB5PSIyODYuNTkzIiB3aWR0aD0iNDguNDE4IiBoZWlnaHQ9IjM5Ni45NDIiIGZpbGw9IiM3NTc1NzUiLz4KCQk8cmVjdCB4PSIyNTEuNjkiIHk9IjI4Ni41OTMiIHdpZHRoPSI0OC40MTgiIGhlaWdodD0iMzk2Ljk0MiIgZmlsbD0iIzc1NzU3NSIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />',
    editIcon: '<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMS4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ2OS4zMzEgNDY5LjMzMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDY5LjMzMSA0NjkuMzMxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnPgoJPHBhdGggZD0iTTQzOC45MzEsMzAuNDAzYy00MC40LTQwLjUtMTA2LjEtNDAuNS0xNDYuNSwwbC0yNjguNiwyNjguNWMtMi4xLDIuMS0zLjQsNC44LTMuOCw3LjdsLTE5LjksMTQ3LjQgICBjLTAuNiw0LjIsMC45LDguNCwzLjgsMTEuM2MyLjUsMi41LDYsNCw5LjUsNGMwLjYsMCwxLjIsMCwxLjgtMC4xbDg4LjgtMTJjNy40LTEsMTIuNi03LjgsMTEuNi0xNS4yYy0xLTcuNC03LjgtMTIuNi0xNS4yLTExLjYgICBsLTcxLjIsOS42bDEzLjktMTAyLjhsMTA4LjIsMTA4LjJjMi41LDIuNSw2LDQsOS41LDRzNy0xLjQsOS41LTRsMjY4LjYtMjY4LjVjMTkuNi0xOS42LDMwLjQtNDUuNiwzMC40LTczLjMgICBTNDU4LjUzMSw0OS45MDMsNDM4LjkzMSwzMC40MDN6IE0yOTcuNjMxLDYzLjQwM2w0NS4xLDQ1LjFsLTI0NS4xLDI0NS4xbC00NS4xLTQ1LjFMMjk3LjYzMSw2My40MDN6IE0xNjAuOTMxLDQxNi44MDNsLTQ0LjEtNDQuMSAgIGwyNDUuMS0yNDUuMWw0NC4xLDQ0LjFMMTYwLjkzMSw0MTYuODAzeiBNNDI0LjgzMSwxNTIuNDAzbC0xMDcuOS0xMDcuOWMxMy43LTExLjMsMzAuOC0xNy41LDQ4LjgtMTcuNWMyMC41LDAsMzkuNyw4LDU0LjIsMjIuNCAgIHMyMi40LDMzLjcsMjIuNCw1NC4yQzQ0Mi4zMzEsMTIxLjcwMyw0MzYuMTMxLDEzOC43MDMsNDI0LjgzMSwxNTIuNDAzeiIgZmlsbD0iIzZiNmI2YiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />',
    open: function() {
        this.selected = 0;
        this.offset = 0;
        this.render();
    },
    fetch: function() {
        var me = this;
        $.get(this.fetchURL, {
            "type":this.type,
            "page":this.page,
            "count":this.pageSize
        }, function(data) {
            me.files = data.files || [];
            me.page = 1;
            me.scrollTop = 0;
            me.pager();
        }, 'json');
    },
    sync: function() {
        var $target = this.$renderTarget ? this.$renderTarget : $('body');
        var me = this;
        var $button = $target.find('.gallery-sync-button');
        var buttonText = $button.text();
        $button.text('同步中..');
        $.get(this.syncURL, {
            "type":this.type
        }, function(data) {
            $button.text(buttonText);
            $target.find('.gallery-content').html('<div style="line-height: 400px; text-align: center;">正在加载素材库...</div>');
            me.fetch();
        }, 'json');
    },
    render: function() {
        $('#galleryModal').remove();
        var main = [
            '<div class="upload-form" style="display:none">' + this.uploadForm() + '</div>',
            '<div class="gallery-content" style="margin: 10px 0; min-height: 350px;"><div style="line-height: 400px; text-align: center;">正在加载素材库...</div></div>',
            '<div class="gallery-page-navigator" style="height: 38px">',
            '<button type="button" class="btn btn-default pull-right gallery-page-navigator-button" data-type="next" style="margin-right: 27px" disabled="disabled">下一页</button>',
            '<button type="button" class="btn btn-default pull-right gallery-page-navigator-button" data-type="previous" style="margin-right: 20px" disabled="disabled">上一页</button>',
            '</div>',
        ].join('');
        var dflag = "";
        if (this.uploadLabel == "")
        {
            dflag = "display:none";
        }
        var topButtons = [
            '<button type="button" class="btn btn-default pull-right gallery-upload-button" style="margin-right: 20px;'+dflag+'" >' + this.uploadLabel + '</button>',
            '<button type="button" class="btn btn-default pull-right gallery-sync-button" style="margin-right: 20px">' + this.syncLabel + '</button></h4>',
        ].join('');
        var dom = [
            '<div class="modal fade" id="galleryModal">',
            '<div class="modal-dialog modal-lg">',
            '<div class="modal-content">',
            '<div class="modal-header">',
            '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>',
            '<h4 class="modal-title" style="line-height:33px">' + this.title,
            topButtons,
            '</div>',
            '<div class="modal-body">',
            main,
            '<div class="gallery-page-footer" style="height: 38px; background: #fafafa; border-top: 1px solid #EEE; padding: 10px; margin: 10px -15px 0 -15px;">',
            '<button type="button" class="btn btn-default pull-right gallery-cancel" style="margin-right: 31px">取消</button>',
            '<button type="button" class="btn btn-primary pull-right gallery-confirm" style="margin-right: 20px" disabled="disabled">确定</button>',
            '</div>',
            '</div>',
            '</div>',
            '</div>',
            '</div>'
        ].join('');
        if (this.inModal) {
            $('body').append(dom);
            $('#galleryModal').modal();
        } else {
            this.$renderTarget.html([
                '<div style="height: 56px; padding-top: 20px;">',
                topButtons,
                '</div>',
                main
            ].join(''));
        }
        this.bindEvents();
        this.fetch();
    },
    bindEvents: function() {
        var $target = this.$renderTarget ? this.$renderTarget : $('body');
        var me = this;
        this.bindUploadEvent();
        $target.find('.gallery-cancel').click(function(){
            $('#galleryModal').modal('hide');
        });
        $target.find('.gallery-confirm').click(function(){
            me.confirm();
            $('#galleryModal').modal('hide');
        });
        $target.find('#galleryModal').on('hidden.bs.modal', function() {
            $('#galleryModal').remove();
            me.release && me.release();
        });
        $target.find('.gallery-page-navigator-button').click(function(){
            var type = $(this).data('type');
            me.pager(type);
        });
        // sync
        $target.find('.gallery-sync-button').click(function(){
            me.sync();
        });
    },
    bindItemEvents: function() {
        var $target = this.$renderTarget ? this.$renderTarget.find('.gallery-content') : $('.gallery-content');
        var me = this;
        $target.find('.file-item').click(function(){
            var id = $(this).data('file-id');
            me.selectHandler(id);
        });
        $target.find('.file-item-remove').click(function(){
            var id = $(this).parent().parent().data('file-id');
            me.removeHandler(id);
        });
    },
    pager: function(type) {
        if (type == 'next') {
            this.page++;
            this.selectHandler();
        }
        else if (type == 'previous') {
            this.page--;
            this.selectHandler();
        }
        var $target = this.$renderTarget ? this.$renderTarget : $('body');
        $target.find('.gallery-page-navigator-button[data-type="previous"]').prop('disabled', this.page === 1);

        $target.find('.gallery-page-navigator-button[data-type="next"]').prop('disabled', true);
        $target.find('.gallery-page-navigator-button[data-type="next"]').text('加载中..');

        var me = this;
        $.get(this.fetchURL, {
            "type":this.type,
            "page":this.page,
            "count":this.pageSize
        }, function(data) {
            $target.find('.gallery-page-navigator-button[data-type="next"]').prop('disabled', false);
            $target.find('.gallery-page-navigator-button[data-type="next"]').text('下一页');
            if (!data.files) {
                //alert('没有更多了');
            } else {
                me.files = data.files || [];
                me.scrollTop = 0;
                me.renderItems();
                me.bindItemEvents();
            }
        }, 'json');
    },
    selectHandler: function(id) {
        // 素材管理里面禁用选择
        if (!this.inModal) {
            return;
        }
        // 记录滚动位置
        this.scrollTop = $('.gallery-news-wrap').scrollTop();
        if (id) {
            this.selected = id;
            this.pager();
        }
        else {
            this.selected = 0;
        }
        $('.gallery-confirm').prop('disabled', !this.selected);
    },
    removeHandler: function(id) {
        if (!confirm('确定删除？')) {
            return;
        }
        if (id) {
            var that = this;
            $.post(this.removeURL, {
                "file_id": id
            }, function(data){
                that.fetch();
            });
        }
        $('.gallery-confirm').prop('disabled', !this.selected);
    },
    confirm: function() {
        var data = {};
        for (var i = 0; i < this.files.length; i++) {
            if (this.files[i].id == this.selected) {
                data = $.extend(data, this.files[i]);
            }
        }

        // 检查素材是否同步成功
        if (this.needSync && this.syncCheckURL) {
            $.get(this.syncCheckURL, {file_id: data.id}, function(data){
                if (data.error_code != 0) {
                    alert('素材同步失败，可能无法使用');
                }
            }, 'json');
        }

        this.selectCallback && this.selectCallback(data);
    }
}

function ImageGallery(params)
{
    Gallery.call(this, params);
    this.title = '选择图片';
    this.uploadLabel = '上传图片';
    this.syncLabel = '同步图片';
    this.type = 'image';
}
ImageGallery.prototype = object(Gallery.prototype);
ImageGallery.prototype.uploadForm = function() {
    return [
        '<form enctype="multipart/form-data" action="' + this.uploadURL + '" id="image-gallery-upload-form" method="POST">',
        '<input type="file" name="photo" id="image-gallery-upload-file">',
        '<input name="doSubmit" type="submit" value="上传">',
        '</form>'
    ].join('');
};
ImageGallery.prototype.bindUploadEvent = function() {
    var $target = this.$renderTarget ? this.$renderTarget : $('body');
    var me = this;

    $(document).ready(function() {
        $target.find('.gallery-upload-button').click(function(){
            $('#image-gallery-upload-file').click();
        });
        $('#image-gallery-upload-file').change(function(){
            var originText = $target.find('.gallery-upload-button').text();
            $target.find('.gallery-upload-button').text('上传中...');
            $("#image-gallery-upload-form").ajaxSubmit({
                success: function( data ) {
                    $target.find('.gallery-upload-button').text(originText);
                    $('#image-gallery-upload-file').val('');

                    if (data.status==1)
                    {
                        $.post("/index.php/material/wechat_upload_image.html", {
                            url: data.url
                        }, function(data){
                            me.offset = 0;
                            me.pager();
                        }, 'json');
                    }else{
                        alert(data.msg);
                    }

                },
                dataType: "json"
            });
        });
    });
};
ImageGallery.prototype.renderItems = function() {
    var $target = this.$renderTarget ? this.$renderTarget.find('.gallery-content') : $('.gallery-content');
    if (this.files.length) {
        $target.html('');
        for (var i = 0; i < this.files.length; i++) {
            var item = this.wrapImage(this.files[i], this.files[i].id == this.selected);
            $target.append(item);
        }
        $target.append('<div style="clear:both"></div>');
    } else {
        $target.html('<div style="line-height: 400px; text-align: center;">您尚未上传素材</div>');
    }
};
ImageGallery.prototype.wrapImage = function(data, active) {
    return [
        '<div style="float: left; margin: 10px; box-shadow: 0px 0px 2px #BBB; ',
        this.inModal ? 'cursor: pointer;' : '',
        ' position: relative;" data-file-id="' + data.id + '" class="file-item">',
        // '<img src="' + data.url.replace('wx.y1y.me', 'y1y.test.com') + '" width="150px" height="150px"/>',
        '<img src="' + data.url + '" width="150px" height="150px"/>',
        active ? '<div style="position: absolute; right: 5px; top: 5px; width: 25px; height: 25px; line-height: 25px; border-radius: 50%; background: #33C268; text-align: center; font-size: 20px; color: #fff;">&#x2713;</div>' : '',
        data.sync == 1 ? '' : '<div style="position: absolute; left: 5px; top: 5px; width: 25px; height: 25px; border-radius: 50%; background: red; line-height: 23px; padding-left: 4px;">' + this.syncIcon + '</div>',
        this.inModal ? '' : '<div style="height: 30px; border: 1px solid #ddd"><div class="file-item-remove" style="float: right; cursor: pointer; margin: 2px 4px 0 0">' + this.removeIcon + '</div></div>',
        '</div>'
    ].join('');
};

function VoiceGallery(params)
{
    Gallery.call(this, params);
    this.title = '选择语音';
    this.uploadLabel = '上传语音';
    this.syncLabel = '同步语音';
    this.pageSize = 10;
    this.type = 'voice';
}
VoiceGallery.prototype = object(Gallery.prototype);
VoiceGallery.prototype.uploadForm = function() {
    return [
        '<form enctype="multipart/form-data" action="' + this.uploadURL + '" id="voice-gallery-upload-form" method="POST">',
        '<input type="file" name="photo" id="voice-gallery-upload-file">',
        '<input type="hidden" name="type" value="voice">',
        '<input type="hidden" name="title">',
        '<input name="doSubmit" type="submit" value="上传">',
        '</form>'
    ].join('');
};
VoiceGallery.prototype.bindUploadEvent = function() {
    var $target = this.$renderTarget ? this.$renderTarget : $('body');
    var me = this;
    $target.find('.gallery-upload-button').click(function(){
        $('#voice-gallery-upload-file').click();
    });
    $('#voice-gallery-upload-file').change(function(){
        var title;
        title = prompt('输入语音标题：');
        if (title) {
            $('#voice-gallery-upload-form input[name="title"]').val(title);

            var originText = $target.find('.gallery-upload-button').text();
            $target.find('.gallery-upload-button').text('上传中...');
            $("#voice-gallery-upload-form").ajaxSubmit({
                success: function( data ) {
                    $target.find('.gallery-upload-button').text(originText);
                    $('#voice-gallery-upload-file').val('');


                    if (data.status==1)
                    {
                        $.post("/index.php/material/wechat_upload_voice.html", {
                            url: data.url,
                            title:title
                        }, function(data){
                            me.offset = 0;
                            me.pager();
                        }, 'json');
                    }else{
                        alert(data.msg);
                    }


                },
                dataType: "json"
            });
        } else {
            alert('上传失败，无效的语音标题');
        }
    });
};
var pBindItemEvents = VoiceGallery.prototype.bindItemEvents;
VoiceGallery.prototype.bindItemEvents = function() {
    pBindItemEvents.call(this);
    this.bindPlayEvent();
};
VoiceGallery.prototype.bindPlayEvent = function() {
    var me = this;
    $('.voice-gallery-preview').click(function(){
        me.playVoice.call(me, $(this).data('file-id'));
    });
};
VoiceGallery.prototype.renderItems = function() {
    var $target = this.$renderTarget ? this.$renderTarget.find('.gallery-content') : $('.gallery-content');
    if (this.files.length) {
        $target.html('');
        for (var i = 0; i < this.files.length; i++) {
            var item = this.wrapVoice(this.files[i], this.files[i].id == this.selected);
            $target.append(item);
        }
    } else {
        $target.html('<div style="line-height: 400px; text-align: center;">您尚未上传素材</div>');
    }
};
VoiceGallery.prototype.wrapVoice = function(data, active) {
    return [
        '<div style="float: left; margin: 10px; box-shadow: 0px 0px 2px #BBB; cursor: pointer; position: relative;" data-file-id="' + data.id + '" class="file-item">',
        // '<img src="' + data.url.replace('wx.y1y.me', 'y1y.test.com') + '" width="150px" height="150px"/>',
        '<div style="width:260px;height:66px;padding:10px">',
        active ? '<div style="position: absolute; right: 5px; top: 5px; width: 25px; height: 25px; line-height: 25px; border-radius: 50%; background: #33C268; text-align: center; font-size: 20px; color: #fff;">&#x2713;</div>' : '',
        data.sync == 1 ? '' : '<div style="position: absolute; left: 5px; top: 5px; width: 25px; height: 25px; border-radius: 50%; background: red; line-height: 23px; padding-left: 4px;">' + this.syncIcon + '</div>',
        '<div class="voice-gallery-preview" data-file-id="' + data.id + '" style="',
        'width:44px;',
        'height:44px;',
        'float:left;',
        'background:url(/public/static/resource/images/',(this.playing == data.id ? 'audio_play.gif' : 'media_icon.png') + ')',
        ' 0 ' + (this.playing == data.id ? '0' : '-512px') + ' no-repeat;"></div>',
        '<div style="float: left; line-height: 1.6; width: 185px; overflow: hidden">',
        '<div>' + data.title + '</div>',
        '<div>创建于' + data.time + '</div>',
        '</div>',
        '</div>',
        this.inModal ? '' : '<div style="height: 30px; border: 1px solid #ddd"><div class="file-item-remove" style="float: right; cursor: pointer; margin: 2px 4px 0 0">' + this.removeIcon + '</div></div>',
        '</div>'
    ].join('');
};
VoiceGallery.prototype.playVoice = function(id) {
    if (this.playing == id) {
        this.clearVoice();
        return;
    }
    this.clearVoice();
    var src;
    var me = this;
    for (var i = 0; i < this.files.length; i++) {
        if (this.files[i].id == id) {
            src = this.files[i].url // .replace('wx.y1y.me', 'y1y.test.com');
            this.playing = id;
            break;
        }
    }
    if (src) {
        if (!this.audio) {
            this.audio = document.createElement("audio");
            this.audio.addEventListener('ended', function() {
                me.clearVoice();
                me.pager();
            }, false);
        }
        this.audio.src = src;
        this.audio.currentTime = 0;
        this.audio.play();
    }
};
VoiceGallery.prototype.release = function() {
    this.clearVoice();
};
VoiceGallery.prototype.clearVoice = function() {
    this.playing = 0;
    if (this.audio) {
        this.audio.pause();
    }
}

function NewsGallery(params)
{
    Gallery.call(this, params);
    this.title = '选择图文';
    this.uploadLabel = '';
    this.syncLabel = '同步图文';
    this.pageSize = 10;
    this.type = 'news';
    this.addNewsURL = params.addNewsURL;
}
NewsGallery.prototype = object(Gallery.prototype);
NewsGallery.prototype.uploadForm = function() {
    return [
        '<form enctype="multipart/form-data" action="' + this.uploadURL + '" id="image-gallery-upload-form" method="POST">',
        '<input type="file" name="photo" id="image-gallery-upload-file">',
        '<input name="doSubmit" type="submit" value="上传">',
        '</form>'
    ].join('');
};
NewsGallery.prototype.bindUploadEvent = function() {
    var $target = this.$renderTarget ? this.$renderTarget : $('body');
    var me = this;
    $target.find('.gallery-upload-button').click(function(){
        window.open(me.addNewsURL);
    });
};
NewsGallery.prototype.renderItems = function() {
    var $target = this.$renderTarget ? this.$renderTarget.find('.gallery-content') : $('.gallery-content');
    if (this.files.length) {
        $target.html('<div class="gallery-news-wrap" style="display: flex; height: 350px; overflow-y: auto; align-items: flex-start; flex-wrap: wrap;"></div>');
        $innerTarget = $('.gallery-news-wrap');
        for (var i = 0; i < this.files.length; i++) {
            var item = this.wrapImage(this.files[i], this.files[i].id == this.selected);
            $innerTarget.append(item);
        }
        this.scrollTop && $('.gallery-news-wrap').scrollTop(this.scrollTop);
    } else {
        $target.html('<div style="line-height: 400px; text-align: center;">您尚未上传素材</div>');
    }
};
NewsGallery.prototype.wrapImage = function(data, active) {
    var articles = [];
    var aData;
    try {
        aData = JSON.parse(data.content);
    } catch(e) {
        return;
    }
    var that = this;
    $.each(aData, function(index, item) {
        if (index == 0) {
            var article = that.wrapArticleTop(item);
        } else {
            var article = that.wrapArticle(item);
        }
        articles.push(article);
    });
    return [
        '<div style="margin: 10px; box-shadow: 0px 0px 2px #BBB; ',
        this.inModal ? 'cursor: pointer;' : '',
        ' position: relative;" data-file-id="' + data.id + '" class="file-item">',
        articles.join(''),
        active ? '<div style="position: absolute; right: 5px; top: 5px; width: 25px; height: 25px; line-height: 25px; border-radius: 50%; background: #33C268; text-align: center; font-size: 20px; color: #fff;">&#x2713;</div>' : '',
        data.sync == 1 ? '' : '<div style="position: absolute; left: 5px; top: 5px; width: 25px; height: 25px; border-radius: 50%; background: red; line-height: 23px; padding-left: 4px;">' + this.syncIcon + '</div>',
        this.inModal ? '' : '<div style="height: 30px; border: 1px solid #ddd"><div class="file-item-edit" style="float: left; cursor: pointer; margin: 2px 0 0 4px"><a target="_blank" href="' + this.addNewsURL + '&id=' + data.id + '">' + this.editIcon + '</a></div><div class="file-item-remove" style="float: right; cursor: pointer; margin: 2px 4px 0 0">' + this.removeIcon + '</div></div>',
        '</div>'
    ].join('');
};
NewsGallery.prototype.wrapArticle = function(data) {
    var link = data.url ? [
        '<a href="' + data.url + '" target="_blank" style="color: white">',
        data.title,
        '</a>',
    ].join('') : [
        '<a style="color: white">',
        data.title,
        '</a>',
    ].join('');
    return [
        '<div style="border-top: 1px solid #e7e7eb; width: 260px; height: 80px; padding: 10px; overflow: hidden">',
        '<div style="float:left; width: 160px;">',
        link,
        '</div>',
        '<div style="float:right">',
        '<img src="' + data.thumb_url.replace('mmbiz.qpic.cn', 'qqpic.oss-cn-shanghai.aliyuncs.com') + '" width="58px" height="58px">',
        '</div>',
        '<div style="clear:both"></div>',
        '</div>'
    ].join('');
};
NewsGallery.prototype.wrapArticleTop = function(data) {
    var link = data.url ? [
        '<a href="' + data.url + '" target="_blank" style="color: white">',
        data.title,
        '</a>',
    ].join('') : [
        '<a style="color: white">',
        data.title,
        '</a>',
    ].join('');
    return [
        '<div style="width: 260px; height: 160px; overflow: hidden; position: relative">',
        '<div>',
        '<img src="' + data.thumb_url.replace('mmbiz.qpic.cn', 'qqpic.oss-cn-shanghai.aliyuncs.com') + '" width="260px" height="160px">',
        '</div>',
        '<div style="position: absolute; bottom:0; left:0; width: 260px; height: 30px; padding: 0 10px; line-height: 30px; color: #fff; background: rgba(0,0,0,0.6); overflow: hidden;word-wrap: break-word;word-break: break-all;">',
        link,
        '</div>',
        '</div>'
    ].join('');
};
NewsGallery.prototype.confirm = function(data) {
    var data = {};
    for (var i = 0; i < this.files.length; i++) {
        if (this.files[i].id == this.selected) {
            data = $.extend(data, this.files[i]);
            // 输出dom
            data.preview = this.wrapImage(this.files[i]);
        }
    }
    // 检查素材是否同步成功
    if (this.needSync && this.syncCheckURL) {
        $.get(this.syncCheckURL, {file_id: data.id}, function(data){
            if (data.error_code != 0) {
                alert('素材同步失败，可能无法使用');
            }
        }, 'json');
    }
    this.selectCallback && this.selectCallback(data);
};

function VideoGallery(params) {
    Gallery.call(this, params);
    this.title = '选择视频';
    this.uploadLabel = '上传视频(MP4)';
    this.syncLabel = '同步视频';
    this.type = 'video';
}

VideoGallery.prototype = object(Gallery.prototype);
VideoGallery.prototype.uploadForm = function() {
    return [
        '<form enctype="multipart/form-data" action="' + this.uploadURL + '" id="video-gallery-upload-form" method="POST">',
        '<input type="file" name="photo" id="video-gallery-upload-file">',
        '<input name="doSubmit" type="submit" value="上传">',
        '<input type="hidden" name="title">',
        '<input type="hidden" name="type" value="video">',
        '</form>'
    ].join('');
};

VideoGallery.prototype.bindUploadEvent = function() {
    var $target = this.$renderTarget ? this.$renderTarget : $('body');
    var me = this;
    $target.find('.gallery-upload-button').click(function(){
        $('#video-gallery-upload-file').click();
    });
    $('#video-gallery-upload-file').change(function(){
        var title = prompt('请输入视频标题：');
        if(title) {
            var originText = $target.find('.gallery-upload-button').text();

            $('#video-gallery-upload-form input[name="title"]').val(title);
            $target.find('.gallery-upload-button').text('上传中...');
            $("#video-gallery-upload-form").ajaxSubmit({
                success: function( data ) {
                    $target.find('.gallery-upload-button').text(originText);
                    $('#video-gallery-upload-file').val('');
                    if (data.error_code == 0) {
                        me.files.unshift({
                            id: data.id,
                            url: data.url
                        });
                        me.offset = 0;
                        me.pager();
                    } else {
                        alert(data.error_msg);
                    }
                },
                dataType: "json"
            });
        } else {
            alert('上传失败，无效的视频标题');
        }
    });
};

VideoGallery.prototype.renderItems = function() {
    var $target = this.$renderTarget ? this.$renderTarget.find('.gallery-content') : $('.gallery-content');
    if (this.files.length) {
        $target.html('');
        for (var i = 0; i < this.files.length; i++) {
            var item = this.wrapVideo(this.files[i], this.files[i].id == this.selected);
            $target.append(item);
        }
        $target.append('<div style="clear:both"></div>');
    } else {
        $target.html('<div style="line-height: 400px; text-align: center;">您尚未上传素材</div>');
    }
};

VideoGallery.prototype.wrapVideo = function(data, active) {
    return [
        '<a href="' + data.url + '" target="_blank" style="float: left; margin: 10px; box-shadow: 0px 0px 2px #BBB; ',
        this.inModal ? 'cursor: pointer;' : '',
        ' position: relative;" data-file-id="' + data.id + '" class="file-item video">',
        '<div style="padding: 20px; font-size: 18px;">视频：' + data.title + '</div>',
        active ? '<div style="position: absolute; right: 5px; top: 5px; width: 25px; height: 25px; line-height: 25px; border-radius: 50%; background: #33C268; text-align: center; font-size: 20px; color: #fff;">&#x2713;</div>' : '',
        data.sync == 1 ? '' : '<div style="position: absolute; left: 5px; top: 5px; width: 25px; height: 25px; border-radius: 50%; background: red; line-height: 23px; padding-left: 4px;">' + this.syncIcon + '</div>',
        this.inModal ? '' : '<div style="height: 30px; border: 1px solid #ddd"><div class="file-item-remove" style="float: right; cursor: pointer; margin: 2px 4px 0 0">' + this.removeIcon + '</div></div>',
        '</a>'
    ].join('');
};