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
 * @OA\Xml(name="UserSearch"),
 * @OA\Property(property="id", type="integer", description="Database PK", example="1"),
 * @OA\Property(property="busqueda_id", type="integer", description="FK from busqueda table", example="3"),
 * @OA\Property(property="user_id", type="integer", description="FK from usuario table", example="6"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * Class UserSearch
 *
 */
class UserSearch extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_busqueda';
}