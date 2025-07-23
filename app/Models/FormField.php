<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $casts = ['header'=>'array','front_validation_rule'=>'array','validation_rule' => 'array','comment'=>'array'];
    protected $hidden = ['created_at','updated_at'];
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    public function option()
    {
        return $this->belongsTo(FormFieldOption::class, 'option_id');
    }
    public function commentField()
    {
        return $this->belongsTo(FormFieldOption::class,'comment_id');
    }
    public function formSubmission()
    {
        return $this->hasMany(FormSubmission::class, 'field_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(FormField::class, 'parent_id');
    }
    // public function propoalList()
    // {
    //     return $this->hasOne(FormSubmission::class, 'field_id', 'id');
    // }
}
