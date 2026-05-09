<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{   use Notifiable;
    protected $fillable = [
        'role_id','year','round','firstname', 'middlename', 'lastname',
        'dob', 'mobile', 'email', 'category',
        'state', 'district', 'password','email_verified_at','active',
    ];
}
