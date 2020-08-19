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
        $list = DB::connection('toufang')->table('account_config as a')->leftJoin(config('database.connections.admin.database').'.account_config as b', function ($join) {
            $join->on('a.platform_nick','=','b.platform_nick');
        })->whereNotNull('b.password')->where('a.status', 2)->where('b.status', 1)->select(['b.password','a.id'])->get();

        foreach ($list as $val){
            $status = DB::connection('toufang')->table('account_config')->where('id', $val->id)->update(['password' => $val->password, 'status' => 1]);
            echo $status;
        }
    }
}
