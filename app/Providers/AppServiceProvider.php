<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Vite;

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
        // Vite manifest समस्या का समाधान
        if (app()->environment('production') && 
            !file_exists(public_path('build/manifest.json'))) {
            
            // Vite को अस्थायी रूप से निष्क्रिय करें
            $this->app->bind(Vite::class, function () {
                return new class extends Vite {
                    public function __invoke(array $entryPoints): string
                    {
                        return '';
                    }
                };
            });
        }
    }
}