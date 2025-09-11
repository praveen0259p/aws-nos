<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class PmuIrProposalList extends Model
{
    protected $table='proposal';
    public $timestamps = false;
    protected $fillable = ['status'];
    protected $appends = ['proposal_status']; 
    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'acknowledgement_number', 'acknowledgement_number');
    }
    public function inspectionDetail()
    {
        return $this->belongsTo(InspectionDetail::class, 'acknowledgement_number', 'acknowledgement_number');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'status_changed_by');
    }
    

    public function getProposalStatusAttribute()
    {
        $statusLabels = [
            0 => 'Pending',
            1 => 'Draft',
            2 => 'Submitted',
        ];

        return $statusLabels[$this->status] ?? 'Unknown';
    }
    public function getStatusUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
