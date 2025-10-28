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

    protected $casts = [
        'tooth_numbers'   => 'array',
        'treatment_date' => 'date',
    ];

   public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
