<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaDetail extends Model
{
    use HasFactory;

    protected $table = 'visa_details';

    protected $fillable = [
        'application_id',
        'scholarship_select',
        'no_of_sibling_awarded',
        'visa_applied_select',
        'visa_obtained_select',
        'obtained_visa_type',
    ];

    // Relationships
    public function siblings()
    {
        return $this->hasMany(Sibling::class, 'visa_detail_id');
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class, 'visa_detail_id');
    }
}
