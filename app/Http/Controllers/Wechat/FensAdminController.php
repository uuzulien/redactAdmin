<?php


namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\Models\Wechat\WechatUserInfo;
use Illuminate\Http\Request;

class FensAdminController extends Controller
{
    public function index(Request $request,WechatUserInfo $wechatUserInfo)
    {
        $param = $request->all();

        $data = $wechatUserInfo->getFensList($param);
        return view('fensAdmin.list', $data);
    }

    public function msgList()
    {
        $data = ['list' => []];
        return view('fensAdmin.msg',$data);
    }
}