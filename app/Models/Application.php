<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['user_id','window_id','application_number','steps',
        'application_start_date', 'application_status','submit_date'
    ];
    public function window()
    {
        return $this->belongsTo(ApplicationWindow::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class);
    }

    public function foreignDetail()
    {
        return $this->hasOne(ForeignDetail::class);
    }
    public function employmentDetail()
    {
        return $this->hasOne(EmploymentDetail::class);
    }
    public function visaDetail()
    {
        return $this->hasOne(VisaDetail::class);
    }

    public function history()
    {
        return $this->hasMany(ApplicationHistory::class);
    }
}
