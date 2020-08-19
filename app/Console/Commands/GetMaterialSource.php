<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatFunBase;
use Illuminate\Console\Command;

class GetMaterialSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_material_source';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取公众号下的永久素材列表';

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
        (new WechatFunBase())->getMaterialList();

    }
}
