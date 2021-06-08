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
     * @OA\Post(
     * path="/auth/login",
     * summary="Checks if the user is in the database",
     * tags={"Public access"},
     * @OA\RequestBody(
     *     required=true,
     *     description="User credentials",
     *     @OA\JsonContent(
     *          required={"email", "password"},
     *          @OA\Property(property="email", type="string", format="email", example="Ronaldo@gmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password")
     *      ),
     * ),     
     * @OA\Response(
     *      response=400,
     *      description="Email or password invalid"
     *  ),
     * @OA\Response(
     *      response=200,
     *      description="Success since the user credentials are correct."
     *  )
     * )
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
     * @OA\Post(
     * path="/auth/register",
     * summary="Register a user",
     * tags={"Public access"},
     * @OA\RequestBody(
     *     required=true,
     *     description="User credentials",
     *     @OA\JsonContent(
     *          required={"name", "surnames", "password", "email"},
     *          @OA\Property(property="name", ref="#/components/schemas/User/properties/name"),
     *          @OA\Property(property="surnames", ref="#/components/schemas/User/properties/surnames"),
     *          @OA\Property(property="password", ref="#/components/schemas/User/properties/password"),
     *          @OA\Property(property="email", ref="#/components/schemas/User/properties/email")
     *      ),
     * ),     
     * @OA\Response(
     *      response=400,
     *      description="Validator fail such as email is not unique"
     *  ),
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
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