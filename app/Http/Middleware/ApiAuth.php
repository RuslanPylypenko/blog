<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');

        if(!$token || !$User = User::where('api_token', $token)->first()) {
            throw new NotFoundHttpException('invalid token');
        }
        if($User){
            Auth::login($User);
        }

        return $next($request);
    }
}
