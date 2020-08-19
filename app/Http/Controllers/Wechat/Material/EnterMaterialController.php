<?php


namespace App\Http\Controllers\Wechat\Material;


use App\Http\Controllers\Controller;
use App\Models\AdminUsers;
use App\Repositories\Comman\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnterMaterialController extends Controller
{
    // 推送标题明细
    public function titleIndex(Request $request)
    {
        $act = $request->input('act', 2);
        $pdr = $request->input('pdr');
        $status = $request->input('status', 'all');
        $title = $request->input('title');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('wechat_title_info as a')->leftJoin('wechat_link_type as b', function ($join){
            $join->on('a.type','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($status != 'all', function ($q) use($status){
            $q->where('a.status', $status);
        })->when($title, function ($q) use($title) {
            $q->where('a.title', 'like', "%$title%");
        })->when($act, function ($q) use($act){
            $q->where('b.id', $act);
        })->whereIn('user_id', $userGroup)->select(['a.*','b.name','c.name as user_name'])->paginate(15);

        return view('wechat.enter.title', compact('list', 'groups', 'user_all'));
    }

    // 小说的列表明细
    public function novelIndex(Request $request)
    {
        $wid = $request->input('wid');
        $act = $request->input('act');

        $service_status = $request->input('service_status', 'all');
        $group_status = $request->input('group_status', 'all');
        $book_name = $request->input('book_name');

        $list = DB::connection('public')->table('novel_info as a')->leftJoin(config('database.connections.admin.database').'.platform_config as b', function ($join){
            $join->on('a.pid','=','b.id');
        })->leftJoin(config('database.connections.admin.database').'.admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin('novel_type as d', function ($join){
            $join->on('a.typeid','=','d.id');
        })->leftJoin(config('database.connections.admin.database').'.wechat_link_info as f', function ($join){
            $join->on('a.id','=','f.book_id');
        })->when($act, function ($q) use($act){
            $q->where('b.id', $act);
        })->when($service_status != 'all', function ($q) use($service_status, $wid) {
            $q->where('f.chapter_num', 1)->where('f.status', $service_status)->where('wid', $wid);
        })->when($group_status != 'all', function ($q) use($group_status, $wid) {
            $q->where('f.chapter_num', '>', 1)->where('f.status', $group_status)->where('wid', $wid);
        })->when($book_name, function ($q) use($book_name){
            $q->where('a.name','like',"%$book_name%");
        })->select(['a.*','b.platform_name','c.name as user_name','d.name as type_name','f.href','f.chapter_num','f.status','f.wid'])->get()
            ->groupBy('id')->map(function ($value) use($wid){
            $item['id'] = $value->first()->id;
            $item['name'] = $value->first()->name;
            $item['number'] = $value->first()->number;
            $item['word_count'] = $value->first()->word_count;
            $item['sex'] = $value->first()->sex;
            $item['typeid'] = $value->first()->typeid;
            $item['hot'] = $value->first()->hot;
            $item['platform_name'] = $value->first()->platform_name;
            $item['user_name'] = $value->first()->user_name;
            $item['type_name'] = $value->first()->type_name;
            // 跟随公众号
            $item['service_link'] = $value->where('wid', $wid)->where('chapter_num',1)->first()->href ?? null;// 客服消息链接
            $item['group_link'] = $value->where('wid', $wid)->where('chapter_num','>',1)->first()->href ?? null;// 群发链接
            $item['service_status'] = $value->where('wid', $wid)->where('chapter_num',1)->first()->status ?? 0;// 客服消息审核状态
            $item['group_status'] = $value->where('wid', $wid)->where('chapter_num','>',1)->first()->status ?? 0;// 群发消息审核状态
            $item['chapter_id'] = $value->where('wid', $wid)->where('chapter_num','>',1)->first()->chapter_num ?? null; // 第几个章节

            $item['created_at'] = $value->first()->created_at;
            $item['updated_at'] = $value->first()->updated_at;
            return $item;
        })->toArray();

        $list = (new Base())->paginator($list, $request);

        return view('wechat.enter.novel', compact('list', 'wid'));
    }

    public function lazyNovelType(Request $request)
    {
        $pid = $request->input('pid');
        if (empty($pid)){
            return error('-1');
        }
        $data = DB::connection('public')->table('novel_type')->where('pid', $pid)->select(['id','name'])->orderBy('id')->get();

        return success($data);
    }

    // 活动管理
    public function linkActiveIndex(Request $request)
    {
        $wid = $request->get('wid');

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info as d', function ($join){
            $join->on('a.book_id','=','d.id');
        })->leftJoin(config('database.connections.public.database').'.novel_chapter as e', function ($join){
            $join->on('a.chapter_id','=','e.id');
        })->where('a.typeid', 1)->where('wid', $wid)->select(['a.*','c.name as user_name','d.name as book_name','e.name as chapter_name'])->paginate(15);

        $books = DB::connection('public')->table('novel_info')->select(['id','name'])->get();

        return view('wechat.enter.link.active', compact('list', 'wid', 'books'));
    }

    // 活动管理
    public function linkSignIndex(Request $request)
    {
        $wid = $request->get('wid');

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info as d', function ($join){
            $join->on('a.book_id','=','d.id');
        })->leftJoin(config('database.connections.public.database').'.novel_chapter as e', function ($join){
            $join->on('a.chapter_id','=','e.id');
        })->where('a.typeid', 3)->where('wid', $wid)->select(['a.*','c.name as user_name','d.name as book_name','e.name as chapter_name'])->paginate(15);

        $books = DB::connection('public')->table('novel_info')->select(['id','name'])->get();

        return view('wechat.enter.link.sign', compact('list', 'wid', 'books'));
    }

    // 继续阅读
    public function linkHistoryIndex(Request $request)
    {
        $wid = $request->get('wid');

        $list = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('admin_users as c', function ($join){
            $join->on('a.user_id','=','c.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info as d', function ($join){
            $join->on('a.book_id','=','d.id');
        })->leftJoin(config('database.connections.public.database').'.novel_chapter as e', function ($join){
            $join->on('a.chapter_id','=','e.id');
        })->where('a.typeid', 4)->where('wid', $wid)->select(['a.*','c.name as user_name','d.name as book_name','e.name as chapter_name'])->paginate(15);

        $books = DB::connection('public')->table('novel_info')->select(['id','name'])->get();

        return view('wechat.enter.link.history', compact('list', 'wid', 'books'));
    }

}
