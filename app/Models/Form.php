<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //
    
    protected $fillable = ['name'];
    protected $hidden = ['scheme_icon','slug','active','created_at','updated_at'];
    protected $appends = ['icon_url'];
    public function scheme()
    {
        return $this->belongsTo(Scheme::class,'scheme_id');
    }
    public function fields()
    {
        return $this->belongsToMany(FormField::class, 'form_formfield', 'form_id', 'formfield_id')
        ->where('parent_id', 0)
        //->with(['children', 'option'])
        ->withPivot('steps','sorting','active')
        ->orderBy('form_formfield.sorting');
    }

    public function getIconUrlAttribute()
    {
        return asset($this->scheme_icon);
    }

}
