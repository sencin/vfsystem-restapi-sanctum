<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token && $token->expires_at && now()->greaterThan($token->expires_at)) {
            logger('Token expired for user: ' . $request->user()->email);
            $token->delete();
            return response()->json(['message' => 'Token expired'], 401);
        }
        if ($token) {
            logger('Token is valid for user: ' . $request->user()->email);
        }
        return $next($request);
    }
}
