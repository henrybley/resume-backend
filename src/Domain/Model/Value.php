<?php

namespace App\Domain\Model;

use Ramsey\Uuid\UuidInterface;

class Value
{
    private ?UuidInterface $id;
    private string $content;

    public function __construct(
        string $content = "",
        UuidInterface $id = null
    ) {
        $this->id = $id;
        $this->content = $content;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
