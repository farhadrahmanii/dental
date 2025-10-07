<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format amount with currency symbol
     */
    public static function format($amount, $showSymbol = true, $locale = null)
    {
        $currency = config('app.currency');
        $locale = $locale ?? app()->getLocale();
        
        $formattedAmount = number_format(
            $amount,
            $currency['decimal_places'],
            $currency['decimal_separator'],
            $currency['thousands_separator']
        );
        
        if ($showSymbol) {
            return $currency['symbol'] . $formattedAmount;
        }
        
        return $formattedAmount;
    }
    
    /**
     * Get currency symbol
     */
    public static function symbol()
    {
        return config('app.currency.symbol');
    }
    
    /**
     * Get currency code
     */
    public static function code()
    {
        return config('app.currency.code');
    }
    
    /**
     * Get currency name
     */
    public static function name()
    {
        return config('app.currency.name');
    }
    
    /**
     * Format amount for display in forms (with prefix)
     */
    public static function prefix()
    {
        return config('app.currency.symbol');
    }
}
