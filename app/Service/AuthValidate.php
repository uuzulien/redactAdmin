<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;
class AuthValidate
{
    public function authRouterValidate($route_name)
    {
        return Auth::user()->can($route_name);
    }
}
