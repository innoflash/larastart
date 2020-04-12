<?php

namespace InnoFlash\LaraStart\Console\Commands\Helpers;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use InnoFlash\LaraStart\Helper;

abstract class MakeFile extends Command
{
    abstract public function getStub();

    abstract public function getFilename();

    abstract public function getPath();

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeFile();
    }

    protected function makeFile()
    {
        $this->makeDir();
        if (! $this->filesystem->isFile($this->getPath().'/'.$this->getFilename())) {
            $this->warn(Helper::getFileName($this->argument('name')).' created');

            return $this->filesystem->put($this->getPath().'/'.$this->getFilename(), $this->getReplaceContent());
        } else {
            $this->warn(Helper::getFileName($this->argument('name')).' already exist');
        }
    }

    /**
     * @return bool
     */
    protected function makeDir()
    {
        if (! $this->filesystem->isDirectory($this->getPath())) {
            return $this->filesystem->makeDirectory($this->getPath(), 0755, true);
        }
    }

    protected function getContent()
    {
        try {
            return $this->filesystem->get($this->getStub());
        } catch (FileNotFoundException $e) {
        }
    }

    protected function getReplaceContent()
    {
        $content = $this->getContent();
        $content = str_replace(
            $this->stringsToReplace(),
            $this->replaceContent(),
            $content
        );

        return $content;
    }

    protected function stringsToReplace()
    {
        return [
            '$servicePackage',
            '$namespaceModelName',
            '$filename',
            'modelObject',
            'model_object',
            'ModelName',
        ];
    }

    protected function replaceContent()
    {
        return [
            Helper::getDirName($this->argument('name'), true),
            Helper::getModelNamespace($this->option('model')),
            Helper::getFileName($this->argument('name')),
            Str::camel(Helper::getFileName($this->option('model'))),
            \str_replace('-', '_', Str::kebab(Helper::getFileName($this->option('model')))),
            Helper::getFileName($this->option('model')),
        ];
    }
}
