<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Hash;
use Closure;

class AuthManagerMiddleware
{
    /**
     * Handle an incoming request.
     * 判斷是否有管理者登入過的SESSION
     */
    public function handle($request, Closure $next)
    {
        if(is_null(session()->get('manager_id'))){
            return redirect()->to('/auth/login');
        }
        return $next($request);
    }
}
