<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'diagnosis',
        'comment',
        'images',
        'treatment',
        'doctor_name',
    ];

    protected $casts = [
        'images' => 'array',
        'age' => 'integer',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'patient_id', 'register_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'patient_id', 'register_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'register_id');
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
}


