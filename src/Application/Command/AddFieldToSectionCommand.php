<?php

namespace App\Application\Command;

use App\Domain\Enum\FieldType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AddFieldToSectionCommand {
    public UuidInterface $sectionId;
    public FieldType $fieldType;

    public function __construct(string $sectionId, string $fieldType) {
        $this->sectionId = Uuid::fromString($sectionId);
        $this->fieldType = FieldType::from($fieldType);
    }
}
