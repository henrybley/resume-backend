<?php

namespace App\Application\Command;

class CreateSectionCommand
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
