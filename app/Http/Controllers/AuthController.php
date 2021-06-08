<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends Controller
{
    /**
     * HTTP Request instance
     */
    private $request;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token
     * 
     * @param \App\Models\User $user
     * @return void
     */
    public function generateJWT(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 3600   // Expiration time 3600
        ];

        // Check if it has admin role (no 1 = admin)
        if ($user->role == 1)
            // If it is then append admin payload
            $payload['is_admin'] = 'true';

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return token if the operation is a success
     * 
     * @param \App\Models\User $user
     * @return json
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Checks there is an existing email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist'
            ], 400);
        }

        if ($user->is_disabled) {
            return response()->json([
                'error' => 'User is disabled'
            ], 400);
        }

        // Checks the password and returns token if succesful
        if (Hash::check($this->request->password, $user->password)) {
            // We set the jwt token on the db
            $access_token = $this->generateJWT($user);
            $user->access_token = $access_token;
            $user->save();
            
            // OK response
            return response()->json([
                'success' => true,
                'access_token' => $access_token,
                'name' => $user->name,
                'surnames' => $user->surnames
            ], 200);
        }

        // Both checks failed (Bad request response)
        return response()->json([
            'error' => 'Email or password invalid.'
        ], 400);
    }

    /**
     * Register a user
     * 
     * @param Request $request
     * @return json
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surnames' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:usuario'
        ]);

        // Bad request 400
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 400); 
        }

        $user = new User;

        $user->name = $request->name;
        $user->surnames = $request->surnames;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 0;
        $user->save();

        // We remove the password since we are returning the object back to the user
        // unset($user->password);

        return response()->json([
            'success' => true
        ], 200);
    }

}