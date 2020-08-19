<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wechat\WechatEmpowerInfo;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class EmpowerController extends Controller
{
    // 获取授权二维码的url地址
    public function getAuthQrcodeUrl()
    {
        try{
            $openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
            $cache = new RedisAdapter(app('redis')->connection()->client());
            $openPlatform->rebind('cache', $cache);
            // 获取跳转的链接
            $redirect_uri = 'http://redact.5dan.com/wechat_notify?uid=' . Auth::id();
            $request_url = $openPlatform->getPreAuthorizationUrl($redirect_uri);
            return redirect($request_url);

        }catch(\Exception $e){
            flash_message($e->getMessage(),false);

            return redirect()->back();
        }
    }

    // 公众号归属
    public function accountAmend(Request $request)
    {
        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $query = WechatEmpowerInfo::query()->find($id);
        $platform_nick = $query->nick_name;
        $query->user_id = $user_id;
        $query->save();

        $status = DB::connection('admin')->table('account_config')->where('platform_nick', $platform_nick)->update(['user_id' => $user_id]);

        if ($status){
            flash_message('公众号以及平台账户转移成功');
        }else {
            flash_message('公众号转移成功，无对应平台账户可转移！');
        }
        return redirect()->back();
    }

    // 公众号批量转移
    public function transferAccount(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']) || empty($data['user_id'])){
                abort('非法请求！');
            }

            if ($data['wxv'] == []) {
                $validatorError = ['name' => '请先选择公众号'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $user_id = $data['user_id'];

            foreach ($data['wxv'] as $value){
                $query = WechatEmpowerInfo::query()->find($value);
                $query->user_id = $user_id;
                $query->save();
            }

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect('account/novel_configs')
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }
}
