<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    //
    protected $fillable = ['scheme_id','form_id','Ngo_Unique_Id','path','image_type','latitude','longitude','active'];
}
