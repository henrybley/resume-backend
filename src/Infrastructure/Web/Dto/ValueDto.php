<?php

namespace App\Infrastructure\Web\Dto;

use App\Domain\Model\Value;

class ValueDto{
    private string $id;
    private string $content;

    public function __construct(string $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public static function fromValue(Value $value): ValueDto
    {
        return new ValueDto($value->getId(), $value->getContent());
    }
}
