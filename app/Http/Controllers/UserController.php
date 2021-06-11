<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserSearch;
use App\Models\Busqueda;

class UserController extends Controller
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
     * @OA\Post(
     * path="/getUserSearches",
     * summary="Get the search history for a user",
     * tags={"User access"},
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
    public function getUserSearches(Request $request) {
        $user_id = $request->user_id;
        return Busqueda::select('wiki_info', 'municipio_id', 'municipio') 
        ->whereIn('busqueda.id', function($query) use ($user_id) {
            $query->select('busqueda_id')
            ->from('user_busqueda')
            ->where('user_id', '=', $user_id);
        })
        ->join('municipios', 'busqueda.municipio_id', '=', 'municipios.id')
        ->get();
    }

    /**
     * @OA\Post(
     * path="/insertUserSearch",
     * summary="Insert a town into the user search history",
     * tags={"User access"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *     required=true,
     *     description="JSON containing user and search id",
     *     @OA\JsonContent(
     *          required={"user_id", "busqueda_id"},
     *          @OA\Property(property="user_id", ref="#/components/schemas/User/properties/id"),
     *          @OA\Property(property="busqueda_id", ref="#/components/schemas/Busqueda/properties/id")
     *      ),
     * ),   
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function insertUserSearch(Request $request) {
        $search = UserSearch::where('user_id', $request->user_id)->where('busqueda_id', $request->busqueda_id)->first();

        // Check we dont insert an already searched town
        if (!$search) {
            $user_search = new UserSearch;
            $user_search->busqueda_id = $request->busqueda_id;
            $user_search->user_id = $request->user_id;
            $user_search->save();
        }

        return response()->json([
            'success' => true
        ], 200);
    }
}
