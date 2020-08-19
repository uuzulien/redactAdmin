<?php


namespace App\Http\Controllers\Wechat\Material;


use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Models\Wechat\BatchMaterialInfo;
use Illuminate\Support\Facades\DB;
use App\Repositories\Wechat\Func\WechatFunBase;


class BatchgetMaterialController extends Controller
{
    public function index(Request $request,BatchMaterialInfo $batchMaterialInfo)
    {
        $param = $request->all();

        $data = $batchMaterialInfo->getMaterialInfo($param);

        return view('wechat.material.list', $data);
    }
    // 添加素材别名
    public function addMediaName(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['media_name'] == "") {
                $validatorError = ['name' => '请输入本素材的别名'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $media_id = $data['media_id'];
            $name = $data['media_name'];

            DB::connection('admin')->table('wechat_media_id')->updateOrInsert(['media_id' => $media_id] , [ 'name' => $name]);

            flash_message('操作成功');
            return redirect()->back();

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();

        }
    }
    // 自定义菜单
    public function menuCustom(Request $request)
    {
        $wid = $request->input('wid');

        $originalMenu = DB::connection('admin')->table('wechat_custom_info')->where(['wid' => $wid, 'is_show' => 1])->first()->datas ?? null;

        return view('wechat.menu.custom', compact('originalMenu'));
    }

    public function lazyReadMenus(Request $request)
    {
//        $openPlatform = Factory::openPlatform(config('wechat.open_platform.default'));
////        $officialAccount = $openPlatform->officialAccount('wxa50b60e69b18626b', 'refreshtoken@@@GWo79oT4Z9-_9tAAm0tZNNEcqYKevwmoIuinAjHIOOk');
//        $officialAccount = $openPlatform->officialAccount('wx319489bf57a241dd', 'refreshtoken@@@cuO0Kb-Bw0hOzcdr7DcD5Ncy-WLHuNvNL6_hUjIWVXg');
//
//        $accessToken = $officialAccount->access_token;
//        $newAccessToken =  $accessToken->getToken(true);
//        $officialAccount['access_token']->setToken($newAccessToken['authorizer_access_token'], 7200);
//        dd($accessToken);
//        dd($officialAccount->menu->current());

        return ['title'=>"未定义",'keyword'=>null,'url'=>null,'is_show'=>1,'wxid'=>1,'type'=>null];
    }

    // 获取当前的自定义菜单
    public function getWechatMenu()
    {
        $data =(new WechatFunBase())->getCurrentWechatInfo();

        return response()->json(['error_code' => 0, 'menus' => $data], 200);
    }
    // 保存并发布自定义菜单
    public function saveWechatMenu(Request $request)
    {
        $param = $request->all();
        $menus = $param['menu'] ? json_decode($param['menu']) : [];

        $data =(new WechatFunBase())->updateWechatInfo($menus);

        return response()->json(['error_code' => $data['errcode'], 'msg' => '发布失败，错误代码：'. $data['errcode']], 200);
    }
}
