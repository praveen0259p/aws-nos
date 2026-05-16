<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['assets_id','parent_menu_id','title','active','created_by'];
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'parent_menu_id', 'id');
    }
}
