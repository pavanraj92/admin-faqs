<?php

namespace admin\faqs;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FaqServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Faqs/resources/views'), // Published module views first
            resource_path('views/admin/faq'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'faq');

        $this->mergeConfigFrom(__DIR__.'/../config/faq.php', 'faq.constants');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Faqs/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Faqs/resources/views'), 'faqs-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Faqs/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Faqs/database/migrations'));
        }

        // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/Faqs/config/faqs.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Faqs/config/faqs.php'), 'faq.constants');
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan faqs:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('Modules/Faqs/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Faqs/resources/views/'),
        ], 'faq');
       
        $this->registerAdminRoutes();

    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\faqs\Console\Commands\PublishFaqsModuleCommand::class,
                \admin\faqs\Console\Commands\CheckModuleStatusCommand::class,
                \admin\faqs\Console\Commands\DebugFaqsCommand::class,
                \admin\faqs\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/FaqManagerController.php' => base_path('Modules/Faqs/app/Http/Controllers/Admin/FaqManagerController.php'),
            
            // Models
            __DIR__ . '/../src/Models/Faq.php' => base_path('Modules/Faqs/app/Models/Faq.php'),
            
            // Requests
            __DIR__ . '/../src/Requests/FaqCreateRequest.php' => base_path('Modules/Faqs/app/Http/Requests/FaqCreateRequest.php'),
            __DIR__ . '/../src/Requests/FaqUpdateRequest.php' => base_path('Modules/Faqs/app/Http/Requests/FaqUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Faqs/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\faqs\\Controllers;' => 'namespace Modules\\Faqs\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\faqs\\Models;' => 'namespace Modules\\Faqs\\app\\Models;',
            'namespace admin\\faqs\\Requests;' => 'namespace Modules\\Faqs\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\faqs\\Controllers\\' => 'use Modules\\Faqs\\app\\Http\\Controllers\\Admin\\',
            'use admin\\faqs\\Models\\' => 'use Modules\\Faqs\\app\\Models\\',
            'use admin\\faqs\\Requests\\' => 'use Modules\\Faqs\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\faqs\\Controllers\\FaqManagerController' => 'Modules\\Faqs\\app\\Http\\Controllers\\Admin\\FaqManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\faqs\\Models\\Faq;',
            'use Modules\\Faqs\\app\\Models\\Faq;',
            $content
        );
        
        $content = str_replace(
            'use admin\\faqs\\Requests\\FaqCreateRequest;',
            'use Modules\\Faqs\\app\\Http\\Requests\\FaqCreateRequest;',
            $content
        );
        
        $content = str_replace(
            'use admin\\faqs\\Requests\\FaqUpdateRequest;',
            'use Modules\\Faqs\\app\\Http\\Requests\\FaqUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\faqs\\Controllers\\FaqManagerController',
            'Modules\\Faqs\\app\\Http\\Controllers\\Admin\\FaqManagerController',
            $content
        );

        return $content;
    }
}
