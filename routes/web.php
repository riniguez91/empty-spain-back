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

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// PUBLIC ACCESS
$router->post('/auth/login', 'AuthController@authenticate'); 
$router->post('/auth/register', 'AuthController@register'); 
$router->get('/municipios', 'BusquedaController@getMunicipios');
$router->get('/municipios/{id}', 'BusquedaController@municipioInfo');
$router->post('/addSearch', 'BusquedaController@addSearch');
$router->get('/highlightedMunicipios', 'BusquedaController@highlightedMunicipios');

// USER 
$router->group(['middleware' => 'jwt.auth'], function() use ($router) {
    
});  

// ADMIN 
$router->group(['middleware' => ['jwt.auth', 'admin']], function() use ($router) {
    $router->get('/users', 'DashboardController@getUsers');
    $router->get('/mostSearchedMunicipios', 'DashboardController@getMostSearchedMunicipios');
    $router->get('/resetCcaaProvinciasMunicipios', 'DashboardController@ResetCcaaProvinciasMunicipios');
    $router->get('/municipiosWithHighlighted', 'DashboardController@getMunicipiosWithHighlighted');
    $router->post('/updateHighlighted', 'DashboardController@updateHighlighted');
});  