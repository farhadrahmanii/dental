<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'treatment_description',
        'treatment_date',
        'tooth_numbers',
        'tooth_selection_visual',
    ];

    /**
     * Set the treatment_types attribute.
     */
    public function setTreatmentTypesAttribute($value)
    {
        if (is_null($value) || $value === '' || $value === []) {
            $this->attributes['treatment_types'] = json_encode([]);
        } elseif (is_array($value)) {
            $this->attributes['treatment_types'] = json_encode($value);
        } else {
            $this->attributes['treatment_types'] = $value;
        }
    }

    /**
     * Get the treatment_types attribute.
     */
    public function getTreatmentTypesAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Set the tooth_numbers attribute.
     */
    public function setToothNumbersAttribute($value)
    {
        if (is_null($value) || $value === '' || $value === []) {
            $this->attributes['tooth_numbers'] = json_encode([]);
        } elseif (is_array($value)) {
            $this->attributes['tooth_numbers'] = json_encode($value);
        } else {
            $this->attributes['tooth_numbers'] = $value;
        }
    }

    /**
     * Get the tooth_numbers attribute.
     */
    public function getToothNumbersAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get the patient that owns the treatment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }

   public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
