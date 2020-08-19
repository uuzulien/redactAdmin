<?php

namespace App\Http\ViewComposers;

use App\Models\AdminUsers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\Auth\UserPermission;
use App\Models\Wechat\WechatEmpowerInfo;

class LayoutComposer
{

    protected $adminService;
    protected $request;

    public function __construct( UserPermission $userPermission,Request $request)
    {
        $this->adminService = $userPermission;
        $this->request = $request;
    }

    /**
     * 绑定数据给view
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        $wechat = WechatEmpowerInfo::query()->find($user->last_use_wechat_id);

        $queryWechat = WechatEmpowerInfo::query()->group()->select(['id','nick_name','head_img','is_power'])->get();

        $wechatInfoList = $queryWechat->toArray();

        if (Auth::check()) { //判断用户是否登录
            //从UAMS获得菜单信息
            $menu=$this->adminService->userMenu($user);
            //附加管理员信息
            $view->with('user', $user);
            //附加菜单信息
            $view->with('menu', $menu);
            $view->with('headimg', $wechat->head_img ?? null);
            $view->with('nick', $wechat->nick_name ?? null);
            $view->with('wechatInfoList', $wechatInfoList);
        }
    }
}
