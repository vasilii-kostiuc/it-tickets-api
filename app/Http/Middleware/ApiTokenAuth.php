<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResponseResource;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $validTokens = config('api.tokens', []);
        
        if (!$token || !in_array($token, $validTokens, true)) {
            return ApiResponseResource::error(
                message: 'Unauthorized',
                statusCode: 401
            );
        }
        
        return $next($request);
    }
}
