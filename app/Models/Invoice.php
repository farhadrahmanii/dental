<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'register_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsPaidAttribute()
    {
        return $this->balance <= 0;
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'paid');
    }

    /**
     * Automatically set required fields when creating an invoice.
     */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice): void {
            // Generate a sequential invoice number if not provided
            if (blank($invoice->invoice_number)) {
                $last = Invoice::orderByDesc('id')->first();
                $next = 1;
                if ($last && preg_match('/INV-(\d+)/', (string) $last->invoice_number, $m)) {
                    $next = ((int) $m[1]) + 1;
                } elseif ($last) {
                    $next = (int) $last->id + 1;
                }
                $invoice->invoice_number = 'INV-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
            }

            // Ensure created_by is set to the current user or the first available user
            if (blank($invoice->created_by)) {
                $invoice->created_by = auth()->id() ?? \App\Models\User::query()->value('id');
            }
        });
    }
}
