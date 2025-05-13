<?php

namespace App\Infrastructure\Web\Dto;

use App\Domain\Enum\FieldType;
use App\Domain\Model\Field;

class FieldDto
{
    private string $id;
    private string $type;
    private array $values;
    /**
     * @param array<int,mixed> $values
     */
    public function __construct(string $id, string $type, array $values)
    {
        $this->id = $id;
        $this->type = $type;
        $this->values = $values;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValues(): Array
    {
        return $this->values;
    }
    /**
     * @param array<int,mixed> $values
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    public static function fromField(Field $field): FieldDto
    {
        $values = [];
        foreach($field->getValues() as $key => $value) {
            $values[$key] = ValueDto::fromValue($value);
        }
        return new FieldDto($field->getId()->toString(), $field->getType()->value, $values);
    }
}
