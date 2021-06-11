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
 * @OA\Xml(name="Busqueda"),
 * @OA\Property(property="id", type="integer", description="Database PK", example="1"),
 * @OA\Property(property="municipio_id", type="integer", description="FK from municipio table", example="2045"),
 * @OA\Property(property="tripadvisor_info", type="string", description="Stringified JSON containing the result from tripadvisor scraper"),
 * @OA\Property(property="twitter_info", type="string", description="Stringified JSON containing the result from twitter scraper"),
 * @OA\Property(property="tiempo_info", type="string", description="Stringified JSON containing the result from tiempo scraper"),
 * @OA\Property(property="wiki_info", type="string", description="Stringified JSON containing the result from wiki scraper"),
 * @OA\Property(property="municipio_state", type="string", description="State of the municipio regarding 'empty spain'", example="Despoblacion"),
 * @OA\Property(property="highlighted", type="boolean", description="Determines if the town is highlighted on the main search page", example="1"),
 * @OA\Property(property="no_searches", type="integer", description="No. of times a municipio has been searched", example="654"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * Class Busqueda
 *
 */
class Busqueda extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'busqueda';

    /**
     * @return Relations\belongsTo 1-user => N-Busquedas
     */
    public function user() { 
        return $this->belongsTo(User::class);
    }

}