<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Empty Spain API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="grupo5@gmail.com",
     *     name="Grupo 5 Support Team"
     *   ),
     * ),
     * @OA\SecurityScheme(
    *   securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
    * )
     */
    //
}
