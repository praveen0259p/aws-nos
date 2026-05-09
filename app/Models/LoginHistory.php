<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'regno',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'is_success',
        'logged_in_at',
        'logged_out_at',
    ];

    protected $casts = [
        'is_success'   => 'boolean',
        'logged_in_at' => 'datetime',
        'logged_out_at'=> 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
