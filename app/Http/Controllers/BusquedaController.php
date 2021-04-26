<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\Busqueda;
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
        $search->search_date = $request->search_date;
        $search->municipio_id = $request->municipio_id;
        $search->usuario_id = $request->usuario_id;
        $search->save();

        return response()->json([
            'success' => true
        ], 200);
    }

}
