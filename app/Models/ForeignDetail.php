<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignDetail extends Model
{
    protected $fillable = [
        'application_id',
        'degree_course',
        'study_field',
        'research_title',
        'description',
        'application_date',
        'anticipated_joining_date',
        'anticipated_course_end_date',
        'university',
        'country',
        'course',
        'college_name',
        'course_state',
        'course_district',
        'college_address',
        'course_taken',
        'passing_year',
        'scoring_system',
        'marks',
        'research_detail_paper'
    ];

    protected $casts = [
        'application_date' => 'date',
        'anticipated_joining_date' => 'date',
        'anticipated_course_end_date' => 'date',
        'passing_year' => 'date',
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
