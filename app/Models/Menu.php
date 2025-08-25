<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $appends = ['icon_url'];
    protected $hidden = ['url'];
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('children');
    }
    public function getIconUrlAttribute()
    {
        return asset($this->url);
    }
}
