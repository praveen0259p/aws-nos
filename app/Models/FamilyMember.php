<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;
    protected $table = 'family_members';
    protected $fillable = [
        'visa_detail_id',
        'relationship',
        'name',
        'age',
        'employment',
        'income',
        'itr_status',
    ];
    public function visaDetail()
    {
        return $this->belongsTo(VisaDetail::class, 'visa_detail_id');
    }
}
