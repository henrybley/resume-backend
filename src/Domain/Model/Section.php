<?php
namespace App\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Section
{
    private ?UuidInterface $id;
    private string $name;
    private array $fields;

    /**
     * @param array<int,mixed> $fields
     */
    public function __construct(
        string $name,
        array $fields = [],
        UuidInterface $id = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->fields = $fields;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int,mixed>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function addField(Field $field): void
    {
        $this->fields[] = $field;
    }
}
