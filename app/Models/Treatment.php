<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'treatment_description',
        'treatment_date',
        'tooth_numbers',
    ];

    // Remove the JSON casting since these are now ENUM columns
    protected $casts = [
        'treatment_types' => 'array',
        'tooth_numbers'   => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
