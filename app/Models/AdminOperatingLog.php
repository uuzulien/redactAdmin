<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminOperatingLog extends Model
{
    protected $connection = 'admin';
    protected $table = 'admin_operating_log';
    protected $guarded = ['id'];
}
