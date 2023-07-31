<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Exception;
use App\Traits\ApiResponseTrait;

class AdminVerifyJWTToken
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!Auth::guard('admin-api')->check()) {
                return $this->responseError(Response::HTTP_UNAUTHORIZED, 'Token invalid');
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->responseError(Response::HTTP_UNAUTHORIZED, 'Token invalid');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->responseError(Response::HTTP_UNAUTHORIZED, 'Token expired');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return $this->responseError(Response::HTTP_UNAUTHORIZED, 'Token is Blacklisted');
            } else {
                return $this->responseError(Response::HTTP_UNAUTHORIZED, 'Authorization Token not found');
            }
        }
        return $next($request);
    }
}
