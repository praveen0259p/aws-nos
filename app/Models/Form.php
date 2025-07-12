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
        return $this->belongsTo(Scheme::class);
    }
    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }
}
