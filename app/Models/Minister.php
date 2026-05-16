<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Minister extends Model
{
    protected $fillable = ['priority_ordering','assets_id','name','active','created_by'];
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
