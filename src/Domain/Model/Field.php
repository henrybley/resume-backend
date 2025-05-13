<?php

namespace App\Domain\Model;

use App\Domain\Enum\FieldType;
use Ramsey\Uuid\UuidInterface;

class Field
{
    private ?UuidInterface $id;
    private int $order;
    private FieldType $type;
    private array $values;

    /**
     * @param array<ValueType,Value> $values
     */
    public function __construct(
        FieldType $type,
        array $values = [],
        UuidInterface $id = null,
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->values = $values;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getType(): FieldType
    {
        return $this->type;
    }
    /**
     * @return array<int,mixed>
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
