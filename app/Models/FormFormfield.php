<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFormfield extends Model
{
    protected $table = 'form_formfield';
    public $timestamps = false;
    protected $fillable = ['form_id','formfield_id','steps','sorting','active'];
}
