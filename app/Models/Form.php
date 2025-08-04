<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //
    
    protected $fillable = ['name','slug'];
    protected $hidden = ['active','created_at','updated_at'];
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id');
    }
    public function fields()
    {
        return $this->hasMany(FormField::class)->where('parent_id', 0)->orderBy('order');
    }
}
