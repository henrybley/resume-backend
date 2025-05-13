<?php

namespace App\Application\QueryHandler;

use App\Domain\Repository\SectionRepository;

class GetAllSections {
    private SectionRepository $sectionRepository;
    public  function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function execute(): array {
        return $this->sectionRepository->findAll();
    }
    
}
