<?php

namespace App\Http\Middleware;

use App\Utils\ResponseFormator;
use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JwtAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            FacadesJWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return ResponseFormator::create(401, 'Token is Invalid');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return ResponseFormator::create(401, 'Token is Expired');
            } else {
                return ResponseFormator::create(401, 'Unauthorized');
            }
        }
        return $next($request);
    }
}
