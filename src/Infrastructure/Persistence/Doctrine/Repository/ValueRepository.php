<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Enum\ValueType;
use App\Domain\Model\Field;
use App\Domain\Model\Value;
use App\Domain\Repository\ValueRepository as AppValueRepository;
use App\Infrastructure\Persistence\Doctrine\Entity\FieldEntity;
use App\Infrastructure\Persistence\Doctrine\Entity\ValueEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * @extends ServiceEntityRepository<ValueEntity>
 */
class ValueRepository extends ServiceEntityRepository implements AppValueRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValueEntity::class);
    }

    public function findById(Uuid $id): Value
    {
        $valueEntity = $this->find($id);

        if (!$valueEntity) {
            throw new EntityNotFoundException("Value with ID {$id} not found");
        }

        return $valueEntity->toValue();
    }

    public function save(Field $field, ValueType $type, Value $value): Value
    {
        $entityManager = $this->getEntityManager();
        if ($value->getId() != null) {
            $valueEntity = $this->find($value->getId()->toString());

            if (!$valueEntity) {
                throw new EntityNotFoundException("Value with ID {$value->getId()->toString()} not found");
            }

            $valueEntity->setContent($value->getContent());
        } else {
            $fieldEntity = $entityManager->getRepository(FieldEntity::class)->find($field->getId()->toString());

            if(!$fieldEntity) {
                throw new EntityNotFoundException("Field with ID {$field->getId()->toString()} not found");
            }
            $valueEntity = ValueEntity::fromValue($fieldEntity, $type, $value);
            
        }

        $entityManager->persist($valueEntity);
        $entityManager->flush();

        return $valueEntity->toValue();
    }

    public function update(UuidInterface $id, string $content): Value
    {
        $entityManager = $this->getEntityManager();
        $valueEntity = $this->find($id->toString());
        $valueEntity->setContent($content);
        $entityManager->persist($valueEntity);
        $entityManager->flush();

        return $valueEntity->toValue();
    }
    
}
