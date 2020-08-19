<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\{AdminOperatingLog, Permission};

class OperatingLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::check()) { //判断用户是否登录
            return redirect('/admins/login.xhtml');
        }
        $permission_data = Permission::where('name', $request->route()->getAction()['as'])->first(['display_name']);
        $data = [
            'controller' => $request->route()->getAction()['controller'],
            'as' => $request->route()->getAction()['as'],
            'display_name' => isset($permission_data) ? $permission_data->display_name : '',
            'admin_id' => auth()->user()->id,
            'admin_name' => auth()->user()->name,
            'ip' => $request->getClientIp(),
            'method' => $request->method(),
        ];
        if (env('APP_ENV') == 'local') {
            return $next($request);
        }
        if (env('APP_ENV') != 'develop') {
            AdminOperatingLog::create($data);
        }
        return $next($request);
    }
}
