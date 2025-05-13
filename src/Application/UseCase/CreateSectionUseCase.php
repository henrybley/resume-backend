<?php

namespace App\Application\UseCase;

use App\Application\Command\CreateSectionCommand;
use App\Domain\Model\Section;
use App\Domain\Repository\SectionRepository;

class CreateSectionUseCase
{
    private SectionRepository $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function execute(CreateSectionCommand $command): Section
    {
        return $this->sectionRepository->save(new Section($command->name));
    }
}
