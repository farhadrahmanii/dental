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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        // Try to get the attribute in the current locale
        $value = $this->getRawOriginal("name_{$locale}");
        
        // If not found, try fallback locale
        if (empty($value) && $locale !== $fallbackLocale) {
            $value = $this->getRawOriginal("name_{$fallbackLocale}");
        }
        
        // If still not found, try the original attribute
        if (empty($value)) {
            $value = $this->getRawOriginal('name');
        }
        
        return $value;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        // Try to get the attribute in the current locale
        $value = $this->getRawOriginal("description_{$locale}");
        
        // If not found, try fallback locale
        if (empty($value) && $locale !== $fallbackLocale) {
            $value = $this->getRawOriginal("description_{$fallbackLocale}");
        }
        
        // If still not found, try the original attribute
        if (empty($value)) {
            $value = $this->getRawOriginal('description');
        }
        
        return $value;
    }

    public function getNameEnAttribute()
    {
        return $this->getRawOriginal('name_en') ?: $this->getRawOriginal('name');
    }

    public function getNamePsAttribute()
    {
        return $this->getRawOriginal('name_ps') ?: $this->getRawOriginal('name');
    }

    public function getNameFaAttribute()
    {
        return $this->getRawOriginal('name_fa') ?: $this->getRawOriginal('name');
    }

    public function getDescriptionEnAttribute()
    {
        return $this->getRawOriginal('description_en') ?: $this->getRawOriginal('description');
    }

    public function getDescriptionPsAttribute()
    {
        return $this->getRawOriginal('description_ps') ?: $this->getRawOriginal('description');
    }

    public function getDescriptionFaAttribute()
    {
        return $this->getRawOriginal('description_fa') ?: $this->getRawOriginal('description');
    }
}
