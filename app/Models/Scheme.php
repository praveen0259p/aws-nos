<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $hidden = ['active','created_at','updated_at'];
    public function forms()
    {
        return $this->hasMany(Form::class);
    }
}
