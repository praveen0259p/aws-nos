<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $hidden = ['active','created_at','updated_at'];
    public function forms()
    {
        return $this->hasMany(Form::class,'scheme_id')->where('active', 1);
    }
    
    public function faqs()
    {
        return $this->hasMany(FAQ::class);
    }
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
