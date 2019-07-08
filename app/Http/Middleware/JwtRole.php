<?php

namespace App\Http\Middleware;

use Closure;
use Lcobucci\JWT\Parser;
use App\User;
use Illuminate\Auth\AuthenticationException;

class JwtRole
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
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens->find($id);
        $userId = $token['user_id'];
        
        $user = User::findOrFail($userId);
        if($user['role'] != 'ROLE_ADMIN'){
            throw new AuthenticationException('Unauthenticated.');
        }

        return $next($request);
    }
}
