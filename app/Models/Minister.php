<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Minister extends Model
{
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }
}
