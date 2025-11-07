<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'fa', 'ps'])
                ->labels([
                    'en' => 'English',
                    'fa' => 'Farsi',
                    'ps' => 'Pashto',
                ])
                ->visible(insidePanels: true, outsidePanels: true) // Enable language switch both inside and outside Filament panels
                ->displayLocale('en') // Display locale for language names
                ->outsidePanelRoutes([
                    'filament.admin.auth.login',
                    'filament.admin.auth.register',
                    'filament.admin.auth.password-reset.request',
                    'filament.admin.auth.password-reset.reset',
                ])
                ->outsidePanelPlacement(Placement::BottomRight);
        });
    }
}
