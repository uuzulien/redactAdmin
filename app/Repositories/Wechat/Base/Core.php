<?php


namespace App\Repositories\Wechat\Base;


use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class Core
{
    protected $officialAccount = null;

    protected $nextOpenid = null;

    protected $appid = null;

    protected $token = null;

    public function make()
    {
        if ($this->officialAccount)
            return $this;

        try{
            $openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
            // 创建缓存实例
            $cache = new RedisAdapter(app('redis')->connection()->client());
            $openPlatform->rebind('cache', $cache);

            $this->officialAccount = $openPlatform->officialAccount($this->appid, $this->token);

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return $this;
    }

    public function initOfficialAccount()
    {
        $this->officialAccount = null;
        return $this;
    }

    public function message()
    {

    }
}