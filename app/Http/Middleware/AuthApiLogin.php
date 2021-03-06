<?php
/**
 * Created by PhpStorm.
 * User: sunjin
 * Date: 2017/3/22
 * Time: 17:31
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiLogin
{
    /**
     * The Guard implementation.
     *
     * @var Guard|AdminGuard
     */
    protected $auth;


    /**
     * @param Guard|AdminGuard $auth
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param callable $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws AdminException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) { //判断用户是否登录
            return error('请先登录');
        }
        return $next($request);
    }
}