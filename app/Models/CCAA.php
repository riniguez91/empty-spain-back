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
 * @OA\Xml(name="CCAA"),
 * @OA\Property(property="id", type="integer", description="Database PK", example="1"),
 * @OA\Property(property="autonomia", type="string", description="Autonomia name", example="Andalucia"),
 * @OA\Property(property="image", type="string", description="Image URL", example="https://i.ibb.co/MkyqFkv/Andalucia.jpg"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * Class CCAA
 *
 */
class CCAA extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

}