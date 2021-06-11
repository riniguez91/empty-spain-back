<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * ss
     * 
     * @return 
     * SELECT * FROM busqueda WHERE busqueda.id IN (SELECT busqueda_id FROM user_busqueda WHERE user_id = 6)
     */
    public function getUserSearches(Request $request) {
        $user_id = $request->user_id;
        return DB::table('busqueda')
        ->select('wiki_info', 'municipio_id', 'municipio') 
        ->whereIn('busqueda.id', function($query) use ($user_id) {
            $query->select('busqueda_id')
            ->from('user_busqueda')
            ->where('user_id', '=', $user_id);
        })
        ->join('municipios', 'busqueda.municipio_id', '=', 'municipios.id')
        ->get();
    }
}
