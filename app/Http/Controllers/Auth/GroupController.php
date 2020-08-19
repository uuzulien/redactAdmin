<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Repositories\Comman\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $list = DB::connection('admin')->table('groups as a')->leftJoin('group_role as b', function ($join){
            $join->on('a.id','=','b.group_id');
        })->leftJoin('roles as c', function ($join){
            $join->on('b.role_id','=','c.id');
        })->select(['a.id','a.name','a.created_at','c.name as role_name','b.role_id'])->get()->groupBy('id')->map(function ($value){
            $item['id'] =$value->first()->id;
            $item['name'] =$value->first()->name;
            $item['role_name'] =$value->pluck('role_name','role_id')->toArray();
            $item['created_at'] =$value->first()->created_at;
            return $item;
        })->toArray();

        $list = (new Base())->paginator($list, $request);;

        $jobs = DB::connection('admin')->table('jobs_class')->select(['id','name'])->get();

        $roles = DB::connection('admin')->table('roles')->select(['id','name'])->get();

        return view('userAdmin.group.list', compact('list', 'roles', 'jobs'));
    }
    // 部门设立
    public function addSave(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token'])){
                abort('非法请求！');
            }

            if ($data['name'] == []) {
                $validatorError = ['name' => '请认真填写部门名称'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['job_type'] == '0') {
                $validatorError = ['name' => '请选择对应的岗位分类'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::transaction(function () use($data) {
                $jobs = DB::connection('admin')->table('roles')->where('job_type', $data['job_type'])->select('id')->get();

                $group_id = DB::connection('admin')->table('groups')->insertGetId(['name' => $data['name'],'job_type' => $data['job_type']]);
                foreach ($jobs as $role){
                    DB::connection('admin')->table('group_role')->insert(['group_id' => $group_id, 'role_id' => $role->id]);
                }
            });


        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 部门角色分配
    public function editSave(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token'])){
                abort('非法请求！');
            }

            if ($data['name'] == []) {
                $validatorError = ['name' => '部门名称不能为空'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['group_id'] == []) {
                $validatorError = ['name' => '非法操作'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::transaction(function () use($data) {
                DB::connection('admin')->table('groups')->where('id', $data['group_id'])->update(['name' => $data['name']]);

                DB::connection('admin')->table('group_role')->where('group_id', $data['group_id'])->delete();

                foreach ($data['role_id'] as $role_id){
                    DB::connection('admin')->table('group_role')->insert(['group_id' => $data['group_id'], 'role_id' => $role_id]);
                }
            });

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    public function jobIndex()
    {
        $list = DB::connection('admin')->table('jobs_class')->paginate(15);

        return view('userAdmin.jobs.list', compact('list'));
    }

    public function addJobSave(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token'])){
                abort('非法请求！');
            }

            if ($data['name'] == []) {
                $validatorError = ['name' => '请认真填写岗位名称'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::connection('admin')->table('jobs_class')->insert(['name' => $data['name']]);


        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }
}
