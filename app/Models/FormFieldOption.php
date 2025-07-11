<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFieldOption extends Model
{
    protected $fillable = ['values'];
    protected $casts = ['values' => 'array'];
    protected $hidden = ['active','created_at','updated_at'];
}
