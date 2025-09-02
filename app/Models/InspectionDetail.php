<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionDetail extends Model
{
    protected $table = 'inspection_details';
    public $timestamps = false;
    protected $fillable = ['acknowledgement_number', 'inspection_id','status','response_status', 'created_by'];
    public function proposal()
    {
        return $this->hasOne(PmuIrProposalList::class, 'acknowledgement_number', 'acknowledgement_number');
    }
}
 