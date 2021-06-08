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
 * @OA\Xml(name="User"),
 * @OA\Property(property="id", type="integer", description="Database PK", example="1"),
 * @OA\Property(property="email", type="string", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="name", type="string", description="User name", example="John"),
 * @OA\Property(property="surnames", type="string", description="User surnames", example="Doe"),
 * @OA\Property(property="password", type="string", description="Encoded password", example="$2y$10$LGHRoMB25RZUsWsDhZoQKO9ZpxnFO1GLIggRGtxXIpjP0JgbNaTbC"),
 * @OA\Property(property="role", type="integer", readOnly="true", description="User role (0->user, 1->admin)", example="0"),
 * @OA\Property(property="is_disabled", type="boolean", description="Determines if the user is disabled and has no access to the web", example="0"),
 * @OA\Property(property="access_token", type="string", description="JWT Token generated from the web service", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOjEsImlhdCI6MTYyMzE2MTYxMywiZXhwIjoxNjIzMTY1MjEzLCJpc19hZG1pbiI6InRydWUifQ.wXsJ459zlnth9soCG4z8W9Fsu6ttqRFOXRhCHrph0Ao"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * Class User
 *
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuario';

    /**
     * @return Relations\HasMany 1-user => N-Busquedas
     */
    public function busqueda() { 
        return $this->hasMany(Busqueda::class);
    }

    /**
     * @return 
     */
    public function addUser() {
        return True;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surnames', 'email', 'password', 'role', 
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    /* protected $hidden = [
        'password',
    ];  */
}