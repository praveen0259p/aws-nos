<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sibling extends Model
{
    use HasFactory;

    protected $table = 'siblings';

    protected $fillable = [
        'visa_detail_id',
        'name',
        'relationship',
        'year_of_award',
        'course',
    ];
    public function visaDetail()
    {
        return $this->belongsTo(VisaDetail::class, 'visa_detail_id');
    }
}
