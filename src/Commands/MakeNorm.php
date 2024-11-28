<?php

namespace Raid\Guardian\Commands;

class MakeNorm extends MakeCommand
{
    protected $signature = 'raid:make-norm {className}';

    protected $description = 'Make a new norm class';

    protected string $type = 'Norm';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Norms/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/norm.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Norms',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
