<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use App\Repositories\CacheKey\CacheKeyConstant;
use EasyWeChat\Factory;
use EasyWeChat\OpenPlatform\Server\Guard;
use Illuminate\Http\Request;
use App\Models\Wechat\WechatEmpowerInfo as Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Illuminate\Support\Facades\Redis;

class OpenWeixinController extends Controller
{
    protected $openPlatform = null;

    public function __construct()
    {
        $this->openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
        $cache = new RedisAdapter(app('redis')->connection()->client());
        $this->openPlatform->rebind('cache', $cache);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
//        $encryptMsg = file_get_contents('php://input');
//        file_put_contents('info.txt',print_r($encryptMsg . PHP_EOL,true),FILE_APPEND);
        $this->request = $request;

        $server = $this->openPlatform->server;

        // 处理授权成功事件
        $server->push(function ($message) {
            Log::info('Handle authorization success events.' . $message['AuthorizerAppid']);
            DB::connection('admin')->table('wechat_empower_info')->where('auth_appid', $message['AuthorizerAppid'])->update(['is_power' => 1]);

        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            Log::info('Handling authorization update events.' . $message['AuthorizerAppid']);
            DB::connection('admin')->table('wechat_empower_info')->where('auth_appid', $message['AuthorizerAppid'])->update(['is_power' => 1]);

        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) {
            Log::info('Handling authorization cancellation events.' . $message['AuthorizerAppid']);
            DB::connection('admin')->table('wechat_empower_info')->where('auth_appid', $message['AuthorizerAppid'])->update(['is_power' => 0]);

        }, Guard::EVENT_UNAUTHORIZED);

        // VerifyTicket component_verify_ticket协议推送
        $server->push(function ($message) {

            Log::info('VerifyTicket component_verify_ticket request arrived.');
            $data = [
                "appid" => $message['AppId'],
                "ticket" => $message['ComponentVerifyTicket']
            ];
            DB::connection('admin')->table('wechat_ticket')->updateOrInsert(["appid" => $message['AppId']],$data);

            // ...
        }, Guard::EVENT_COMPONENT_VERIFY_TICKET);

        return $server->serve();

    }
    // 完成授权后回调
    public function userAuthCallback(Request $request)
    {
        $info = CacheKeyConstant::WECHAT_INFO; // 公众号的auth相关

        $authorizationCode = $request->input('auth_code');
        $user_id = $request->input('uid');
        if (empty($authorizationCode))
            abort('404');

        $res = $this->openPlatform->handleAuthorize($authorizationCode);

        $appid = $res['authorization_info']['authorizer_appid'];
        //获取授权方的帐号基本信息
        $data = $this->openPlatform->getAuthorizer($appid);

        $info1 = $data['authorizer_info'];
        $info2 = $data['authorization_info'];

        Redis::hDel($info, $appid);

        $account = Account::where('auth_appid', $appid)->first();
        if ($account) {
            $account->authorizer_refresh_token = $info2['authorizer_refresh_token'];
            $account->save();
            return redirect()->route('wechat.account.list');
        }

        $info_data = [
            'auth_appid' => $appid,

            'nick_name' => $info1['nick_name'],
            'head_img' => $info1['head_img'],
            'service_type_info' => $info1['service_type_info']['id'],
            'verify_type_info' => $info1['verify_type_info']['id'],
            'original' => $info1['user_name'], // 原始id
            'principal_name' => $info1['principal_name'],
            'alias' => $info1['alias'] ?? null,
            'qrcode_url' => $info1['qrcode_url'], //二维码
            'user_id' => $user_id,
            'authorizer_refresh_token' => $info2['authorizer_refresh_token'],
            'func_info' => json_encode($info2['func_info'])
        ];

        Account::query()->insert($info_data);
        return redirect()->route('wechat.account.list');


    }

    /**
     * 处理微信用户发过来的消息
     *
     * @return string
     */
    public function msgNotify($appid = null)
    {
        try{
            $key = CacheKeyConstant::WECHAT_ACTIVE_FANS;
            $info = CacheKeyConstant::WECHAT_INFO;

            $is_ture = Redis::HEXISTS($info, $appid);

            if (!$is_ture){
                $data = Account::query()->where('auth_appid', $appid)->select('auth_appid','authorizer_refresh_token as token', 'id')->first();
                $wid = $data->id;
                $token = $data->token;
                Redis::HMSET($info, ["{$appid}" => $token . ':' . $wid]);
            }else {
                $item = Redis::	HGET($info, $appid);
                list($token, $wid) = explode(':', $item);
            }
            $officialAccount = $this->openPlatform->officialAccount($appid, $token);

            $officialAccount->server->push(function($message) use($wid, $key) {
                Redis::HMSET($key, ["{$message['FromUserName']}" => $message['CreateTime'].':'. $wid]);

                switch ($message['MsgType']) {
                    case 'event':
                        //接收事件推送:
                        switch ($message['Event']) {
                            case 'subscribe':  //关注事件, 扫描带参数二维码事件(用户未关注时，进行关注后的事件推送)
//                            return $message['FromUserName'];
                                break;
                            case 'unsubscribe':  //取消关注事件
                                Log::info('取消关注 '.$message['FromUserName']);
                                Redis::hDel($key, $message['FromUserName']);
                                DB::connection('admin')->table('wechat_user_info')->where('openid', $message['FromUserName'])->update(['subscribe' => 0]);
                                break;
                            case 'SCAN':  //扫描带参数二维码事件(用户已关注时的事件推送)
//                            return "欢迎关注 HTTP://WWW.AppBlog.CN！";
                                break;
                            case 'LOCATION':  //上报地理位置事件
//                            return "经度: " . $message['Longitude'] . "\n纬度: " . $message['Latitude'] . "\n精度: " . $message['Precision'];
                                break;
                            case 'CLICK':  //自定义菜单事件(点击菜单拉取消息时的事件推送)
//                            return "事件KEY值: " . $message['EventKey'];
                                break;
                            case 'VIEW':  //自定义菜单事件(点击菜单拉取消息时的事件推送)
//                            return "跳转URL: " . $message['EventKey'];
                                break;
                            case 'ShakearoundUserShake':
                                Log::info('摇一摇周边事件');
                                //摇一摇事件通知: https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443448066
//                            return 'ChosenBeacon\n' . 'Uuid: ' . $message['ChosenBeacon']['Uuid'] . 'Major: ' . $message['ChosenBeacon']['Major'] . 'Minor: ' . $message['ChosenBeacon']['Minor'] . 'Distance: ' . $message['ChosenBeacon']['Distance'];
                                break;
                            default:
//                            return $message['Event'];
                                break;
                        }
                        break;
                    //接收普通消息:
                    case 'text':
                        Log::info('收到文字消息');
//                    return "Content: " . $message['FromUserName'] . $message['CreateTime'].$wid.$appid;
                        break;
                    case 'image':
                        Log::info('收到图片消息');
//                    return "MediaId: " . $message['MediaId'] . "\nPicUrl: " . $message['PicUrl'];
                        break;
                    case 'voice':
                        Log::info('收到语音消息');
//                    return "MediaId: " . $message['MediaId'] . "\nFormat: " . $message['Format'] . "\nRecognition: " . $message['Recognition'];
                        break;
                    case 'video':
                        Log::info('收到视频消息');
//                    return "MediaId: " . $message['MediaId'] . "\nThumbMediaId: " . $message['ThumbMediaId'];
                        break;
                    case 'shortvideo':
                        Log::info('收到小视频消息');
//                    return "MediaId: " . $message['MediaId'] . "\nThumbMediaId: " . $message['ThumbMediaId'];
                        break;
                    case 'location':
//                    return "Location_X: " . $message['Location_X'] . "\nLocation_Y: " . $message['Location_Y'] . "\nScale: " . $message['Scale'] . "\nLabel: " . $message['Label'];
                        Log::info('收到坐标消息');
                        break;
                    case 'link':
                        Log::info('收到链接消息');
//                    return "Title: " . $message['Title'] . "\nDescription: " . $message['Description'] . "\nUrl: " . $message['Url'];
                        break;
                    default:
                        Log::info('收到其它消息');
//                    return $message['MsgType'];
                        break;
                }
            });

            $response = $officialAccount->server->serve();

            $response->send();

            return $response;

        }catch (\Exception $e){

            Log::info('消息异常原因：'. print_r($e) . '传入的参数：' . print_r(request()->all()));


        }

    }
}
