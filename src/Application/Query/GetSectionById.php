<?php

namespace App\Application\Query;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class GetSectionById {
    public UuidInterface $id;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
    }
}
