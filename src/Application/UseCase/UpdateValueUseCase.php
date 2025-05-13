<?php

namespace App\Application\UseCase;

use App\Application\Command\UpdateValueCommand;
use App\Infrastructure\Persistence\Doctrine\Repository\ValueRepository;

class UpdateValueUseCase{
    private ValueRepository $valueRepository;

    public function __construct(ValueRepository $valueRepository)
    {
        $this->valueRepository = $valueRepository;
    }

    public function execute(UpdateValueCommand $command) {
        return $this->valueRepository->update($command->valueId, $command->content);
    }
}
