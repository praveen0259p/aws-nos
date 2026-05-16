<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{   use Notifiable;
    protected $casts = [
        'dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'role_id','regno','firstname', 'middlename', 'lastname',
        'father_name','gender','dob', 'mobile', 'email', 'category',
        'state', 'district', 'password','email_verified_at','active',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function getFullNameAttribute()
    {
        return trim(
            $this->firstname . ' ' .
            ($this->middlename ? $this->middlename . ' ' : '') .
            $this->lastname
        );
    }
}
