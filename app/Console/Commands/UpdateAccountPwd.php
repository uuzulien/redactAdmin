<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAccountPwd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_account_pwd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从运营后台更新投放的密码';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $this->updateAccount();die;

        $list = DB::connection('toufang')->table('account_config as a')->leftJoin(config('database.connections.admin.database').'.account_config as b', function ($join) {
            $join->on('a.platform_nick','=','b.platform_nick');
        })->whereNotNull('b.password')->where('b.status', 1)->select(['b.password','b.account','a.id'])->get();

        foreach ($list as $val){
            $status = DB::connection('toufang')->table('account_config')->where('id', $val->id)->update(['password' => $val->password, 'account' => $val->account, 'status' => 1]);
            echo $status;
        }
    }

    public function updateToufangWid()
    {
        $upwid = DB::connection('admin')->table('wechat_empower_info as a')->leftJoin(config('database.connections.toufang.database').'.account_config as b', function ($join){
            $join->on('a.nick_name','=','b.platform_nick');
        })->where('b.wid', 0)->select(['a.id as wid','b.id'])->get();
        foreach ($upwid as $val){
            $status = DB::connection('toufang')->table('account_config')->where('id', $val->id)->update(['wid' => $val->wid]);
            echo $status;
        }
    }

    public function updateAdminWid()
    {
        $upwid = DB::connection('admin')->table('wechat_empower_info as a')->leftJoin('account_config as b', function ($join){
            $join->on('a.nick_name','=','b.platform_nick');
        })->where('b.wid', 0)->select(['a.id as wid','b.id'])->get();

        foreach ($upwid as $val){
            $status = DB::connection('admin')->table('account_config')->where('id', $val->id)->update(['wid' => $val->wid]);
            echo $status;
        }
    }

    public function updateAccount()
    {
//        $list = DB::connection('admin')->table('account_config as a')->leftJoin(config('database.connections.toufang.database').'.account_config as b', function ($join){
//            $join->on('a.wid','b.wid');
//        })->where('a.pid','<>',3)->select(['a.account','a.platform_nick','a.password','a.pid','a.yw_id','a.status','b.id', 'b.user_id'])->get();
        $list = DB::connection('admin')->table('account_config')->get();
        dd($list);
        foreach ($list as $val){
            DB::connection('toufang')->table('account_config')->insert([
                'platform_nick' => $val->platform_nick, 'password' => $val->password, 'account' => $val->account, 'pid' => $val->pid, 'status' => $val->status, 'user_id' => 1, 'yw_id' => $val->yw_id
            ]);
//            if (!$val->id){
//                DB::connection('toufang')->table('account_config')->insert([
//                    'platform_nick' => $val->platform_nick, 'password' => $val->password, 'account' => $val->account, 'pid' => $val->pid, 'status' => $val->status, 'user_id' => 1, 'yw_id' => $val->yw_id
//                ]);
//            }else {
//                $status = DB::connection('toufang')->table('account_config')->where('id', $val->id)->update([
//                    'platform_nick' => $val->platform_nick,'password' => $val->password, 'account' => $val->account, 'pid' => $val->pid, 'status' => $val->status, 'yw_id' => $val->yw_id
//                ]);
//            }


//            echo $status;
        }
    }
}
