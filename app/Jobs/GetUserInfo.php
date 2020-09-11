<?php

namespace App\Jobs;

use App\Repositories\Wechat\Func\WechatFunBase;
use EasyWeChat\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class GetUserInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    public $tries = 2;

    protected $val;

    protected $appid;

    protected $wid;

    protected $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appid, $value)
    {
        $wechatInfo = DB::connection('admin')->table('wechat_empower_info')->where('auth_appid', $appid)->first();
        $this->val = $value;
        $this->appid = $appid;
        $this->token = $wechatInfo->authorizer_refresh_token;
        $this->wid = $wechatInfo->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wechatFunc = new WechatFunBase();

        $user_info_list = $this->getWechatUserInfo($this->val); // 拿到微信公众号用户信息
        if ($user_info_list) {
            $item = $wechatFunc->formatUserInfoList($user_info_list, $this->wid); // 格式化用户信息
            $wechatFunc->insertWechatUserInfo($item); // 插入公众号用户信息
        }
    }

    public function getWechatUserInfo(array $data)
    {
        $openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
        // 创建缓存实例
        $cache = new RedisAdapter(app('redis')->connection()->client());
        $openPlatform->rebind('cache', $cache);

        $officialAccount = $openPlatform->officialAccount($this->appid, $this->token);

        return $officialAccount->user->select($data)['user_info_list']  ?? null;
    }
}
