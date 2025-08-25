<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PmuIrProposalList extends Model
{
    protected $table='proposal';
    public $timestamps = false;
    protected $fillable = ['status'];
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'acknowledgement_number', 'acknowledgement_number');
    }

}
