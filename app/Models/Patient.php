<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'register_id';

    protected $fillable = [
        'x_ray_id',
        'name',
        'father_name',
        'sex',
        'age',
        'phone_number',
        'permanent_address',
        'current_address',
        'occupation',
        'diagnosis',
        'comment',
        'images',
        'treatment',
        'doctor_name',
        'marital_status'
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    public function xrays(): HasMany
    {
        return $this->hasMany(Xray::class, 'patient_id', 'register_id');
    }

    public function transcriptions(): HasMany
    {
        return $this->hasMany(Transcription::class, 'patient_id', 'register_id');
    }

    /**
     * Set the images attribute.
     */
    public function setImagesAttribute($value)
    {
        if (is_null($value) || $value === '' || $value === []) {
            $this->attributes['images'] = json_encode([]);
        } elseif (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        } else {
            $this->attributes['images'] = $value;
        }
    }

    /**
     * Get the images attribute.
     */
    public function getImagesAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'patient_id', 'register_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'patient_id', 'register_id');
    }

    // public function services(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Service::class, Payment::class, 'patient_id', 'id', 'register_id', 'service_id');
    // }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'register_id');
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'patient_id', 'register_id');
    }
    public function services()
    {
        return $this->hasManyThrough(
            Service::class,       // final model
            Treatment::class,     // intermediate model
            'patient_id',         // FK on treatments table
            'id',                 // PK on services table
            'register_id',        // local key on patients table
            'service_id'          // FK on treatments table pointing to services
        );
    }

    public function getTotalSpentAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getOutstandingBalanceAttribute()
    {
        return $this->invoices()->where('status', '!=', 'paid')->sum('total_amount') -
               $this->payments()->sum('amount');
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient): void {
            if (blank($patient->doctor_name)) {
                $patient->doctor_name = 'Unassigned';
            }
        });
    }
}
