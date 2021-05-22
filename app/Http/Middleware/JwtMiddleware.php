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
        $token = $request->get('access_token');

        if (!$token) {
            // Unauthorized response since there is no token provided
            return response()->json([
                'error' => 'Token not provided'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return reponse()->json([
                'error' => 'Token has expired.'
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occured while decoding the token.'
            ], 400);
        }
        
        // $credentials->sub is the ID of the user
        $user = User::find($credentials->sub);
        // Place the user inside the request class so that the client can grab it
        $request->auth = $user;

        return $next($request);
    }
}