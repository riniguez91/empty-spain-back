<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\Busqueda;
use App\Models\Municipios;
use App\Models\Provincias;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class BusquedaController extends Controller
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
     * path="/addSearch",
     * summary="Add search info after a user searches a municipio",
     * tags={"Public access"},
     * @OA\RequestBody(
     *     required=true,
     *     description="JSON containing scraper info",
     *     @OA\JsonContent(
     *          required={"tripadvisor_info", "twitter_info", "tiempo_info", "wiki_info", "municipio_id", "municipio_state"},
     *          @OA\Property(property="tripadvisor_info", ref="#/components/schemas/Busqueda/properties/tripadvisor_info"),
     *          @OA\Property(property="twitter_info", ref="#/components/schemas/Busqueda/properties/twitter_info"),
     *          @OA\Property(property="tiempo_info", ref="#/components/schemas/Busqueda/properties/tiempo_info"),
     *          @OA\Property(property="wiki_info", ref="#/components/schemas/Busqueda/properties/wiki_info"),
     *          @OA\Property(property="municipio_id", ref="#/components/schemas/Busqueda/properties/municipio_id"),
     *          @OA\Property(property="municipio_state", ref="#/components/schemas/Busqueda/properties/municipio_state"),
     *      ),
     * ),   
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function addSearch(Request $request) {
        $search = new Busqueda;

        $search->tripadvisor_info = $request->tripadvisor_info;
        $search->twitter_info = $request->twitter_info;
        $search->tiempo_info = $request->tiempo_info;
        $search->wiki_info = $request->wiki_info;
        $search->municipio_id = $request->municipio_id;
        $search->municipio_state = $request->municipio_state;
        $search->no_searches = 1;
        $search->save();

        return response()->json([
            'success' => true
        ], 200);
    }


    /**
     * @OA\Get(
     * path="/municipios/{id}",
     * summary=" Gets information belonging to a municipio",
     * tags={"Public access"},
     * @OA\Parameter(
     *      description="ID of the municipio",
     *      in="path",
     *      name="id",
     *      required=true,
     *      example="2045",
     *      @OA\Schema(
     *          type="integer",
     *          format="int64"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
    */
    public function municipioInfo(Request $request) {
        $municipio = Municipios::where('id', $request->id)->first();
        $provincia = Provincias::where('id', $municipio->provincia_id)->first();
        $busqueda = Busqueda::where('municipio_id', $request->id)->first();

        // Check if we have already scraped this town
        if ($busqueda) {
            // Increment no_searches for this municipio
            $busqueda->no_searches++;
            $busqueda->save();
            return response()->json([
                'scraped' => 'true',
                'id' => $municipio->id,
                'provincia' => $provincia->provincia,
                'municipio' => $municipio->municipio,
                'superficie' => $municipio->superficie,
                'tripadvisor_info' => $busqueda->tripadvisor_info,
                'twitter_info' => $busqueda->twitter_info,
                'tiempo_info' => $busqueda->tiempo_info,
                'wiki_info' => $busqueda->wiki_info,
                'municipio_state' => $busqueda->municipio_state,
                'updated_at'=> $busqueda->updated_at
            ], 200);
        }
        // Else return this for starters, will change in the future
        else {
            return response()->json([
                'id' => $municipio->id,
                'provincia' => $provincia->provincia,
                'municipio' => $municipio->municipio,
                'superficie' => $municipio->superficie
            ], 200);
        }
    }

    /**
     * @OA\Get(
     * path="/municipios",
     * summary="Obtain the information belonging to all the municipios in the database",
     * tags={"Public access"},
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function getMunicipios(Request $request) {
        return Municipios::select('id', 'municipio')->get();
    }

    /**
     * @OA\Get(
     * path="/highlightedMunicipios",
     * summary="Gets the wikipedia scraper info for four highlighted municipios",
     * tags={"Public access"},
     * @OA\Response(
     *      response=200,
     *      description="Success"
     *  )
     * )
     */
    public function highlightedMunicipios(Request $request) {
        return Busqueda::select('wiki_info')
                        ->where('highlighted', 1)
                        ->limit(4)
                        ->get();
    }

}
