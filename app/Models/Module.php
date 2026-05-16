<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['module_id','parent_id','module_name','page_url','position','icon_name','active','created_by'];
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'module_id');
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'module_id')->where('active', 1)->orderBy('position')->with('children');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function isActive(): bool
    {
        if (url()->current() === url($this->page_url)) {
            return true;
        }
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return true;
            }
        }

        return false;
    }
}
