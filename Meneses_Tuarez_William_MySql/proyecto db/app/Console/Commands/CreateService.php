<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $this->createService($name);
    }

    protected function createService($name)
    {
        $className = ucwords($name);
        $stub = File::get(app_path('Console/Commands/stubs/service.stub'));
        $stub = str_replace('{{class}}', $className, $stub);

        $path = app_path('Services/' . $className . '.php');
        File::put($path, $stub);

        $this->info('Service created successfully.');
    }
}
