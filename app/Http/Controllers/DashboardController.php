<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\User;
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
}