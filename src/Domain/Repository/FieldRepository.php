<?php

namespace App\Domain\Repository;

use App\Domain\Model\Field;
use App\Domain\Model\Section;
use Symfony\Component\Validator\Constraints\Uuid;

interface FieldRepository
{
    public function findById(Uuid $id): Field;
    public function findAll(): Array;
    public function save(Section $section, Field $field): Field;
    public function delete(Field $field): void;
}

