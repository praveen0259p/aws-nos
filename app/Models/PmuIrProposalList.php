<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PmuIrProposalList extends Model
{
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'project_id', 'project_id');
    }

}
