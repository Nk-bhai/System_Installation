<?php

namespace App\Providers;

use File;
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
        // Check for a core class of your package
        if (!class_exists(\Nk\SystemAuth\SystemAuthServiceProvider::class)) {
            die('Critical system-auth package is missing. Contact system administrator.');
        }

        // Optional: check for critical file presence
        $coreFile = base_path('vendor/nk/system-auth/src/SystemAuthServiceProvider.php');
        if (!File::exists($coreFile)) {
            die('System package files have been altered or removed. Access denied.');
        }
    }
}
