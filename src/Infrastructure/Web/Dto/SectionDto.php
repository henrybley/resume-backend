<?php

namespace App\Infrastructure\Web\Dto;

use App\Domain\Model\Section;
use Symfony\Component\Validator\Constraints\Uuid;

class SectionDto
{
    private string $id;

    private string $name;

    private array $fields;

    /**
     * @param array<int,FieldDto> $fields
     */
    public function __construct(string $id, string $name, array $fields)
    {
        $this->id = $id;
        $this->name = $name;
        $this->fields = $fields;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array<int,FieldDto> $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public static function fromSection(Section $section): SectionDto
    {
        $fieldDtos = [];
        foreach ($section->getFields() as $field) {
            $fieldDtos[] = FieldDto::fromField($field);
        }
        return new SectionDto($section->getId()->toString(), $section->getName(), $fieldDtos);
    }
}
