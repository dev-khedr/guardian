<?php

namespace Raid\Core\Authentication\Commands;

class MakeStep extends MakeCommand
{
    protected $signature = 'raid:make-step {className}';

    protected $description = 'Make a new step class';

    protected string $type = 'Step';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Steps/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/step.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Steps',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
