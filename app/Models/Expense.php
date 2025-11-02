<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'expense_type',
        'description',
        'amount',
        'payment_method',
        'expense_date',
        'paid_to',
        'recorded_by',
        'receipt_image',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (empty($expense->expense_id)) {
                $expense->expense_id = static::generateExpenseId();
            }
        });
    }

    protected static function generateExpenseId(): string
    {
        $lastExpense = static::orderBy('id', 'desc')->first();
        $nextNumber = 1;

        if ($lastExpense && preg_match('/EXP-(\d+)/', $lastExpense->expense_id, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        }

        return 'EXP-' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

