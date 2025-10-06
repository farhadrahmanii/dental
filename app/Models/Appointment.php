<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'patient_name',
        'patient_email',
        'patient_phone',
        'service_id',
        'service_name',
        'appointment_date',
        'appointment_time',
        'status',
        'message',
        'notes',
        'patient_id',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAppointmentDateTimeAttribute()
    {
        return Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->format('g:i A');
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->appointment_date)->format('M d, Y');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
                    ->where('status', '!=', 'cancelled');
    }

    public function scopeToday($query)
    {
        return $query->where('appointment_date', now()->toDateString());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('appointment_date', [$startDate, $endDate]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (empty($appointment->appointment_number)) {
                $appointment->appointment_number = 'APT-' . strtoupper(uniqid());
            }
        });
    }
}
