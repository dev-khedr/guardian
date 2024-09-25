<?php

namespace Raid\Guardian\Commands;

class MakeGuardian extends MakeCommand
{
    protected $signature = 'raid:make-guardian {className}';

    protected $description = 'Make a new guardian class';

    protected string $type = 'Guardian';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Guardians/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/guardian.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Guardians',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
