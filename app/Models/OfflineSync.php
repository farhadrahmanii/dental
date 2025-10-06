<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineSync extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'action',
        'data',
        'client_id',
        'synced_at',
        'is_synced',
        'error_message',
    ];

    protected $casts = [
        'data' => 'array',
        'is_synced' => 'boolean',
        'synced_at' => 'datetime',
    ];

    /**
     * Scope to get unsynced records
     */
    public function scopeUnsynced($query)
    {
        return $query->where('is_synced', false);
    }

    /**
     * Scope to get synced records
     */
    public function scopeSynced($query)
    {
        return $query->where('is_synced', true);
    }

    /**
     * Scope to get records by model type
     */
    public function scopeByModel($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }
}
