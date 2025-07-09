<?php

namespace admin\faqs\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishFaqsModuleCommand extends Command
{
    protected $signature = 'faqs:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Faqs module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Faqs module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Faqs');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'faq',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Faqs module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/faqs/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/FaqManagerController.php' => base_path('Modules/Faqs/app/Http/Controllers/Admin/FaqManagerController.php'),
            
            // Models
            $basePath . '/Models/Faq.php' => base_path('Modules/Faqs/app/Models/Faq.php'),
            
            // Requests
            $basePath . '/Requests/FaqCreateRequest.php' => base_path('Modules/Faqs/app/Http/Requests/FaqCreateRequest.php'),
            $basePath . '/Requests/FaqUpdateRequest.php' => base_path('Modules/Faqs/app/Http/Requests/FaqUpdateRequest.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Faqs/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

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
            $content = str_replace('use admin\\faqs\\Models\\Faq;', 'use Modules\\Faqs\\app\\Models\\Faq;', $content);
            $content = str_replace('use admin\\faqs\\Requests\\FaqCreateRequest;', 'use Modules\\Faqs\\app\\Http\\Requests\\FaqCreateRequest;', $content);
            $content = str_replace('use admin\\faqs\\Requests\\FaqUpdateRequest;', 'use Modules\\Faqs\\app\\Http\\Requests\\FaqUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Faqs\\'])) {
            $composer['autoload']['psr-4']['Modules\\Faqs\\'] = 'Modules/Faqs/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
