function Menu(dom) {
    this.dom = dom;
    this.menu = [];
    this.current = '';
    this.wrapper = [
        '<div class="diy-menu-wrapper">',
        '<div class="diy-menu-main">',
        '<div class="diy-menu-decorate diy-menu-block">',
        '</div>',
        '</div>',
        '</div>'
    ].join('');
    this.inner = [
        '<div class="diy-menu-main">',
        '<div class="diy-menu-decorate diy-menu-block">',
        '</div>',
        '</div>'
    ].join('');
    this.selectHandler = this.selectHandler.bind(this);
    this.addHandler = this.addHandler.bind(this);
    this.deleteHandler = this.deleteHandler.bind(this);
    this.dragHandler = this.dragHandler.bind(this);
    this.updateMenu = this.updateMenu.bind(this);
}

Menu.prototype = {
    $: function (dom) {
        return this.dom.find(dom);
    },
    defaultItem: function (children) {
        var str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var id = '';
        for (var i = 0; i < 10; i++) {
            id += str[Math.floor(Math.random() * str.length)];
        }
        return {
            title: children ? '子菜单' : '菜单',
            id: id,
            children: []
        };
    },
    render: function () {
        if (!this.$('.diy-menu-wrapper').length) {
            this.dom.append(this.wrapper);
        }
        else {
            this.$('.diy-menu-wrapper').html(this.inner);
        }
        for (var i = 0; i < this.menu.length; i++) {
            var item = this.menu[i];
            this.$('.diy-menu-main').append(this.menuBlock(item, i));
            var offset = 1;
            if (item.children.length < 5) {
                this.$('.diy-menu-main').append('<div class="diy-menu-add diy-menu-block diy-menu-add-slave diy-menu-add-slave-' + i + '" data-parent="' + item.id + '"></div>');
            } else {
                offset = 0;
            }
            for (var j = 0; j < item.children.length; j++) {
                var cItem = item.children[item.children.length-j-1];
                this.$('.diy-menu-main').append(this.menuBlock(cItem, i, j+offset));
            }
        }
        if (this.menu.length < 3) {
            this.$('.diy-menu-main').append('<div class="diy-menu-add diy-menu-block diy-menu-add-' + this.menu.length + '"></div>');
        }
        this.bindEvents();
    },
    bindEvents: function () {
        this.$('.diy-menu-slave').click(this.selectHandler);
        this.$('.diy-menu-master').click(this.selectHandler);
        this.$('.diy-menu-add').click(this.addHandler);
        this.$('.diy-menu-edit-delete').click(this.deleteHandler);
        var items = this.$('.diy-menu-slave');
        for (var i = 0; i < items.length; i++) {
            items[i].ondragend = this.dragHandler;
        }
    },
    dragHandler: function (e) {
        var id = $(e.target).data('id');
        var isMaster = !$(e.target).hasClass('diy-menu-slave');

        if (isMaster) {

        }
        else {
            var positionY = e.y + $(window).scrollTop();
            var positionX = e.x;

            for (var i = 0; i < this.menu.length; i++) {
                var item = this.menu[i];
                var found = false;
                var current = -1;
                var newMenuIndex = -1;
                var newIndex = -1;
                var offsetsY = [];
                var offsetsX = [];

                for (var j = 0; j < item.children.length; j++) {
                    var cItem = item.children[j];
                    if (cItem.id == id) {
                        found = true;
                        current = j;
                    }
                }

                if (found) {
                    // 拖拽逻辑修复

                    for (var k = 0; k < this.menu.length; ++ k) {
                        var $targetMenu = this.$('.diy-menu-' + this.menu[k].id);
                        offsetsX[k] = $targetMenu.offset().left - $targetMenu.width() / 2;
                        offsetsY[k] = [];

                        for (var j = 0; j < this.menu[k].children.length; j++) {
                            var $target = this.$('.diy-menu-' + this.menu[k].children[j].id);
                            offsetsY[k][j] = $target.offset().top; + $target.height() / 2;
                        }
                    }

                    for (var k = 0; k < this.menu.length; ++ k) {
                        if(
                            positionX > offsetsX[k] &&
                            (k == this.menu.length - 1 || positionX <= offsetsX[k + 1])
                        ) {
                            newMenuIndex = k;

                            for (var j = 0; j < this.menu[k].children.length; j++) {
                                if (
                                    positionY > offsetsY[k][j] &&
                                    (j == this.menu[k].children.length - 1 || positionY <= offsetsY[k][j + 1])
                                ) {
                                    newIndex = j;
                                    break;
                                }
                            }

                            break;
                        }
                    }

                    if (newIndex != -1) {
                        // newIndex = item.children.length - 1;
                        var tempItem = this.menu[i].children[current];
                        this.menu[i].children[current] = this.menu[newMenuIndex].children[newIndex];
                        this.menu[newMenuIndex].children[newIndex] = tempItem;
                    }

                    // if (current < newIndex) {
                    //     for (var k = current + 1; k <= newIndex; k++) {
                    //         item.children[k-1] = item.children[k];
                    //     }
                    //     item.children[k-1] = newItem;
                    // }
                    // else {
                    //     for (var k = current - 1; k >= newIndex; k--) {
                    //         item.children[k+1] = item.children[k];
                    //     }
                    //     item.children[k+1] = newItem;
                    // }

                    this.render();
                    this.selectBlock(id);
                    break;
                }
            }
        }
    },
    selectHandler: function (e) {
        var id = $(e.target).data('id');
        if (!id) {
            id = $(e.target).parent().parent().data('id');
        }
        this.selectBlock(id);
    },
    addHandler: function (e) {
        var $target = $(e.target);
        var parent = $target.data('parent');
        var isMaster = !$target.hasClass('diy-menu-add-slave');
        if (isMaster) {
            this.addMaster();
        }
        else {
            this.addChildren(parent);
        }
    },
    deleteHandler: function (e) {
        var id = $(e.target).parent().parent().data('id');
        this.deleteBlock(id);
    },
    deleteBlock: function (id) {
        if (!id) return;
        for (var i = 0; i < this.menu.length; i++) {
            var item = this.menu[i];
            if (item.id == id) {
                this.menu.splice(i, 1);
                break;
            }
            for (var j = 0; j < item.children.length; j++) {
                var cItem = item.children[j];
                if (cItem.id == id) {
                    item.children.splice(j, 1);
                    break;
                }
            }
        }
        this.selectBlock();
        this.render();
    },
    addMaster: function () {
        var newItem = this.defaultItem();
        this.menu.push(newItem);
        this.render();
        this.selectBlock(newItem.id);
    },
    addChildren: function (parent) {
        for (var i = 0; i < this.menu.length; i++) {
            var item = this.menu[i];
            if (item.id == parent) {
                var newItem = this.defaultItem(true);
                item.children.push(newItem);
                break;
            }
        }
        this.render();
        this.selectBlock(newItem.id);
    },
    selectBlock: function (id) {
        this.current = id;
        $('.diy-menu-edit-box').hide();
        this.$('.diy-menu-' + id).find('.diy-menu-edit-box').show();
        $('.diy-menu-block').css('z-index', 10);
        this.$('.diy-menu-' + id).css('z-index', 20);

        var menu = this.findMenu(id, true);
        this.selectCallback && this.selectCallback(menu);
    },
    updateMenu: function (id, data) {
        $.extend(this.findMenu(id), data);
        this.render();
        this.selectBlock(id);
    },
    findMenu: function (id, copy) {
        for (var i = 0; i < this.menu.length; i++) {
            var masterMenu = this.menu[i];
            if (masterMenu.id == id) {
                return copy ? $.extend(true, {type: 'master'}, masterMenu) : masterMenu;
            }
            for (var j = 0; j < masterMenu.children.length; j++) {
                var slaveMenu = masterMenu.children[j];
                if (slaveMenu.id == id) {
                    return copy ? $.extend(true, {type: 'slave'}, slaveMenu) : slaveMenu;
                }
            }
        }
    },
    menuBlock: function (item, number1, number2) {
        master = number2 === undefined ;
        var number = !master ? number1 + '-' + number2 : number1;
        var masterSlaveMark = master ? 'diy-menu-master' : 'diy-menu-slave';
        return [
            '<div draggable="true" class="diy-menu-block diy-menu-block-editable ',
            masterSlaveMark + ' ' + masterSlaveMark + '-' + number + ' diy-menu-' + item.id + '" ',
            'data-id="' + item.id + '"',
            '>',
            '<div class="diy-menu-edit-box" style="display:none">',
            '<div class="diy-menu-edit"></div>',
            '<div class="diy-menu-edit-delete"></div>',
            '</div>',
            item.title,
            '</div>'
        ].join('');
    }

};

function PropertyPanel(dom) {
    this.dom = dom;
    this.menuId = '';
    this.selectBlock = this.selectBlock.bind(this);
    this.loadWechatMenu = this.loadWechatMenu.bind(this);
    this.loadMenuByVersion = this.loadMenuByVersion.bind(this);
    this.clearHistory = this.clearHistory.bind(this);
}
PropertyPanel.prototype = {
    $: function (dom) {
        return this.dom.find(dom);
    },
    wrapper: [
        '<div class="property-panel-wrapper">',
        '</div>'
    ].join(''),
    renderBlock: function (target, menu) {
        var actionSheet = [
            '<div class="form-group">',
            '<label>菜单功能</label>',
            '<select class="form-control change-action">',
            '<option value="1">关键词回复菜单</option>',
            '<option value="2">url链接菜单</option>',
            '<option value="6">回复文本消息</option>',
            '<option value="7">回复图片消息</option>',
            '<option value="8">回复语音消息</option>',
            '<option value="9">回复图文消息</option>',
            '</select>',
            '</div>'
        ].join('');
        var dom = [
            '<div class="form-group">',
            '<label>菜单名称（不超过5个汉字）</label>',
            '<input type="text" class="form-control update-title" value="' + menu.title + '">',
            '<span class="help-block update-title-help" style="display: none">不能超过5个汉字或10个字符</span>',
            '</div>',
            actionSheet,
            '<div class="action-box"></div>',
            '</div>'
        ].join('');

        target.html(dom);

        this.changeAction();
    },
    render: function (menu) {
        var target = this.$('.property-panel-wrapper');
        if (!target.length) {
            this.dom.append(this.wrapper);
            target = this.$('.property-panel-wrapper')
        }
        else {
            target.html('');
        }
        if (menu) {
            $('.ftip').hide();
            this.renderBlock(target, menu);
        } else {
            $('.ftip').show();
        }
        this.submitForm && target.append(this.submitForm);

        this.bindEvents();
    },
    bindEvents: function () {
        var me = this;
        this.$('.update-title').change(function () {
            me.updateTitle.call(me, this.value);
        });
        this.$('.change-action').change(function () {
            me.changeAction.call(me, this.value);
        });
        this.$('.update-keyword').change(function () {
            me.updateKeyword.call(me, this.value);
        });
        this.$('.update-url').change(function () {
            me.updateUrl.call(me, $('#url-address').val(),$('input[name=check_domain]:checked').val());
        });
        this.$('.update-appurl').change(function () {
            me.updateApp.call(me);
        });
        this.$('.update-appid').change(function () {
            me.updateApp.call(me);
        });
        this.$('.update-pagepath').change(function () {
            me.updateApp.call(me);
        });
        this.$('.update-wxsys').change(function () {
            me.updateWxsys.call(me, this.value);
        });
        this.$('.update-tel').change(function () {
            me.updateTel.call(me, this.value);
        });
        this.$('.update-latitude').change(function () {
            me.updateLocation.call(me, $('#latitude').val(), $('#longitude').val());
        });
        this.$('.update-longitude').change(function () {
            me.updateLocation.call(me, $('#latitude').val(), $('#longitude').val());
        });
        this.$('.update-reply-text').change(function () {
            me.updateReplyText.call(me, this.value);
        });
        this.$('#update-reply-image').change(function () {
            me.updateReplyImage.call(me, this.value, me.$('#update-reply-image-media-id').val());
        });
        this.$('#update-reply-voice').change(function () {
            me.updateReplyVoice.call(me, this.value, me.$('#update-reply-voice-media-id').val(), $('.reply-voice-preview-title').text(), $('.reply-voice-preview-time').text());
        });
        this.$('#update-reply-news-media-id').change(function () {
            try {
                me.updateReplyNews.call(me, this.value, JSON.parse(me.$('.reply-news-preview').data('content')));
            } catch (e) {

            }
        });
        this.$('.reply-image-upload').click(function() {
            gallery.open();
        });
        this.$('.reply-voice-upload').click(function() {
            voiceGallery.open();
        });
        this.$('.reply-news-upload').click(function() {
            newsGallery.open();
        });
        $('.reply-voice-preview-btn').click(this.previewVoice);
        this.$('#saveForm').submit(saveMenu);
        this.$('.getMenuFromWechat').click(function (e) {
            me.loadWechatMenu(e);
        });
        $('#getMenuByVersion').click(function(e) {
            me.loadMenuByVersion(e);
        });
        $('#clearHistory').click(function(e) {
            me.clearHistory(e);
        });
        this.$('#diy-select-all').click(function(e) {
            $('.wxuser-checkbox').prop('checked', 'checked');
        });

        this.$('#diy-select-reverse').click(function(e) {
            $('.wxuser-checkbox').each(function() {
                if($(this).prop('checked')) {
                    $(this).removeProp('checked');
                } else {
                    $(this).prop('checked', 'checked');
                }
            });
        });

    },
    changeAction: function (value) {
        var dom;
        action = this.menu.action || {};
        if (action.type) {
            value = value || action.type;
        }
        value = parseInt(value);
        if (this.menu && !value) {
            value = 1;
        }
        if (this.menu.type == 'master' && this.menu.children.length) {
            value = 99;
        }
        this.$('.change-action').val(value);
        switch (value) {
            case 1: // 关键字回复
                dom = [
                    '<div class="form-group">',
                    '<label>要触发的关键字</label>',
                    '<div class="row">',
                    '<div class="col-xs-12">',
                    '<input type="text" class="form-control update-keyword" id="menu_keyword" value="' + (action.keyword || '') + '">',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 2: // url链接菜单
                dom = [
                    '<div class="form-group">',
                    '<label>要链接到的URL地址</label>',
                    '<div class="row">',
                    '<div class="col-xs-12">',
                    '<input type="text" class="form-control update-url" id="url-address" value="' + (action.url || '') + '">',
                    '<div class="help-block" style="display:none">' ,
                    '域名检测:',
                    '<label for="check_domain1" class="label">' +
                    '   <input type="radio" class="radio ipt update-url" value="1" name="check_domain" id="check_domain1" ' + (action.check_domain == 1 ? 'checked' : '') + '>' ,
                    '<span class="radioInput"></span> 启用' ,
                    '</label>',
                    '<label for="check_domain2" class="label">' +
                    '   <input type="radio" class="radio ipt update-url" value="0" name="check_domain" id="check_domain2" ' + (action.check_domain == 0 ? 'checked' : '') + '>' ,
                    '<span class="radioInput"></span> 关闭' ,
                    '</label>',
                    '</div>',
                    '<span class="help-block">禁止使用短网址</span>',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 3: // 扫码带提示
                dom = [
                    '<div class="form-group">',
                    '<label>拓展菜单</label>',
                    '<div class="row">',
                    '<div class="col-xs-6">',
                    '<select class="form-control update-wxsys">',
                    '<option value="扫码带提示">扫码带提示</option>',
                    '<option value="扫码推事件">扫码推事件</option>',
                    '<option value="系统拍照发图">系统拍照发图</option>',
                    '<option value="拍照或者相册发图">拍照或者相册发图</option>',
                    '<option value="微信相册发图">微信相册发图</option>',
                    '<option value="发送位置">发送位置</option>',
                    '</select>',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 4: // 一键拨号菜单
                dom = [
                    '<div class="form-group">',
                    '<label>一键拔号</label>',
                    '<div class="row">',
                    '<div class="col-xs-12">',
                    '<input type="text" class="form-control update-tel" value="' + (action.tel || '') + '">',
                    '<span class="help-block">格式：0551-688888888 或 13888888888</span>',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 5: // 一键导航
                dom = [
                    '<div class="form-group">',
                    '<label>一键导航</label>',
                    '<div class="row">',
                    '<div class="col-xs-3">',
                    '<input type="text" class="form-control update-longitude" id="longitude" value="' + (action.longitude || '') + '">',
                    '</div>',
                    '<div class="col-xs-3">',
                    '<input type="text" class="form-control update-latitude" id="latitude" value="' + (action.latitude || '') + '">',
                    '</div>',
                    '<div class="col-xs-2">',
                    '<a onclick="setlatlng($(\'#longitude\').val(),$(\'#latitude\').val())" class="btn btn-default">在地图中选择/查看</a>',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 6: // 回复文本消息
                dom = [
                    '<div class="form-group">',
                    '<label>要回复的文本内容</label>',
                    '<textarea class="form-control update-reply-text" rows="12">' + (action.reply_text || '') + '</textarea>',
                    '</div>'
                ].join('');
                break;
            case 7: // 回复图片消息
                dom = [
                    '<div class="form-group reply-image-box">',
                    '<label>要回复的图片</label><br>',
                    '<input type="hidden" id="update-reply-image" class="form-control" value="' + (action.reply_file_url || '') + '">',
                    '<input type="hidden" id="update-reply-image-media-id" class="form-control" value="' + (action.reply_gallery_id || '') + '">',
                    '<a class="btn btn-default reply-image-upload">选择图片</a>',
                    '<img src="' + (action.reply_file_url || '') + '" class="reply-image-preview">',
                    '</div>'
                ].join('');
                break;
            case 8: // 回复图片消息
                dom = [
                    '<div class="form-group reply-voice-box">',
                    '<label>要回复的语音</label><br>',
                    '<input type="hidden" id="update-reply-voice" class="form-control" value="' + (action.reply_file_url || '') + '">',
                    '<input type="hidden" id="update-reply-voice-media-id" class="form-control" value="' + (action.reply_gallery_id || '') + '">',
                    '<a class="btn btn-default reply-voice-upload">选择语音</a>',
                    '<div class="form-group" style="box-shadow: 0px 0px 2px #BBB;width:260px; ',
                    (action.reply_gallery_id && action.reply_title ? '' : 'display: none;'),
                    '">',
                    '<div style="width:260px;height:66px;padding:10px" class="reply-voice-preview">',
                    '<div class="reply-voice-preview-btn" data-file-url="' + action.reply_file_url + '" style="width:44px;height:44px;float:left;background:url(/public/static/resource/img/media_icon.png) 0 -512px no-repeat;"></div>',
                    '<div style="float: left; line-height: 1.6; width: 185px; overflow: hidden">',
                    '<div class="reply-voice-preview-title">' + action.reply_title + '</div><div>创建于 <span class="reply-voice-preview-time">' + action.reply_time + '</span></div>',
                    '</div>',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 9: // 回复图文消息
                dom = [
                    '<div class="form-group reply-news-box" style="position: relative">',
                    '<label>要回复的图文</label><br>',
                    '<input type="hidden" id="update-reply-news-media-id" class="form-control" value="' + (action.reply_gallery_id || '') + '">',
                    '<a class="btn btn-default reply-news-upload">选择图文</a>',
                    '<div class="form-group reply-news-preview" style="width: 280px;left:180px;">',
                    action.reply_file_content,
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 10: // 小程序
                dom = [
                    '<div class="form-group reply-miniprogram-box">',
                    '<label>URL地址</label>',
                    '<div class="row">',
                    '<div class="col-xs-12">',
                    '<input type="text" class="form-control update-appurl" value="' + (action.appurl || '') + '">',
                    '<span class="help-block">不支持小程序的老版本将打开本地址</span>',
                    '</div>',
                    '<div class="help-block" style="display:none">' ,
                    '域名检测:',
                    '<label for="check_domain1" class="label">' +
                    '<input type="radio" class="radio ipt update-appurl" value="1" name="check_domain" id="check_domain1" ' + (action.check_domain == 1 ? 'checked' : '') + '>' ,
                    '<span class="radioInput"></span> 启用' ,
                    '</label>',
                    '<label for="check_domain2" class="label">' +
                    '<input type="radio" class="radio ipt update-appurl" value="0" name="check_domain" id="check_domain2" ' + (action.check_domain == 0 ? 'checked' : '') + '>' ,
                    '<span class="radioInput"></span> 关闭' ,
                    '</label>',
                    '</div>',
                    '</div>',
                    '<label>小程序appid</label>',
                    '<div class="row">',
                    '<div class="col-xs-12">',
                    '<input type="text" class="form-control update-appid" value="' + (action.appid || '') + '">',
                    '</div>',
                    '</div>',
                    '<label>小程序页面路径</label>',
                    '<div class="row">',
                    '<div class="col-xs-12">',
                    '<input type="text" class="form-control update-pagepath" value="' + (action.pagepath || '') + '">',
                    '</div>',
                    '</div>',
                    '</div>'
                ].join('');
                break;
            case 99:
                dom = [
                    '<div class="form-group">',
                    '<label>已有子菜单，无法编辑功能</label>',
                    '</div>'
                ].join('');
                this.$('.change-action').hide();
                break;
            default:
                dom = [
                    '<div class="ftip" style="margin:10px auto;">',
                    '注意及时保存菜单配置，菜单只有发布后才会在手机侧显示<br>',
                    '微信对于公众号自定义菜单有一定缓存时间，发布菜单后如果想及时看到菜单修改，可以取消关注再重新关注公众号快速地看到新菜单。<br>',
                    '</div>'
                ].join('');
                break;
        }
        this.$('.action-box').html(dom);
        this.bindEvents();
        if (value == 3 && action.wxsys) {
            this.$('.update-wxsys').val(action.wxsys);
        }
        else if (value == 9 && action.reply_file_content) {
            $('.reply-news-preview').html(newsGallery.wrapImage({
                content: JSON.stringify(action.reply_file_content)
            }));
        }
    },
    updateTitle: function (value) {
        var length = value.length;

        for (var i = 0; i < value.length; i++) {
            if (value.charCodeAt(i) > 255) {
                length++;
            }
        }

        if (length > 12) {
            this.$('.update-title-help').show();
            return;
        } else {
            this.$('.update-title-help').hide();
        }
        this.update({
            title: value
        });
    },
    updateKeyword: function (value) {
        this.update({
            action: {
                type: 1,
                keyword: value
            }
        });
    },
    updateUrl: function (value,check_domain) {
        this.update({
            action: {
                type: 2,
                url: value,
                check_domain: check_domain
            }
        });
    },
    updateWxsys: function (value) {
        this.update({
            action: {
                type: 3,
                wxsys: value
            }
        });
    },
    updateTel: function (value) {
        this.update({
            action: {
                type: 4,
                tel: value
            }
        });
    },
    updateLocation: function (latitude, longitude) {
        this.update({
            action: {
                type: 5,
                latitude: latitude,
                longitude: longitude
            }
        });
    },
    updateReplyText: function (value) {
        this.update({
            action: {
                type: 6,
                reply_text: value
            }
        });
    },
    updateReplyImage: function (value, value2) {
        this.update({
            action: {
                type: 7,
                reply_file_url: value,
                reply_gallery_id: value2,
                reply_type: 'image'
            }
        });
    },
    updateReplyVoice: function (value, value2, title, time) {
        this.update({
            action: {
                type: 8,
                reply_file_url: value,
                reply_gallery_id: value2,
                reply_type: 'voice',
                reply_title: title,
                reply_time: time
            }
        });
    },
    updateReplyNews: function (value, content) {
        this.update({
            action: {
                type: 9,
                reply_file_content: content,
                reply_gallery_id: value,
                reply_type: 'news',
            }
        });
    },
    updateApp: function () {
        this.update({
            action: {
                type: 10,
                appurl: this.$('.update-appurl').val(),
                appid: this.$('.update-appid').val(),
                pagepath: this.$('.update-pagepath').val(),
                check_domain: this.$('[name=check_domain]:checked').val()
            }
        });
    },
    update: function (data) {
        this.updateCallback && this.updateCallback(this.menuId, data);
    },
    previewVoice: function () {
        if ($(this).data('playing') == 1) {
            return;
        }
        $(this).data('playing', 1);
        $(this).css('background', 'url(/public/static/resource/img/audio_play.gif) 0 0 no-repeat');
        var src = $(this).data('file-url')  // .replace('wx.y1y.me', 'y1y.test.com');
        var target = $(this);
        var audio = document.createElement("audio");
        audio.src = src;
        audio.addEventListener('ended', function() {
            target.css('background', 'url(/public/static/resource/img/media_icon.png) 0 -512px no-repeat');
            target.data('playing', 0);
        }, false);
        audio.play();
    },
    selectBlock: function (menu) {
        if (menu) {
            this.menuId = menu.id;
            this.menu = menu;
        }
        this.render(menu);
    },
    loadWechatMenu: function (e) {
        var $target = $(e.target);
        if ($target.text() == '正在读取...') {
            return;
        }

        var me = this;
        $target.text('正在读取...');
        $.get(loadURL, '', function (data) {
            $target.text('读取当前公众号菜单');
            if (data.error_code == 0) {
                var newMenu = formatMenu(data.menus);
                me.menuObject.menu = newMenu;
                me.menuObject.render();
                toastr.success('读取完成！');
            } else if (data.error_code == 1) {
                alert('当前公众号没有自定义菜单');
            } else {
                alert('读取失败');
            }
        }, 'json');
    },
    loadMenuByVersion: function (e) {
        var $target = $(e.target);
        if ($target.text() == '正在读取...') {
            return;
        }

        var me = this;
        $target.text('正在读取...');
        $.get(lazyReadMenusURL + '?version=' + $('#menu-version').val(), '', function(data){
            var newMenu = formatMenu(data);
            me.menuObject.menus = newMenu;
            me.menuObject.render();
            $target.text('读取历史菜单');
            toastr.success('读取完成！');
        }, 'json');
    },
    clearHistory: function(e) {
        $.post(clearHistoryURL, {}, function() {
            toastr.success('历史菜单已清除！');
            setTimeout(function(){window.location.href='/index.php/selfmenu/index.html';},1500);
        }, 'json');
    }
}

window.onload = function () {
    // 如果JSON PARSE失败，改为异步加载菜单
    try {
        originalMenu = JSON.parse(originalMenu) || [];
        init();
    } catch (e) {

        $.get(lazyReadMenusURL, '', function(data){
            originalMenu = data;
            init();
        }, 'json');
    }
};

function init() {
    var formatedMenu = formatMenu(originalMenu);
    menuObject = new Menu($('#diy-menu-phone'));
    menuObject.menu = formatedMenu;
    menuObject.render();

    var panel = new PropertyPanel($('#diy-panel'));

    panel.submitForm = [
        '<form id="saveForm" action="' + postURL + '" method="post">',
        '<input type="hidden" name="menu" id="saveFormInput">',
        '<button class="btn btn-success" style=" line-height:30px;margin-top:10px;margin-right:10px" type="submit">保存新版本并发布菜单</button>',
        '<a class="btn btn-success getMenuFromWechat" style=" line-height:30px;margin-top:10px;">读取当前公众号菜单</a>',
        '</form>'
    ].join('');
    panel.render();

    menuObject.selectCallback = panel.selectBlock;
    panel.updateCallback = menuObject.updateMenu;
    panel.menuObject = menuObject;
    window.menu = menuObject.menu;

    gallery = new ImageGallery({
        uploadURL: "/index.php/utility/wechat_upload_image.html",
        fetchURL: "/index.php/material/fetch.html",
        syncURL: "/index.php/material/sync.html",
        needSync: true,
        syncCheckURL: "/index.php/material/synccheck.html",
        selectCallback: function(data) {
            if (data.url) {
                var $image = $('.reply-image-preview');
                $image.parent().show();
                $image.prop('src', data.url);
            }
            $('#update-reply-image-media-id').val(data.id);
            $('#update-reply-image').val(data.url).change();
        }
    });
    voiceGallery = new VoiceGallery({
        uploadURL: "/index.php/utility/wechat_upload_voice.html",
        fetchURL: "/index.php/material/fetch.html",
        syncURL: "/index.php/material/sync.html",
        needSync: false,
        syncCheckURL: "/index.php/material/synccheck.html",
        selectCallback: function(data) {
            if (data.url) {
                $('.reply-voice-preview').parent().show();
                $('.reply-voice-preview-title').text(data.title);
                $('.reply-voice-preview-time').text(data.time);
                $('.reply-voice-preview-btn').data('file-url', data.url);
            }
            $('#update-reply-voice-media-id').val(data.id);
            $('#update-reply-voice').val(data.url).change();
        },
        staticURL: staticURL
    });
    newsGallery = new NewsGallery({
        uploadURL: "",
        fetchURL: "/index.php/material/fetch.html",
        syncURL: "/index.php/material/sync.html",
        addNewsURL: "/index.php/material/sync.html",
        needSync: false,
        syncCheckURL: "/index.php/material/synccheck.html",
        selectCallback: function(data) {
            $('.reply-news-preview').data('content', data.content);
            $('#update-reply-news-media-id').val(data.id).change();
        },
        staticURL: staticURL
    });
}

var menuObject, gallery, voiceGallery, newsGallery;
function saveMenu(e){
    var menuForPost = [];
    for (var i = 0; i < menuObject.menu.length; i++) {
        var om = menuObject.menu[i];
        var nm = $.extend(true, {
            title: om.title,
            class: []
        }, om.action);
        handelActionDiff(nm);
        for (var j = 0; j < om.children.length; j++) {
            var os = om.children[j];
            var ns = $.extend(true, {
                title: os.title
            }, os.action);
            handelActionDiff(ns);
            nm.class.push(ns);
        }
        menuForPost.push(nm);
    }
    console.log('menu', menuForPost);
    $('#saveFormInput').val(JSON.stringify(menuForPost));

    $(".loader").show();

    $.post(postURL, { menu: JSON.stringify(menuForPost)}, function (data) {
        $(".loader").hide();
        if (data.error_code == 0) {
            toastr.success('已成功发布！');
            setTimeout(function(){window.location.href='/material/menu/custom';},1500);
        } else
            toastr.error(data.msg);
    }, 'json');
    return false;
}

function handelActionDiff(o) {
    if (o.type == 5) {
        o.nav = o.longitude + ',' + o.latitude;
        delete o.longitude;
        delete o.latitude;
    }
    delete o.type;
}

function unicodeToChar(text) {
    return text.replace(/\\u[\dA-F]{4}/gi,
        function (match) {
            return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
        });
}

function formatMenu(originalMenu) {
    if(!originalMenu) {
        return formatedMenu;
    }

    var formatedMenu = [];
    for (var i = 0; i < originalMenu.length; i++) {
        var om = originalMenu[i];
        var masterMenu = {
            title: unicodeToChar(om.title),
            id: om.id || randomID(),
            children: []
        };
        if (om.class && om.class.length) {
            for (var j = 0; j < om.class.length; j++) {
                var os = om.class[j];
                var slaveMenu = {
                    title: unicodeToChar(os.title),
                    id: os.id || randomID(),
                    action: formatAction(os)
                };
                masterMenu.children.push(slaveMenu);
            }
        } else {
            masterMenu.action = formatAction(om);
        }
        formatedMenu.push(masterMenu);
    }
    return formatedMenu;
}
function randomID() {
    var str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var id = '';
    for (var i = 0; i < 10; i++) {
        id += str[Math.floor(Math.random() * str.length)];
    }
    return id;
}
function formatAction(o) {
    var action = {};
    if (o.keyword && !o.reply_text && !o.reply_type) {
        action.type = 1;
        action.keyword = o.keyword
    }
    else if (o.url) {
        action.type = 2;
        action.url = o.url;
        action.check_domain = o.check_domain;
    }
    else if (o.wxsys) {
        action.type = 3;
        action.wxsys = o.wxsys;
    }
    else if (o.tel) {
        action.type = 4;
        action.tel = o.tel;
    }
    else if (o.nav) {
        var nav = o.nav.split(',');
        action.type = 5;
        action.longitude = nav[0];
        action.latitude = nav[1];
    }
    else if (o.reply_text) {
        action.type = 6;
        action.reply_text = o.reply_text;
    }
    else if (o.reply_type == 'image') {
        action.type = 7;
        action.reply_file_url = o.gallery_file_url;
        action.reply_gallery_id = o.reply_gallery_id;
        action.reply_type = 'image';
    }
    else if (o.reply_type == 'voice') {
        action.type = 8;
        action.reply_file_url = o.gallery_file_url;
        action.reply_gallery_id = o.reply_gallery_id;
        action.reply_title = o.gallery_title;
        action.reply_time = o.gallery_time;
        action.reply_type = 'voice';
    }
    else if (o.reply_type == 'news') {
        action.type = 9;
        action.reply_gallery_id = o.reply_gallery_id;
        action.reply_file_content = o.content;
        action.reply_type = 'news';
    }
    else if (o.appid) {
        action.type = 10;
        action.appurl = o.appurl;
        action.appid = o.appid;
        action.pagepath = o.pagepath;
        action.check_domain = o.check_domain;
    }
    return action;
}