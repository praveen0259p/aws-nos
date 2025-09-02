<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalPhoto extends Model
{
     protected $fillable = [
        'form_id',
        'name',
        'type',
        'active',
    ];
    protected $hidden = [
        'active',
        'created_at',
        'updated_at',
    ];
}
