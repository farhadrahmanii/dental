<?php

namespace App;

trait Translatable
{
    /**
     * Get a translatable attribute
     */
    public function getTranslatableAttribute($key)
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        // Try to get the attribute in the current locale
        $value = $this->getAttribute("{$key}_{$locale}");
        
        // If not found, try fallback locale
        if (empty($value) && $locale !== $fallbackLocale) {
            $value = $this->getAttribute("{$key}_{$fallbackLocale}");
        }
        
        // If still not found, try the original attribute
        if (empty($value)) {
            $value = $this->getAttribute($key);
        }
        
        return $value;
    }
    
    /**
     * Set a translatable attribute
     */
    public function setTranslatableAttribute($key, $value)
    {
        $locale = app()->getLocale();
        $this->setAttribute("{$key}_{$locale}", $value);
    }
    
    /**
     * Get all translatable attributes for a given key
     */
    public function getTranslatableAttributes($key)
    {
        $locales = ['en', 'ps', 'fa'];
        $attributes = [];
        
        foreach ($locales as $locale) {
            $attributes[$locale] = $this->getAttribute("{$key}_{$locale}");
        }
        
        return $attributes;
    }
    
    /**
     * Set all translatable attributes for a given key
     */
    public function setTranslatableAttributes($key, $values)
    {
        foreach ($values as $locale => $value) {
            $this->setAttribute("{$key}_{$locale}", $value);
        }
    }
}