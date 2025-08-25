<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id');
    }
}