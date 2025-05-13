<?php

namespace App\Application\QueryHandler;

use App\Application\Query\GetSectionById;
use App\Domain\Model\Section;
use App\Domain\Repository\SectionRepository;

class GetSectionByIdHandler
{
    private SectionRepository $sectionRepository;
    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function execute(GetSectionById $query): Section
    {
        return $this->sectionRepository->findById($query->id);
    }
}
