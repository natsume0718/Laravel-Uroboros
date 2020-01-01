<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserNameMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        //ログインしていない、ユーザー名が一致していない場合
        if (!$user || $request->user_name !== $user->nickname) {
            return redirect()->back();
        }
        return $next($request);
    }
}
