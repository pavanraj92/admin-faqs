<?php

namespace admin\faqs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FaqServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'faq');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/faq.php', 'faq.constants');
        

        $this->publishes([  
            __DIR__ . '/../config/faq.php' => config_path('constants/faq.php'),
            __DIR__.'/../resources/views' => resource_path('views/admin/faq'),
            __DIR__ . '/../src/Controllers' => app_path('Http/Controllers/Admin/FaqManager'),
            __DIR__ . '/../src/Models' => app_path('Models/Admin/Faq'),
            __DIR__ . '/routes/web.php' => base_path('routes/admin/admin_faq.php'),
        ], 'faq');

        $this->registerAdminRoutes();

    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // You can bind classes or configs here
    }
}
