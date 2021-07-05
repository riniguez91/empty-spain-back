<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Busqueda;
use App\Models\Municipios;
use App\Models\Provincias;
use App\Models\CCAA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Routing\Controller as BaseController;

class DashboardController extends Controller
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
     * @OA\Get(
     * path="/users",
     * summary="Gets all users records (except sensitive data such as password)",
     * tags={"Admin access"},
     * security={{ "bearerAuth": {}}},
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Token not provided"), 
     *    )
     *  )
     * )
     */
    public function getUsers(Request $request) {
        return User::select('id', 'email', 'name', 'surnames', 'role', 'is_disabled')
               ->get();
    }

    /**
     * @OA\Post(
     * path="/updateUser",
     * summary="Update user credentials",
     * tags={"Admin access"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         description="Bearer {access-token}",
 *         @OA\Schema(
 *              type="bearerAuth"
 *         ) 
 *      ),  
     * @OA\RequestBody(
     *     required=true,
     *     description="JSON containing user credentials",
     *     @OA\JsonContent(
     *          required={"email", "name", "surnames", "role", "is_disabled"},
     *          @OA\Property(property="email", ref="#/components/schemas/User/properties/email"),
     *          @OA\Property(property="name", ref="#/components/schemas/User/properties/name"),
     *          @OA\Property(property="surnames", ref="#/components/schemas/User/properties/surnames"),
     *          @OA\Property(property="role", ref="#/components/schemas/User/properties/role"),
     *          @OA\Property(property="is_disabled", ref="#/components/schemas/User/properties/is_disabled")
     *      ),
     * ),   
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function updateUserCredentials(Request $request) {
        $user = User::where('id', $request->user_id)->first();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->surnames = $request->surnames;
        $user->role = $request->role;
        $user->is_disabled = $request->is_disabled;
        $user->save();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/deleteUser",
     * summary="Delete user permanently from the db",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *     required=true,
     *     description="JSON containing user credentials",
     *     @OA\JsonContent(
     *          required={"id"},
     *          @OA\Property(property="id", ref="#/components/schemas/User/properties/id")
     *      ),
     * ),   
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function deleteUser(Request $request) {
        $user = User::where('id', $request->user_id)->delete();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/updateHighlighted",
     * summary="Updated municipio highlighted column",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *     required=true,
     *     description="JSON containing municipio id and highlighted value",
     *     @OA\JsonContent(
     *          required={"id", "highlighted"},
     *          @OA\Property(property="id", ref="#/components/schemas/Municipios/properties/id"),
     *          @OA\Property(property="highlighted", ref="#/components/schemas/Busqueda/properties/highlighted")
     *      ),
     * ),   
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function updateHighlighted(Request $request) {
        $busqueda = Busqueda::where('municipio_id', $request->municipio_id)->first();
        $busqueda->highlighted = $request->highlighted;
        $busqueda->save();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Gets the highlighted municipios
     * 
     * @param Request $request
     * @return json
     */
    /**
     * @OA\Get(
     * path="/municipiosWithHighlighted",
     * summary="Gets the highlighted municipios",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function getMunicipiosWithHighlighted(Request $request) {
        return Busqueda::select('municipios.id', 'municipios.municipio', 'busqueda.highlighted')
                ->join('municipios', 'busqueda.municipio_id', '=', 'municipios.id')
                ->get();
    }

    /**
     * Gets the top 10 most searched municipios
     * 
     * @param Request $request 
     * @return json
     */
    /**
     * @OA\Get(
     * path="/mostSearchedMunicipios",
     * summary="Gets the top 10 most searched municipios",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function getMostSearchedMunicipios(Request $request) {
        return Busqueda::select('municipios.municipio', 'busqueda.no_searches')
                        ->join('municipios', 'busqueda.municipio_id', '=', 'municipios.id')
                        ->orderByDesc('no_searches')
                        ->limit(10)
                        ->get();
    }

    /**
     * @OA\Get(
     * path="/resetCcaaProvinciasMunicipios",
     * summary="Deletes municipios, provincias and CCAA columns from database and calls SqlSeeder to add them again",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *       @OA\Property(property="Reset success", type="string", example="True"), 
     *    )
     *  )
     * )
     */
    public function ResetCcaaProvinciasMunicipios(Request $request){
        DB::statement("SET foreign_key_checks=0"); 
        DB::table('ccaa')->truncate();
        DB::table('provincias')->truncate();
        DB::table('municipios')->truncate();
        DB::statement("SET foreign_key_checks=1");
        Artisan::call('db:seed --class=SqlSeeder');

        return response()->json([
            'Reset success' => true
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/updateSearch",
     * summary="Updates fields of a search with provided data in the request body",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *     required=true,
     *     description="JSON containing municipio id and column to update",
     *     @OA\JsonContent(
     *          required={"id", "tripadvisor_info"},
     *          @OA\Property(property="id", ref="#/components/schemas/Municipios/properties/id"),
     *          @OA\Property(property="tripadvisor_info", ref="#/components/schemas/Busqueda/properties/tripadvisor_info")
     *      ),
     * ),   
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function updateSearch(Request $request){
        $variable = $request->field;
        $busqueda = Busqueda::where('municipio_id', $request->townId)->first();
        $busqueda->$variable = $request->content; 
        $busqueda->save(); 

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * @OA\Get(
     * path="/getDespoblacion",
     * summary="Get count of municpio_state of all the municipios",
     * tags={"Admin access"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *       @OA\Property(property="Reset success", type="string", example="True"), 
     *    )
     *  )
     * )
     */
    public function getDespoblacion(Request $request){
        $state = Busqueda::groupBy('municipio_state')->select('municipio_state', DB::raw('count(*) as total'))->get();
        return $state;    
    }
}