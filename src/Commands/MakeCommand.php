<?php

namespace Raid\Guardian\Commands;

use Illuminate\Console\Command;

abstract class MakeCommand extends Command
{
    protected string $type = '';

    protected function getType(): string
    {
        return $this->type;
    }

    public function handle(): void
    {
        $path = $this->getClassPath();

        if (file_exists($path)) {
            $this->exists();

            return;
        }

        $this->makeDirectory(dirname($path));

        $content = $this->getClassContent();

        file_put_contents($path, $content);

        $this->success($path);
    }

    protected function success(string $path): void
    {
        $this->components->info(sprintf('%s [%s] created successfully.', $this->getType(), $path));
    }

    protected function exists(): void
    {
        $this->components->error($this->getType().' already exists.');
    }

    protected function getClassName(): string
    {
        return ucwords($this->argument('className'));
    }

    protected function getNameSpace(): string
    {
        return 'App\\Http\\Authentication';
    }

    protected function getClassPath(): string
    {
        return app_path('Http/Authentication');
    }

    protected function getClassContent(): string
    {
        return $this->getStubContent(
            $this->getStubPath(),
            $this->getStubVariables(),
        );
    }

    protected function getStubPath(): string
    {
        return __DIR__.'/../../resources/stubs';
    }

    protected function getStubVariables(): array
    {
        return [
            'CLASS_NAME' => $this->getClassName(),
        ];
    }

    protected function getStubContent(string $path, $variables = []): string
    {
        $contents = file_get_contents($path);

        foreach ($variables as $search => $replace) {
            $contents = str_replace('$'.$search.'$', $replace, $contents);
        }

        return $contents;
    }

    protected function makeDirectory(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        mkdir($path, 0777, true, true);
    }
}
