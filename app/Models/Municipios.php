<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="Municipio"),
 * @OA\Property(property="id", type="integer", description="Database PK", example="1"),
 * @OA\Property(property="provincia_id", type="integer", description="FK from provincia table", example="9"),
 * @OA\Property(property="municipio", type="string", description="Municipio name", example="Barrundia"),
 * @OA\Property(property="superficie", type="double", description="Superficie size in squared km", example="19.95"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * Class Municipios
 *
 */
class Municipios extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

}