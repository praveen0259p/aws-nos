<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class ApplicationWindow extends Model
{
    protected $fillable = [
        'title',
        'application_open_date',
        'application_close_date',
        'edit_start_date',
        'edit_end_date',
        'active'
    ];

    protected $casts = [
        'application_open_date' => 'datetime',
        'application_close_date'   => 'datetime',
        'edit_start_date'       => 'datetime',
        'edit_end_date'         => 'datetime',
    ];

    public function isSubmissionOpen(): bool
    {
        $start = Carbon::parse($this->application_open_date)->startOfDay();
        $end   = Carbon::parse($this->application_close_date)->endOfDay();
        return now()->between($start, $end);
    }

    public function isEditOpen(): bool
    {
        $start = Carbon::parse($this->edit_start_date)->startOfDay();
        $end   = Carbon::parse($this->edit_end_date)->endOfDay();
        return now()->between($start, $end);
    }
}
