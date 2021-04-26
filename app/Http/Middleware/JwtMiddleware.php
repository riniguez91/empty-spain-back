<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null) {
        $token = $request->header('Authorization');

        if (!$token) {
            return response.json([
                'error' => 'Token not provided'
            ], 400);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return reponse()->json([
                'error' => 'Token has expired'
            ]);
        }
        
        $user = User::find($credentials->sub);
        $request->auth = $user;
        return $next($request);
    }
}