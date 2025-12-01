<?php

namespace App\Providers;

use App\Models\Kategori;
use App\Models\ListHewan;
use App\Models\ListDistribusi;
use App\Policies\KategoriPolicy;
use App\Policies\ListHewanPolicy;
use App\Policies\ListDistribusiPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ListHewan::class => ListHewanPolicy::class,
        ListDistribusi::class => ListDistribusiPolicy::class,
        Kategori::class => KategoriPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
