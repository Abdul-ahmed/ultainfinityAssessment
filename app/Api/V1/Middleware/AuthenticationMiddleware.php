<?php

namespace App\Api\V1\Middleware;

use Closure;

class AuthenticationMiddleware
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
        if ($request->header('user-id') != env("USER_ID_ONE") && $request->header('user-id') != env("USER_ID_TWO") && $request->header('user-id') != env("USER_ID_THREE") && $request->header('user-id') != env("USER_ID_FOUR")) {
            return redirect('api/auth/response');
        }

        return $next($request);
    }
}
