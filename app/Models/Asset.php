<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    public function documents()
    {
        return $this->hasMany(Document::class, 'assets_id', 'id');
    }
    public function getSizeMbAttribute()
    {
        return number_format($this->size / 1024 / 1024, 2);
    }
}
