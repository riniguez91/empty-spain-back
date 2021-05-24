<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// PUBLIC ACCESS
$router->post('/auth/login', 'AuthController@authenticate'); 
$router->post('/auth/register', 'AuthController@register'); 
$router->get('/municipios', function() { 
    return DB::table('municipios')->select('id', 'municipio')->get(); 
});

// USER 
$router->group(['middleware' => 'jwt.auth'], function() use ($router) {
    $router->post('/addSearch', 'BusquedaController@addSearch');
});  