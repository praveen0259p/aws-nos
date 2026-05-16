<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;
    protected $table = 'personal_info';
    protected $fillable = [
        'application_id',
        'applicant_name',
        'father_name',
        'gender',
        'dob',
        'mobile_no',
        'email',
        'state',
        'district',
        'board',
        'certificate_no',
        'year_of_passing',
        'marital_status',
        'aadhar',
        'aadhar_enrollment',
        'current_address_line1',
        'current_address_line2',
        'current_address_state',
        'current_address_district',
        'current_address_pincode',
        'permanent_address_line1',
        'permanent_address_line2',
        'permanent_address_state',
        'permanent_address_district',
        'permanent_address_pincode',
        'emergency_contact_person_name',
        'emergency_person_address',
        'emergency_person_contact_number',
        'emergency_person_contact_email',
        'relationship_applicant',
    ];

    protected $casts = [
        'dob' => 'date',
        'year_of_passing' => 'integer',
        'gender' => 'integer',
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    
}
