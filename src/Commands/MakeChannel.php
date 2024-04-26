<?php

namespace Raid\Core\Authentication\Commands;

class MakeChannel extends MakeCommand
{
    protected $signature = 'raid:make-channel {className}';

    protected $description = 'Make a new channel class';

    protected string $type = 'Channel';

    protected function getClassPath(): string
    {
        return parent::getClassPath().'/Channels/'.$this->getClassName().'.php';
    }

    protected function getStubPath(): string
    {
        return parent::getStubPath().'/channel.stub';
    }

    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE' => parent::getNameSpace().'\\Channels',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
