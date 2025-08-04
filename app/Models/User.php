<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */

     protected $fillable = [
        'scheme_id',
        'bo_id',
        'user_name',
        'is_pmu_official',
        'is_active',
        'first_name',
        'middle_name',
        'last_name',
        'mobile_no',
        'email',
        'role',
        'role_name',
        'role_type',
        'district_id',
    ];
    protected $casts=['district_id' => 'array'];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

