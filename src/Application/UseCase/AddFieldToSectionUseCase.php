<?php

namespace App\Application\UseCase;

use App\Application\Command\AddFieldToSectionCommand;
use App\Domain\Enum\FieldType;
use App\Domain\Enum\ValueType;
use App\Domain\Model\Field;
use App\Domain\Model\Section;
use App\Domain\Model\Value;
use App\Domain\Repository\FieldRepository;
use App\Domain\Repository\SectionRepository;
use App\Infrastructure\Persistence\Doctrine\Entity\ValueEntity;
use App\Infrastructure\Persistence\Doctrine\Repository\ValueRepository;
use Psr\Log\LoggerInterface;

class AddFieldToSectionUseCase
{
    private LoggerInterface $logger;
    private SectionRepository $sectionRepository;
    private FieldRepository $fieldRepository;
    private ValueRepository $valueRepository;

    public function __construct(
        LoggerInterface $logger,
        SectionRepository $sectionRepository,
        FieldRepository $fieldRepository,
        ValueRepository $valueRepository
    ) {
        $this->logger = $logger;
        $this->sectionRepository = $sectionRepository;
        $this->fieldRepository = $fieldRepository;
        $this->valueRepository = $valueRepository;
    }

    public function execute(AddFieldToSectionCommand $command): Field
    {
        $section = $this->sectionRepository->findById($command->sectionId);

        $newField = new Field($command->fieldType);
        $field = $this->fieldRepository->save($section, $newField);
        if ($field->getType() == FieldType::DATE) {
            $newValue = new Value();
            $valueType = ValueType::LEFT;
            $value = $this->valueRepository->save($field, $valueType, $newValue);
            $field->getValues()[$valueType->value] = $value;
            $valueType = ValueType::RIGHT; 
            $value = $this->valueRepository->save($field, $valueType, $newValue);
            $field->getValues()[$valueType->value] = $value;
        } else {
            $newValue = new Value();
            $valueType = ValueType::FULL;
            $value = $this->valueRepository->save($field, $valueType, $newValue);
            $field->getValues()[$valueType->value] = $value;
        }

        return $field;
    }
}
