<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    protected $fillable = ['application_id','application_number','user_id',
        'window_id','field_name','old_value','new_value'
    ];
}
