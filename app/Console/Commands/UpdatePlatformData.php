<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePlatformData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_platform_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新小说平台的名称';

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
        $list = DB::connection('admin')->table('wechat_empower_info as a')->join('account_config as b', function ($join){
            $join->on('a.nick_name', '=', 'b.platform_nick');
        })->select(['a.id','b.pid'])->whereNotNull('b.pid')->get();

        foreach ($list as $key => $value){
            $status = DB::connection('admin')->table('wechat_empower_info')->where('id', $value->id)->update(['pid' => $value->pid]);
            echo $status;
        }
    }
}
