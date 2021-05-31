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
     * Insert JSON 
     * 
     * @param Request $request
     * @return json
     */
    public function addSearch(Request $request) {
        $search = new Busqueda;

        $search->tripadvisor_info = $request->tripadvisor_info;
        $search->twitter_info = $request->twitter_info;
        $search->tiempo_info = $request->tiempo_info;
        $search->wiki_info = $request->wiki_info;
        $search->municipio_id = $request->municipio_id;
        $search->usuario_id = $request->usuario_id;
        $search->save();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Gets information belonging to a municipio
     * 
     * @param Integer id
     * @return json
     */
    public function municipioInfo(Request $request) {
        $municipio = Municipios::where('id', $request->id)->first();
        $provincia = Provincias::where('id', $municipio->provincia_id)->first();
        $busqueda = Busqueda::where('municipio_id', $request->id)->first();

        // Check if we have already scraped this town
        if ($busqueda) {
            return response()->json([
                'scraped' => 'true',
                'id' => $municipio->id,
                'provincia' => $provincia->provincia,
                'municipio' => $municipio->municipio,
                'superficie' => $municipio->superficie,
                'tripadvisor_info' => $busqueda->tripadvisor_info,
                'twitter_info' => $busqueda->twitter_info,
                'tiempo_info' => $busqueda->tiempo_info,
                'wiki_info' => $busqueda->wiki_info
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

}
