<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Translatable;

class Service extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'is_active',
        // Translatable fields
        'name_en', 'name_ps', 'name_fa',
        'description_en', 'description_ps', 'description_fa',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Translatable accessors
    public function getNameAttribute()
    {
        return $this->getTranslatableAttribute('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslatableAttribute('description');
    }

    public function getNameEnAttribute()
    {
        return $this->getAttribute('name_en') ?: $this->getAttribute('name');
    }

    public function getNamePsAttribute()
    {
        return $this->getAttribute('name_ps') ?: $this->getAttribute('name');
    }

    public function getNameFaAttribute()
    {
        return $this->getAttribute('name_fa') ?: $this->getAttribute('name');
    }

    public function getDescriptionEnAttribute()
    {
        return $this->getAttribute('description_en') ?: $this->getAttribute('description');
    }

    public function getDescriptionPsAttribute()
    {
        return $this->getAttribute('description_ps') ?: $this->getAttribute('description');
    }

    public function getDescriptionFaAttribute()
    {
        return $this->getAttribute('description_fa') ?: $this->getAttribute('description');
    }
}
