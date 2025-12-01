<?php

namespace App\Providers;

use App\Models\ListHewan;
use App\Observers\ListHewanObserver;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

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
        // Register Filament colors
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Green,
            'success' => Color::Purple,
            'warning' => Color::Amber,
        ]);

        // Register model observers
        ListHewan::observe(ListHewanObserver::class);
    }
}
