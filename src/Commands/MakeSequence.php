<?php

namespace Raid\Guardian\Commands;

class MakeSequence extends MakeCommand
{
    protected $signature = 'raid:make-sequence {className}';

    protected $description = 'Make a new sequence class';

    protected string $type = 'Sequence';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Sequences/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/sequence.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Sequences',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
