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
     * Insert JSON 
     * 
     * @param Request $request
     * @return json
     */
    public function getUsers(Request $request) {
        return User::select('id', 'email', 'name', 'surnames', 'role', 'is_disabled')
               ->get();
    }

    /**
     * Update user credentials
     * 
     * @param Request $request 
     * @return json
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
     * Delete user PERMANENTLY from the db
     */
    public function deleteUser(Request $request) {
        $user = User::where('id', $request->user_id)->delete();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Updated municipio highlighted column
     * 
     * @param Request $request 
     * @return json
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
    public function getMostSearchedMunicipios(Request $request) {
        return Busqueda::select('municipios.municipio', 'busqueda.no_searches')
                        ->join('municipios', 'busqueda.municipio_id', '=', 'municipios.id')
                        ->orderByDesc('no_searches')
                        ->limit(10)
                        ->get();
    }

    /**
     * Deletes municipios, provincias and CCAA columns from database
     * and calls SqlSeeder to add them again
     * 
     * @param Request $request
     * @return void
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
     * Updates fields of a search with provided data in the Request variable
     * 
     * @param Request $request
     * @return void
     */
    public function updateSearch(Request $request){
        $variable = $request->field;
        $busqueda = Busqueda::where('municipio_id', $request->townId)->first();
        $busqueda-> $variable = $request->content; 
        $busqueda->save(); 

        return response()->json([
            'success' => true
        ], 200);
    }

    public function getDespoblacion(Request $request){
        $state = Busqueda::groupBy('municipio_state')->select('municipio_state', DB::raw('count(*) as total'))->get();
        return $state;    
    }
}