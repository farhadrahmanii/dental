<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'patient_id',
        'treatment_type',
        'treatment_description',
        'treatment_date',
        'tooth_number',
    ];
}
