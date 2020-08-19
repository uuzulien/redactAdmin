<?php


namespace App\Http\Middleware;

use Closure;

class SeekHeaders
{

    /**
     * 处理传入的请求
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->header('code') != 'spy' or $request->header('version') != '1.0.1')
            return success($data = ['非法请求'], $dataName = 'data', $message = 'error');

        return $next($request);
    }

}
