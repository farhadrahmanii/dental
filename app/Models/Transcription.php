<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcription extends Model
{
    protected $fillable = [
        'transcription_id',
        'patient_id',
        'transcription_text',
        'recorded_by',
        'date',
    ];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }
}
