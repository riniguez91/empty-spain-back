<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Busqueda;
use App\Models\Municipios;
use Illuminate\Http\Request;
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
     * Insert JSON 
     * 
     * @param Request $request
     * @return json
     */
    public function getUsers(Request $request) {
        return User::all();
    }

    /**
     * Gets the top 10 most searched municipios
     * 
     * @param Request $request 
     * @return json
     */
    public function getMostSearchedMunicipios(Request $request) {
        return Busqueda::select('municipios.municipio', 'busqueda.no_searches')
                        ->join('municipios', 'busqueda.municipio_id', '=', 'municipios.id')
                        ->orderByDesc('no_searches')
                        ->limit(10)
                        ->get();
    }
}