<?php


namespace App\Repositories\Wechat\Base;

class HandleRequest extends Core
{
    /**
     * 获取公众号全部的openid 或者是 total
     * @param bool $get_total
     * @return array|int|mixed
     */
    public function getWechatUserOpenid(bool $get_total = false)
    {
        $list = [];
        if (is_null($this->officialAccount))
            return $list;

        // 获取用户列表：
        while ($this->nextOpenid !== ''){
            $data = $this->officialAccount->user->list($this->nextOpenid);
            if ($get_total)
                return $data['total'] ?? 0;
            $list[] = $data['data']['openid'] ?? null;
            $this->nextOpenid = $data['next_openid'] ?? '';
        }
        $this->nextOpenid = null;

        return collect($list)->flatten()->filter()->toArray();
    }

    /**
     * 获取公众号下的用户基本信息
     * @param array $data
     * @return |null
     */
    public function getWechatUserInfo(array $data)
    {
        return $this->officialAccount->user->select($data)['user_info_list']  ?? null;
    }

    /**
     * 获取单个用户的基本信息
     * @param int $openid
     * @return mixed
     */
    public function getSingleWecahtUser(string $openid)
    {
        return $this->officialAccount->user->get($openid);
    }


    // 发送客服消息
    public function sendCustomerService($text, $openid)
    {
        return $this->officialAccount->customer_service->message($text)->to($openid)->send();
    }
    // 发送高级群发消息
    public function sendImgtextService($mediaId, $openid,$type='NewsItem')
    {
        switch ($type){
            case 'NewsItem':
                return $this->officialAccount->broadcasting->previewNews($mediaId, $openid);
            default:
                break;
        }
    }

    // 上传图片
    public function uploadImage($path, $base='/www/wwwroot/redactAdmin/storage/app/public/')
    {
        return $this->officialAccount->material->uploadImage($base . $path);
    }
    // 上传缩略图片
    public function uploadThumb($path)
    {
        return $this->officialAccount->material->uploadThumb($path);
    }
    // 上传图文消息
    public function uploadArticle($article)
    {
        return $this->officialAccount->material->uploadArticle($article);
    }

    // 获取公众号下的素材
    public function getWechatMaterialList($type = 'news')
    {
        return $this->officialAccount->material->list($type)['item'] ?? [];
    }
    // 获取公众号菜单
    public function deletedMenu($menuId)
    {
        return $this->officialAccount->menu->delete($menuId);
    }
    // 获取公众号菜单
    public function getWecahtCustomInfo()
    {
        return $this->officialAccount->menu->current();
    }
    // 读取（查询）已设置菜单
    public function getWecahtCustomList()
    {
        return $this->officialAccount->menu->list();
    }

    // 获取公众号菜单
    public function updateWecahtCustomInfo($data)
    {
        return $this->officialAccount->menu->create($data);
    }

    public function getUserSummary($start_time, $end_time)
    {
        try{
            $data = $this->officialAccount->data_cube->userSummary($start_time, $end_time)['list'] ?? [];
        }catch (\Exception $e){
            $data = [];
        }
        return $data;
    }

    public function getUserCumulate($start_time, $end_time)
    {
        try{
            $data = $this->officialAccount->data_cube->userCumulate($start_time, $end_time)['list'] ?? [];
        }catch (\Exception $e){
            $data = [];
        }
        return $data;
    }
}
