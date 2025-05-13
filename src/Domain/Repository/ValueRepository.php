<?php

namespace App\Domain\Repository;

use App\Domain\Enum\ValueType;
use App\Domain\Model\Field;
use App\Domain\Model\Value;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints\Uuid;

interface ValueRepository
{
    public function findById(Uuid $id): Value;
    public function findAll(): Array;
    public function save(Field $field, ValueType $type, Value $value): Value;
    public function update(UuidInterface $id, string $content): Value;
}

