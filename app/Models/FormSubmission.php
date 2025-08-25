<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = ['ngo_unique_id','acknowledgement_number','form_id','scheme_id','field_id','user_id','field_response','steps','status','status_changed_by','status_updated_at'];
    protected $hidden = ['created_at','updated_at'];
    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id', 'id');
    }
}
