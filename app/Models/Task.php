<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'completed',
        'priority',
        'due_date',
        'client_id',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'datetime',
        'priority' => 'integer',
    ];

    /**
     * Scope to get completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Scope to get pending tasks
     */
    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    /**
     * Scope to get tasks by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope to get overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('completed', false);
    }
}
