<?php

namespace Raid\Guardian\Commands;

class MakeDriver extends MakeCommand
{
    protected $signature = 'raid:make-driver {className}';

    protected $description = 'Make a new driver class';

    protected string $type = 'Driver';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Drivers/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/driver.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Drivers',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
