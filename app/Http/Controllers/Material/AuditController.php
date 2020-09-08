<?php


namespace App\Http\Controllers\Material;


use App\Http\Controllers\Controller;
use App\Models\AdminUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function titleNovelIndex(Request $request)
    {
        $status = $request->input('status', 'all');

        $pdr = $request->input('pdr');
        $title = $request->input('title');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('wechat_title_info as a')->leftJoin('wechat_title_type as b', function ($join){
            $join->on('a.type','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($status != 'all', function ($q) use($status){
            $q->where('a.status', $status);
        })->when($title, function ($q) use($title){
            $q->where('a.title','like',"%$title%");
        })->where('b.id', 2)->whereIn('user_id', $userGroup)->whereIn('status',[0,2])->select(['a.*','b.name','c.name as user_name'])->orderBy('status')->orderByDesc('created_at')->paginate(15);

        return view('wechat.audit.title.novel', compact('list', 'groups', 'user_all'));
    }

    public function titleActiveIndex(Request $request)
    {
        $pdr = $request->input('pdr');
        $title = $request->input('title');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('wechat_title_info as a')->leftJoin('wechat_title_type as b', function ($join){
            $join->on('a.type','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($title, function ($q) use($title){
            $q->where('a.title','like',"%$title%");
        })->where('b.id', 1)->whereIn('user_id', $userGroup)->whereIn('status',[0,2])->select(['a.*','b.name','c.name as user_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.title.active', compact('list'));
    }

    public function titleSignIndex(Request $request)
    {
        $pdr = $request->input('pdr');
        $title = $request->input('title');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('wechat_title_info as a')->leftJoin('wechat_title_type as b', function ($join){
            $join->on('a.type','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($title, function ($q) use($title){
            $q->where('a.title','like',"%$title%");
        })->where('b.id', 3)->whereIn('user_id', $userGroup)->whereIn('status',[0,2])->select(['a.*','b.name','c.name as user_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.title.sign', compact('list'));
    }

    public function titleHistoryIndex(Request $request)
    {
        $pdr = $request->input('pdr');
        $title = $request->input('title');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('wechat_title_info as a')->leftJoin('wechat_title_type as b', function ($join){
            $join->on('a.type','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($title, function ($q) use($title){
            $q->where('a.title','like',"%$title%");
        })->where('b.id', 4)->whereIn('user_id', $userGroup)->whereIn('status',[0,2])->select(['a.*','b.name','c.name as user_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.title.history', compact('list'));
    }

    // 通用标题
    public function titleComNovelIndex(Request $request)
    {
        $is_share = $request->input('is_share', 'all');

        $pdr = $request->input('pdr');
        $title = $request->input('title');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('wechat_title_info as a')->leftJoin('wechat_title_type as b', function ($join){
            $join->on('a.type','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($is_share != 'all', function ($q) use($is_share){
            $q->where('a.status', $is_share);
        })->when($title, function ($q) use($title){
            $q->where('a.title','like',"%$title%");
        })->where('b.id', 2)->whereIn('user_id', $userGroup)->where('status', 1)->whereIn('is_share', [0,2])->select(['a.*','b.name','c.name as user_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.common.novel', compact('list', 'groups', 'user_all'));
    }

    // 新增标题素材
    public function addTitleSave(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['title'] == "") {
                $validatorError = ['name' => '请认真添加素材标题'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['type'] == "") {
                $validatorError = ['name' => '请选择添加的所属标签'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            $is_false = DB::connection('admin')->table('wechat_title_info')->where('title', $data['title'])->first();

            if ($is_false) {
                $validatorError = ['name' => "《{$data['title']}》已被添加，请勿重复操作！"];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::connection('admin')->table('wechat_title_info')->insert(['title' => $data['title'], 'type' => $data['type'], 'msg_type' => $data['msg_type'], 'user_id' => Auth::id()]);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 新增平台小说
    public function addNovelSave(Request $request)
    {
        try {
            $param = $request->all();
            $data = $param['data'];

            if (empty($param['_token']))
                abort('非法请求！');

            if ($data['name'] == "") {
                $validatorError = ['name' => '请认真添加小说书名'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $is_false = DB::connection('public')->table('novel_info')->where(['name' => $data['name'], 'pid' => $data['pid']])->first();

            if ($is_false) {
                $validatorError = ['name' => "已被添加，请勿重复操作！"];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            $data['user_id'] = Auth::id();

            DB::connection('public')->table('novel_info')->insert($data);


        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 新增章节
    public function addChapterSave(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['chapter'] == "") {
                $validatorError = ['name' => '请认真添加小说章节名'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            $is_false = DB::connection('public')->table('novel_chapter')->where(['book_id' => $data['book_id'], 'name' => $data['chapter']])->first();

            if ($is_false) {
                $validatorError = ['name' => "已被添加，请勿重复操作！"];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            DB::connection('public')->table('novel_chapter')->insert(['book_id' => $data['book_id'], 'name' => $data['chapter']]);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 新增推送链接
    public function addLinkSave(Request $request)
    {
        try {
            $param = $request->all();
            $data = $param['data'];

            if (empty($param['_token']))
                abort('非法请求！');

            if ($data['href'] == "") {
                $validatorError = ['name' => '请认真添加素材链接'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            $is_false = DB::connection('admin')->table('wechat_link_info')->where(['typeid' => $data['typeid'], 'wid' => $data['wid']])->first();

            if ($is_false) {
                $validatorError = ['name' => "已被添加，请勿重复操作！"];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            $data['user_id'] = Auth::id();

            DB::connection('admin')->table('wechat_link_info')->insert($data);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 更新标题审核状态
    public function updateTitleSave(Request $request)
    {
        date_default_timezone_set("Asia/Shanghai");

        $id = $request->input('id');
        $reason= $request->input('msg', null);
        $status= $request->input('status', null);
        $is_share = $request->input('is_share', null);

        if (!is_null($is_share)){
            $data = ['is_share' => $is_share];
        }else {
            $data = ['status' => $status, 'msg' => $reason, 'audit_time' => date('Y-m-d H:i:s')];
        }
        // 更新单个或者多个状态
        if (is_array($id)){
            $ids = $id;
            foreach ($ids as $id){
                DB::connection('admin')->table('wechat_title_info')->where('id', $id)->update($data);
            }
        }else {
            DB::connection('admin')->table('wechat_title_info')->where('id', $id)->update($data);
        }


        flash_message('操作成功');
        return redirect()->back();
    }

    public function updateLinkSave(Request $request)
    {
        date_default_timezone_set("Asia/Shanghai");

        $id = $request->input('id');
        $reason= $request->input('msg', null);
        $status= $request->input('status');
        if (is_array($id)){
            $ids = $id;
            foreach ($ids as $id){
                DB::connection('admin')->table('wechat_link_info')->where('id', $id)->update(['status' => $status, 'msg' => $reason, 'audit_time' => date('Y-m-d H:i:s')]);
            }
        }else {
            DB::connection('admin')->table('wechat_link_info')->where('id', $id)->update(['status' => $status, 'msg' => $reason, 'audit_time' => date('Y-m-d H:i:s')]);
        }

        flash_message('操作成功');
        return redirect()->back();
    }

    // 继续阅读修改
    public function editLinkSave(Request $request)
    {
        try {
            $param = $request->all();
            $data = $param['data'];

            if (empty($param['_token']))
                abort('非法请求！');

            if ($data['href'] == "") {
                $validatorError = ['name' => '请认真添加素材链接'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::connection('admin')->table('wechat_link_info')->where(['typeid' => $data['typeid'],'wid' => $data['wid']])->update($data);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('修改成功');
        return redirect()->back();
    }

    // 活动
    public function linkActiveIndex(Request $request)
    {
        $status = $request->input('status', 'all');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin('wechat_empower_info as d', function ($join){
            $join->on('a.wid','=','d.id');
        })->when($status != 'all', function ($q) use($status){
            $q->where('a.status', $status);
        })->whereIn('a.user_id', $userGroup)->where('a.typeid', 1)->whereIn('status',[0,2])->select(['a.*','c.name as user_name','d.nick_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.link.active', compact('list'));
    }

    // 小说
    public function linkNovelIndex(Request $request)
    {
        $status = $request->input('status', 'all');
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin('wechat_empower_info as d', function ($join){
            $join->on('a.wid','=','d.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info as b', function ($join){
            $join->on('a.book_id','=','b.id');
        })->leftJoin(config('database.connections.public.database').'.novel_chapter as e', function ($join){
            $join->on('a.chapter_id','=','e.id');
        })->leftJoin('platform_config as p', function ($join){
            $join->on('b.pid','=','p.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($status != 'all', function ($q) use($status){
            $q->where('a.status', $status);
        })->whereIn('a.user_id', $userGroup)->where('a.typeid', 2)->whereIn('status',[0,2])->select(['a.*','b.name as book_name','c.name as user_name','d.nick_name','e.name as chapter_name','p.platform_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.link.novel', compact('list', 'groups', 'user_all'));
    }

    // 签到
    public function linkSignIndex(Request $request)
    {
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin('wechat_empower_info as d', function ($join){
            $join->on('a.wid','=','d.id');
        })->whereIn('a.user_id', $userGroup)->where('a.typeid', 3)->whereIn('status',[0,2])->select(['a.*','c.name as user_name','d.nick_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.link.sign', compact('list'));
    }

    // 继续阅读
    public function linkHistoryIndex(Request $request)
    {
        $status = $request->input('status', 'all');

        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin('wechat_empower_info as d', function ($join){
            $join->on('a.wid','=','d.id');
        })->when($status != 'all', function ($q) use($status){
            $q->where('a.status', $status);
        })->whereIn('a.user_id', $userGroup)->where('a.typeid', 4)->whereIn('status',[0,2])->select(['a.*','c.name as user_name','d.nick_name'])->orderBy('status')->paginate(15);

        return view('wechat.audit.link.history', compact('list'));
    }

    // 更新或创建链接
    public function updateOrInsertLink(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['href'] == "") {
                $validatorError = ['name' => '请认真添加素材链接'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            unset($data['_token']);
            $data['user_id'] = Auth::id();

            DB::connection('admin')->table('wechat_link_info')->updateOrInsert(['msgtype' => $data['msgtype'], 'wid' => $data['wid'], 'book_id' => $data['book_id']], $data);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('更新成功');
        return redirect()->back();
    }

}
