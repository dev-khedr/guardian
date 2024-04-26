<?php

namespace Raid\Guardian\Commands;

class MakeWorker extends MakeCommand
{
    protected $signature = 'raid:make-worker {className}';

    protected $description = 'Make a new worker class';

    protected string $type = 'Worker';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Workers/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/worker.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Workers',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
