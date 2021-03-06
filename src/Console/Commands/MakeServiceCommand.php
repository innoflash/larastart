<?php

namespace InnoFlash\LaraStart\Console\Commands;

use InnoFlash\LaraStart\Console\Commands\Helpers\MakeFile;
use InnoFlash\LaraStart\Helper;

class MakeServiceCommand extends MakeFile
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service class with path too} {--m|model= : The model to attach to the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This creates a service class for a specified model if supplied';

    public function getStub()
    {
        if ($this->hasArgument('name')) {
            return __DIR__.'/stubs/FullService.stub';
        } else {
            return __DIR__.'/stubs/PlainService.stub';
        }
    }

    public function getModel()
    {
        return $this->argument('model');
    }

    public function getFilename()
    {
        return Helper::getFileName($this->argument('name')).'.php';
    }

    public function getPath()
    {
        return \app_path('Services/'.Helper::getDirName($this->argument('name')));
    }
}
