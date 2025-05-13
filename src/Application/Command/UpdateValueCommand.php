<?php

namespace App\Application\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UpdateValueCommand{
    public UuidInterface $valueId;
    public string $content;

    public function __construct(string $valueId, string $content)
    {
        $this->valueId = Uuid::fromString($valueId);
        $this->content = $content;
    }
}
