<?php


namespace App\Http\Controllers\Material;


use App\Http\Controllers\Controller;
use App\Models\AdminUsers;
use App\Repositories\Comman\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EnterMaterialController extends Controller
{
    // 推送标题明细
    public function titleIndex(Request $request)
    {
        $act = $request->input('act', 2);
        $pdr = $request->input('pdr');
        $status = $request->input('status', 'all');
        $is_share = $request->input('is_share', 'all');
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
        })->when($is_share != 'all', function ($q) use($is_share){
            $q->where('a.is_share', $is_share);
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

    // 图文消息列表
    public function imageTextIndex()
    {
        $userGroup = AdminUsers::query()->group()->get()->pluck('id');


        $list = DB::connection('admin')->table('wechat_imgtext_info as a')->leftJoin('admin_users as b', function ($join) {
            $join->on('a.user_id','=','b.id');
        })->whereIn('a.user_id', $userGroup)->select(['a.*','b.name as user_name'])->paginate(15);

        return view('wechat.enter.imgtext.list', compact('list'));
    }

    // 素材图片库
    public function imageIndex()
    {
        $list = DB::connection('admin')->table('material_image as a')->leftJoin('admin_users as b', function ($join) {
            $join->on('a.user_id','=','b.id');
        })->select(['a.*','b.name as user_name'])->paginate(15);

        return view('wechat.enter.image.list', compact('list'));
    }

    // 新建图文消息
    public function imageTextAdd(Request $request)
    {
        $wid = $request->get('wid');

        return view('wechat.enter.imgtext.add', compact('wid'));
    }

    // 图文消息编辑
    public function imageTextEdit($id)
    {
        $res = DB::connection('admin')->table('wechat_imgtext_info')->find($id);

        return view('wechat.enter.imgtext.edit', compact('res'));
    }

    // 图文消息保存
    public function imageTextSave(Request $request)
    {
        try {
            $data = $request->get('row');
            $data['user_id'] = Auth::id();
            $id = $request->get('id');

            if (empty($request->get('_token')))
                abort('非法请求！');

            if ($id){
                DB::connection('admin')->table('wechat_imgtext_info')->where('id', $id)->update($data);
            }else {
                DB::connection('admin')->table('wechat_imgtext_info')->insert($data);

            }

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }

        flash_message('添加成功');
        return redirect('material/enter/img_text/list');
    }

    /**
     * 文件上传
     */
    public function uploadFile(Request $request) {

        $file = $request->file('file');

        // 此时 $this->upload如果成功就返回文件名不成功返回false
        // 1.是否上传成功
        if (! $file->isValid()) {
            return false;
        }

        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(! in_array($fileExtension, ['png', 'jpg', 'gif','JPG','PNG','GIF'])) {
            return false;
        }

        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 2048000) {
            return false;
        }

        // 4.是否是通过http请求表单提交的文件
        if (! is_uploaded_file($tmpFile)) {
            return false;
        }

        $img_sha1 = sha1_file($tmpFile);

        $is_false = DB::table('material_image')->where('img_sha1', $img_sha1)->first();
        if ($is_false){
            return false;
        }

        // 5.每天一个文件夹,分开存储, 生成一个随机文件名

        $fileName = md5(time()) .mt_rand(0,9999).'.'. $fileExtension;

        if (Storage::disk('public')->put($fileName, file_get_contents($tmpFile)) ){
            $fileInfo = ['img_href' => config('app.url') . '/storage/' . $fileName, 'img_sha1' => $img_sha1, 'user_id' => Auth::id(), 'img_path' => $fileName];

            $id = DB::table('material_image')->insertGetId($fileInfo);
            $imgNum = $this->makeCommenNumber($id);
            DB::table('material_image')->where('id', $id)->update(['img_num' => $imgNum]);

            return success($fileInfo);
        }else {
            return '上传失败';
        }
    }

    public function imageDelete($id)
    {
        $file = DB::table('material_image')->where('id', $id)->first()->img_path;

        $bool = Storage::disk('public')->delete($file);

        if ($bool){
            $status = DB::connection('admin')->table('material_image')->where('id', $id)->delete();
            return success($status);

        }else {
            return '删除失败';
        }

    }

    public function makeCommenNumber($id, $type='mmbzi_')
    {
        $year = date('Y', time());
        $prefix = substr($year, -2);
        $number = $type . $prefix . date('m', time()) . sprintf("%04d", $id);

        return $number;
    }

}
