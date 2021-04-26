<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

Route::get('/', function() {
    return 'Hello world';
});

$router->post("/login", ["uses" => "UserController@authenticate"]);
$router->post("/register", "UserController@register"); 

/* Route::prefix('auth')->group(function() {
    Route::post('/login', [UserController::class, 'authenticate']);
    Route::post('/register', [UserController::class, 'register']);
}); */

