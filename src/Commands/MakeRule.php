<?php

namespace Raid\Guardian\Commands;

class MakeRule extends MakeCommand
{
    protected $signature = 'raid:make-rule {className}';

    protected $description = 'Make a new rule class';

    protected string $type = 'Norm';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Rules/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/rule.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Rules',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
