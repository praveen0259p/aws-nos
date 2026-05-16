<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentDetail extends Model
{
    protected $fillable = [
        'application_id',
        'currently_employed',
        'current_job_nature',
        'current_office_name',
        'current_office_address',
        'current_office_state',
        'current_office_district',
        'current_office_designation',
        'current_annual_salary',
        'employed_earlier',
        'employed_earlier_job_nature',
        'employed_earlier_office',
        'employed_earlier_office_address',
        'employed_earlier_office_state',
        'employed_earlier_office_district',
        'employed_earlier_office_designation',
        'employed_earlier_salary',
        'other_employment',
        'other_employment_job_nature',
        'other_employment_job_office',
        'other_employment_office_address',
        'other_employment_office_state',
        'other_employment_office_district',
        'other_employment_office_designation',
        'other_employment_salary',
        'other_employment_joining_date',
        'other_employment_leaving_date',
    ];
    protected $casts = [
        'other_employment_joining_date' => 'date',
        'other_employment_leaving_date' => 'date'
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
