<?php

namespace Raid\Guardian\Commands;

class MakeAuthenticator extends MakeCommand
{
    protected $signature = 'raid:make-authenticator {className}';

    protected $description = 'Make a new authenticator class';

    protected string $type = 'Guardian';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Authenticators/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/authenticator.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Authenticators',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
