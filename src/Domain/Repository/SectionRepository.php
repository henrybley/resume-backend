<?php

namespace App\Domain\Repository;

use App\Domain\Model\Section;
use Ramsey\Uuid\UuidInterface;

interface SectionRepository
{
    public function findById(UuidInterface $id): Section;
    public function findAll(): Array;
    public function save(Section $section): Section;
    public function delete(Section $section): void;
}

