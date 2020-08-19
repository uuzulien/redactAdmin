<?php

namespace App\Http\Controllers\SeekData\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreDataController extends Controller
{

    /**
     * 数据存储中心，负责存数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function center(Request $request)
    {
        $contents = json_decode($request->getContent(),true);

        $datas = $contents['datas'];
        $action = $contents['action'];
        $error = null;

        try{
            $this->novelDataSave($action, $datas); // 通用的小说储存
        }catch (\Exception $e) {
            $error = $e;
        }

        if ($error)
            return response()->json(['message' => 'error', 'data' => $datas[0], 'case' => $e,'wid' => $wid]);
        return success();

    }

    public function novelDataSave($action, $datas)
    {
        date_default_timezone_set("Asia/Shanghai");

        switch ($action) {
            // 更新登录状态
            case 'account_config':
                $data = $datas[0];
                DB::connection('admin')->table('account_config')->updateOrInsert(['id' => $data['id']], $data);
                break;
            // 阅文平台的
            case 'yuewen_waitui':
                DB::connection('admin')->table('yuewen_book')->insert($datas);
                break;
            case 'insert_fens_api':
                DB::connection('public')->table('wechat_fens_detail')->insert($datas);
                break;
            case 'update_fens_api':
                foreach ($datas as $item){
                    DB::connection('public')->table('wechat_fens_detail')->where(['book_user_id' => $item['book_user_id']])->update(['openid'=> $item['openid'],'regtime' => $item['regtime']]);
                }
                break;
            case 'pay_order':
                foreach ($datas as $item){
                    DB::connection('public')->table('pay_income')->updateOrInsert(['order_time' => $item['order_time'], 'wid' => $item['wid']],$item);
                }
                break;
            case 'yw_pay_order':
                $first =  DB::connection('admin')->table('account_config')->where('yw_id', $datas[0]['yw_id'])->first();
                if ($first){
                    foreach ($datas as $item){
                        unset($item['yw_id']);
                        DB::connection('public')->table('pay_income')->updateOrInsert(['order_time' => $item['order_time'], 'wid' => $first->id], $item);
                    }
                }
                break;
            default:
                break;
        }
    }

}
