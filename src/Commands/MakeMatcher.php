<?php

namespace Raid\Guardian\Commands;

class MakeMatcher extends MakeCommand
{
    protected $signature = 'raid:make-matcher {className}';

    protected $description = 'Make a new matcher class';

    protected string $type = 'Matcher';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Matchers/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/matcher.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Matchers',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
