<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = ['Ngo_Unique_Id','Ack_Number','form_id','scheme_id','field_id','user_id','field_response','steps'];
    protected $hidden = ['created_at','updated_at'];
    // public function proposal()
    // {
    //     return $this->belongsTo(PmuIrProposalList::class, 'Ngo_Unique_Id', 'Ngo_Unique_Id');
    // }
    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id', 'id');
    }
}
