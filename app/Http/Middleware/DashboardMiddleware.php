<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;

class DashboardMiddleware
{
    public function handle($request, Closure $next, $guard = null) {
        $token = $request->header('Authorization');
        
        $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        // $credentials->sub is the ID of the user
        $user = User::find($credentials->sub);

        // Return 403 http forbidden access
        if ($user->role != 1)
            return response()->json([
                'error' => 'Unauthorized access.'
            ], 403); 
        // Place the user inside the request class so that the client can grab it
        $request->auth = $user;

        return $next($request);
    }
}