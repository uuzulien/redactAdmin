<?php

namespace App\Http\Controllers\SeekData\Api;

use App\Models\Novel\AccountManage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    private $targetTable = [
        'grab_books_page' => 'novel_books_sign',
        'grab_orders_api' => 'wechat_fens_detail',
        'grab_orders_page' => 'novel_orders_detail',
        'grab_books_api' => 'grab_books_api',
    ];
    /**
     * 获取账号配置相关数据
     * @param Request $request
     * @return mixed\
     */
    public function getAccountConfigs(Request $request)
    {
        $user_id = $request->input('user_id');
        $pid = $request->input('pid');
        $query = new AccountManage();
        $data = $query->when($user_id, function ($q) use($user_id) {
            $q->where(['user_id' => $user_id]);
        })->when($pid,function ($q) use($pid){
            $q->where('pid', $pid);
        })->get(['id','pid','platform_nick','account','password'])
            ->toArray();

        return success($data);
    }

    public function getPayTrendData(Request $request)
    {
        $list = DB::connection('admin')->table('pay_trend as p')->leftJoin('account_config as c', function ($join){
            $join->on('p.wid','=','c.id');
        })->where('p.pid',1)->select(['p.trend_id','p.cost_time','c.account','c.password','p.wid'])->get()->groupBy('wid')->toArray();

        return success($list);
    }

    // 获取所有的keys,跳过重复的key
    public function getKeyExist(Request $request)
    {
        $action = $request->input('action');
        $wid = $request->input('wid');

        switch ($action) {
            case 'get_book_page':
                $data = DB::connection('public')->table($this->targetTable['grab_books_page'])->pluck('book_id')->toArray();
                break;
            case 'get_order_page':
                $data = DB::connection('public')->table($this->targetTable['grab_orders_page'])->where('book_id','<>',0)->pluck('order_num')->toArray();
                break;
            // 平台微信粉丝
            case 'get_fens_api':
                $data = DB::connection('public')->table($this->targetTable['grab_orders_api'])->when($wid, function ($q) use($wid){
                    $q->where('wid',$wid)->whereNull('openid');
                })->pluck('book_user_id')->toArray();
                break;
            case 'get_fens_page':
                $data = DB::connection('public')->table($this->targetTable['grab_orders_api'])->when($wid, function ($q) use($wid){
                    $q->where('wid',$wid);
                })->pluck('book_user_id')->toArray();
                break;
            case 'get_book_api':
                $data = DB::connection('public')->table($this->targetTable['grab_books_api'])->pluck('book_id')->toArray();
                break;
            case 'get_user_id':
                $data = DB::connection('public')->table($this->targetTable['grab_orders_page'])->pluck('book_user_id')->toArray();
                break;
            case 'get_ywbook':
                $data = DB::connection('admin')->table('yuewen_book')->pluck('book_id')->toArray();
                break;
            case 'get_channel_id':
                $data = DB::connection('admin')->table('yuewen_order')->get()->map(function ($q){
                    return $item['channel_time'] = $q->channel_id . $q->create_time;
                })->unique()->toArray();
                break;
            default:
                $data =  [];
        }

        return success($data);
    }

    // 获取订单明细链接
    public function getChannelId()
    {
        $data = DB::connection('admin')->table('yuewen_book')->where('is_update',0)->pluck('create_book_url', 'channel_id')->unique()->toArray();

        return success($data);
    }
}
