<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Xray extends Model
{
    protected $fillable = [
        'xray_id',
        'patient_id',
        'xray_image',
        'treatment',
        'doctor_name',
        'comment',
    ];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }
}
