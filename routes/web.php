<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUseWechatId;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 欢迎回家
Route::get('/notice/list', 'HomeController@noticeIndex')->name('home');
Route::post('/notice_check/update', 'HomeController@noticeUpdateStatus')->name('notice.update.status');
// 测试
Route::get('/test', 'HomeController@testindex')->name('test');


//用户管理
Route::group(['prefix' => 'admin_user', 'namespace' => 'AdminUser'], function () {
    Route::get('/list', 'AccountController@list')->name('admin_user.list');
    Route::get('/edit', 'AccountController@edit')->name('admin_user.edit');
    Route::post('/save', 'AccountController@save')->name('admin_user.save');
    Route::delete('/delete_user/{id}', 'AccountController@deleteUser')->name('admin_user.delete_user');
    Route::post('/change_status/{id}', 'AccountController@changeUserStatus')->name('admin_user.change_status');
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    //角色管理
    Route::get('/role_list', 'RoleController@roleList')->name('auth.role_list');
    Route::delete('/delete_role/{id}', 'RoleController@deleteRole')->name('auth.delete_role');
    Route::get('/role_edit', 'RoleController@roleEdit')->name('auth.role_edit');
    Route::post('/role_save', 'RoleController@save')->name('auth.role_save');
    Route::get('/role_permissions/{id}', 'RoleController@rolePermissions')->name('auth.role_permissions');
    Route::put('/save_role_permissions', 'RoleController@saveRolePermissions')->name('auth.save_role_permissions');

    //菜单管理
    Route::get('/permissions_list', 'PermissionController@PermissionList')->name('auth.permissions_list');
    Route::delete('/delete_permissions/{id}', 'PermissionController@deletePermission')->name('auth.delete_permissions');
    Route::get('/permissions_edit', 'PermissionController@permissionEdit')->name('auth.permissions_edit');
    Route::put('/permissions_save', 'PermissionController@save')->name('auth.permissions_save');
    Route::get('/permissions_role', 'PermissionController@permissionsRole')->name('auth.permissions_role');
    Route::put('/permissions_role_save', 'PermissionController@permissionsRoleSave')->name('auth.permissions_role_save');

    // 部门管理
    Route::get('group/list', 'GroupController@index')->name('group.auth.list');
    Route::post('/group_add', 'GroupController@addSave')->name('group.auth.add_save');
    Route::post('/group_edit', 'GroupController@editSave')->name('group.auth.edit_save');

    // 岗位分类
    Route::get('job_class/list', 'GroupController@jobIndex')->name('jobs.auth.list');
    Route::post('job_class/add', 'GroupController@addJobSave')->name('jobs.auth.add_save');


});

// 授权管理
Route::group(['prefix' => 'empower', 'namespace' => 'Auth'], function () {
    // 数据共享
    Route::get('comman/list', 'EmpowerController@index')->name('empower.auth.list');

    // 批量授予权限
    Route::post('account/transfer', 'EmpowerController@transferAccount')->name('empower.auth.transfer');
    // 取消授权
    Route::delete('del_account/{id}', 'EmpowerController@deleteAccount')->name('empower.auth.delete');

});

// 用户信息
Route::group(['prefix' => 'info_user', 'namespace' => 'AdminUser'], function () {
    // 修改密码
    Route::post('passwd/edit', 'UserInfoController@editPasswdSave')->name('info_user.passwd.edit');
    // 人员分配
    Route::post('staff/edit', 'UserInfoController@editStaffSave')->name('info_user.staff.edit');
    // 数据公用
    Route::post('shared/save', 'UserInfoController@sharedSave')->name('info_user.shared.save');
    // 切换到子账户
    Route::get('switch/into/{id}', 'UserInfoController@switchSubAccount')->name('user.switch.sub_id');
    // 回到主账户中去
    Route::get('logout', 'UserInfoController@logoutSubAccount')->name('user.logout.home');

    Route::get('switch/login/{id}', 'UserInfoController@switchLogin')->name('user.switch.login');

});

// 微信管理
Route::group(['prefix' => 'vv', 'namespace' => 'Wechat'], function () {
    // 公众号列表
    Route::get('account/list', 'AccountController@index')->name('wechat.account.list');
    // 年审认证
    Route::get('account/wait_verify', 'AccountController@waitVerifyIndex')->name('wechat.verify.wait');
    Route::get('account/begin_verify', 'AccountController@beginVerifyIndex')->name('wechat.verify.begin');
    Route::get('account/complete_verify', 'AccountController@completeVerifyIndex')->name('wechat.verify.complete');

    Route::post('account/edit_verify', 'AccountController@editVerifyUpdate')->name('wechat.verify.update');


    // 微信个人号列表
    Route::get('personal/list', 'AccountController@wechatPersonInfo')->name('wechat.person.list');
    // 个人号旗下关联的授权号
    Route::get('personal/empower_list', 'AccountController@personWechatDetail')->name('person.wechat.detail');

    // 切换公众号
    Route::get('account/switch/{id}', 'AccountController@switchAccount')->name('wechat.account.switch');
    // 生成公众号授权二维码
    Route::get('account/add', 'EmpowerController@getAuthQrcodeUrl')->name('wechat.empower.add');
    // 公众号分配
    Route::post('account/allocate', 'EmpowerController@accountAmend')->name('wechat.account.allocate');
    // 公众号批量分配
    Route::post('account/transfer', 'EmpowerController@transferAccount')->name('wechat.account.transfer');
    // 公众号资料修改
    Route::post('account/edit_detail', 'AccountController@editAccount')->name('wechat.detail.edit');
    // 微信个人号添加
    Route::post('personal/add', 'AccountController@addPersonAccount')->name('wechat.person.add');
    Route::post('personal/amend', 'AccountController@amendPersonAccount')->name('wechat.person.amend');
    Route::post('personal/add_tag', 'AccountController@addPersonTag')->name('tag.person.add');
    Route::delete('personal/delete/{id}', 'AccountController@deletePersonAccount')->name('wechat.person.delete');
    // 公众号标签
    Route::post('account/signin', 'AccountController@editAdvertInfo')->name('wechat.advert.info');
    // 公众号认证日期
    Route::post('account/verifydate', 'AccountController@verifyDateSave')->name('wechat.date.verify');
});

// 群发管理
Route::group(['prefix' => 'group_send', 'namespace' => 'Wechat', 'middleware' => CheckUseWechatId::class], function () {
    /**
     * 高级群发消息 list 列表 edit 编辑 save 保存 test-send 测试
     * 客服消息
     * 模版消息
     */
    Route::group(['prefix' => 'list' ], function () {
        Route::get('imagetext', 'GroupSentController@imageTextList')->name('wechat.image_text.list');
        Route::get('custommsg', 'GroupSentController@customMsgList')->name('wechat.custom_msg.list');
        Route::get('templatemsg', 'GroupSentController@templateMsgList')->name('wechat.template_msg.list');
        Route::delete('/del_notice/{id}', 'GroupSentController@deleteNotice')->name('wechat.notice.del');

    });

    Route::group(['prefix' => 'edit'], function () {
        Route::get('imagetext', 'GroupSentController@imageTextEdit')->name('wechat.image_text.edit');
        Route::get('custommsg', 'GroupSentController@customMsgEdit')->name('wechat.custom_msg.edit');
        Route::get('templatemsg', 'GroupSentController@templateMsgEdit')->name('wechat.template_msg.edit');
    });

    Route::group(['prefix' => 'add'], function () {
        Route::get('imagetext', 'GroupSentController@imageTextAdd')->name('wechat.image_text.add');
        Route::get('custommsg', 'GroupSentController@customMsgAdd')->name('wechat.custom_msg.add');
        Route::get('templatemsg', 'GroupSentController@templateMsgAdd')->name('wechat.template_msg.add');
    });

    Route::group(['prefix' => 'save'], function () {
        Route::post('imagetext', 'GroupSentController@imageTextSaveTask')->name('wechat.image_text.save');
        Route::post('custommsg', 'GroupSentController@customMsgSaveTask')->name('wechat.custom_msg.save');
        Route::post('templatemsg', 'GroupSentController@templateMsgSaveTask')->name('wechat.template_msg.save');
    });

    Route::group(['prefix' => 'test'], function () {
        Route::post('imagetext', 'GroupSentController@imageTextTestSend')->name('wechat.image_text.test_send');
        Route::post('custommsg', 'GroupSentController@customMsgTestSend')->name('wechat.custom_msg.test_send');
        Route::post('templatemsg', 'GroupSentController@templateMsgTestSend')->name('wechat.template_msg.test_send');

    });
    // 预计送达人数
    Route::post('kf/api', 'GroupSentController@planSendNum')->name('wechat.kf.get_send_num');

});

// 粉丝管理
Route::group(['prefix' => 'fens', 'namespace' => 'Wechat'], function () {
    // 高级群发消息
    Route::get('list', 'FensAdminController@index')->name('wechat.fens.list');
    Route::get('msg/list', 'FensAdminController@msgList')->name('wechat.fens.msg');

});
// 素材中心
Route::group(['prefix' => 'material', 'namespace' => 'Wechat\Material'], function () {
    // 微信公共素材列表
    Route::get('list', 'BatchgetMaterialController@index')->name('wechat.material.list');
    Route::post('media/add_name', 'BatchgetMaterialController@addMediaName')->name('wechat.material.add_name');
    // 自定义菜单栏
    Route::get('menu/custom', 'BatchgetMaterialController@menuCustom')->middleware(CheckUseWechatId::class)->name('wechat.menu.custom');
    // 获取自定义菜单
    Route::get('menu/get_custom', 'BatchgetMaterialController@getWechatMenu')->name('wechat.menu.get_custom');
    // 菜单加载失败后的懒加载
    Route::get('lazy/get_version', 'BatchgetMaterialController@lazyReadMenus')->name('wechat.lazy.menu');
    // 保存新版本并发布菜单
    Route::post('menu/custom/save', 'BatchgetMaterialController@saveWechatMenu')->name('wechat.menu.save_custom');

    Route::group(['prefix' => 'enter'], function () {
        // 录入标题
        Route::get('title/list', 'EnterMaterialController@titleIndex')->name('wechat.enter.title');
        // 录入小说
        Route::get('novel/list', 'EnterMaterialController@novelIndex')->middleware(CheckUseWechatId::class)->name('wechat.enter.novel');

        // 新增素材标题
        Route::post('title/add', 'AuditController@addTitleSave')->name('wechat.title.add');
        Route::post('novel/add', 'AuditController@addNovelSave')->name('wechat.novel.add');
        Route::post('chapter/add', 'AuditController@addChapterSave')->name('wechat.chapter.add');
        Route::post('link/add', 'AuditController@addLinkSave')->name('wechat.link.add');

        Route::post('link/edit', 'AuditController@editLinkSave')->name('wechat.link.edit');
        // 更新或创建链接
        Route::post('link/update_insert', 'AuditController@updateOrInsertLink')->name('wechat.msgtype.update');

        // 读取当前可录入的小说类型
        Route::get('novel/get_type', 'EnterMaterialController@lazyNovelType')->name('wechat.lazy.novel_type');
    });
    // 录入链接
    Route::group(['prefix' => 'link', 'middleware' => CheckUseWechatId::class],function (){
        // 活动
        Route::get('/active', 'EnterMaterialController@linkActiveIndex')->name('wechat.link.active');
        // 签到
        Route::get('/signin', 'EnterMaterialController@linkSignIndex')->name('wechat.link.sign');
        // 继续阅读
        Route::get('/history', 'EnterMaterialController@linkHistoryIndex')->name('wechat.link.history');
    });

    // 审核中心
    Route::group(['prefix' => 'audit'], function () {
        // 小说
        Route::get('title/novel', 'AuditController@titleNovelIndex')->name('title.audit.novel');
        // 活动
        Route::get('title/active', 'AuditController@titleActiveIndex')->name('title.audit.active');
        // 签到
        Route::get('title/signin', 'AuditController@titleSignIndex')->name('title.audit.signin');
        // 继续阅读
        Route::get('title/history', 'AuditController@titleHistoryIndex')->name('title.audit.history');

        // 小说
        Route::get('link/novel', 'AuditController@linkNovelIndex')->name('wechat.audit.novel');
        // 活动
        Route::get('link/active', 'AuditController@linkActiveIndex')->name('wechat.audit.active');
        // 签到
        Route::get('link/signin', 'AuditController@linkSignIndex')->name('wechat.audit.signin');
        // 继续阅读
        Route::get('link/history', 'AuditController@linkHistoryIndex')->name('wechat.audit.history');

        // 更新标题审核结果
        Route::post('title/update', 'AuditController@updateTitleSave')->name('wechat.title.update');
        // 更新链接审核结果
        Route::post('link/update', 'AuditController@updateLinkSave')->name('wechat.link.update');
    });

});
// 小说平台
Route::group(['prefix' => 'account', 'namespace' => 'Account'], function () {
    // 小说平台账户配置列表
    Route::get('/novel_configs', 'NovelController@index')->name('account.novel.list');
    // 小说vip账号列表
    Route::get('/manage/config', 'NovelController@vipList')->name('vip.novel.list');
    // 小说vip账户明细
    Route::get('/vip/manage', 'NovelController@manageNovel')->name('vip.novel.manage');
    // 子账户管理配置
    Route::get('/sub_novel/config', 'NovelController@subNovelConfig')->name('sub.novel.config');
    // 添加
    Route::post('/add_novel', 'NovelController@addAccount')->name('account.add_novel');
    // 修改
    Route::post('config/amend', 'NovelController@amdAccount')->name('account.config.amend');
    // 小说账号删除
    Route::delete('config/del/{id}', 'NovelController@deleteAccount')->name('account.config.del');
    // vip管理账户添加
    Route::post('mange_account/add', 'NovelController@addMangeAccount')->name('vip.config.add');
    // VIP关联子账户
    Route::post('manage/related', 'NovelController@relatedAccount')->name('vip.account.related');
    // VIP账户密码修改
    Route::post('vip_account/amend', 'NovelController@editAccountPasswd')->name('vip.passwd.amend');

});

// 数据分析
Route::group(['prefix' => 'datas', 'namespace' => 'Analyze'], function () {
    // 推送数据
    Route::get('list', 'WechatNotifyController@index')->name('wechat.notice.list');
    // 推送总数据
    Route::get('notices/list', 'WechatNotifyController@noticeTotal')->name('wechat.notice.total');
    // 每月推送
    Route::get('month_notic/list', 'WechatNotifyController@monthNotice')->name('wechat.notice.month');
    // 每日推送
    Route::get('day_notic/list', 'WechatNotifyController@dayNotice')->name('wechat.notice.day');
    // 推广收入
    Route::get('pay_order/list', 'PayOrderController@index')->name('wechat.pay_order.list');
    // 每月收入
    Route::get('month_order/pay_list', 'PayOrderController@monthPayOrder')->name('wechat.month_order.pay_list');
    // 每日收入
    Route::get('day_order/pay_list', 'PayOrderController@dayPayOrder')->name('wechat.day_order.pay_list');
    // 用户分析
    Route::get('user_total/list', 'PayOrderController@userInfoTotal')->name('wechat.user.total');
    // 每月公众号进粉数
    Route::get('month_user/list', 'PayOrderController@monthUserInfo')->name('wechat.month_user.total');
    // 每日公众号进粉数
    Route::get('day_user/list', 'PayOrderController@dayUserInfo')->name('wechat.day_user.total');
    // 活跃用户分析
    Route::get('user_active/list', 'PayOrderController@userInfoActive')->name('wechat.user.active');
    // 每月活跃用户数据
    Route::get('month_active/list', 'PayOrderController@monthUserActive')->name('wechat.month_user.active');
    // 每日活跃用户数据
    Route::get('day_active/list', 'PayOrderController@dayUserActive')->name('wechat.day_user.active');

});

/**
 * 群发模块
 */
Route::group(['prefix' => 'group_send', 'namespace' => 'Wechat'], function () {
    Route::post('/custommsg/api', 'GroupSentController@sendForAction');

});
