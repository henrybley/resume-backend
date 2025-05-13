<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Field;
use App\Domain\Model\Section;
use App\Domain\Repository\FieldRepository;
use App\Infrastructure\Persistence\Doctrine\Entity\FieldEntity;
use App\Infrastructure\Persistence\Doctrine\Entity\SectionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * @extends ServiceEntityRepository<FieldEntity>
 */
class FieldEntityRepository extends ServiceEntityRepository implements FieldRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldEntity::class);
    }

    public function findById(Uuid $id): Field
    {
        $fieldEntity = $this->createQueryBuilder('fe')
            ->where('fe.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$fieldEntity) {
            throw new EntityNotFoundException("Field with ID {$id} not found");
        }

        return $fieldEntity->toField();

    }

    public function save(Section $section, Field $field): Field
    {

        $entityManager = $this->getEntityManager();
        if ($field->getId() != null) {
            $fieldEntity = $this->find($field->getId()->toString());
            if (!$fieldEntity) {
                throw new EntityNotFoundException("Field with ID {$field->getId()} not found");
            }
        } else {
            $sectionEntity = $entityManager->getRepository(SectionEntity::class)
                    ->find($section->getId()->toString());

            if (!$sectionEntity) {
                throw new EntityNotFoundException("Section with ID {$section->getId()} not found");
            }
            $fieldEntity = FieldEntity::fromField($sectionEntity, $field);
        }



        $fieldEntity->setType($field->getType());

        $entityManager->persist($fieldEntity);
        $entityManager->flush();

        return $fieldEntity->toField();
    }

    public function delete(Field $field): void
    {
        $fieldEntity = $this->createQueryBuilder('fe')
                             ->where('fe.id = :id')
                             ->setParameter('id', $field->getId()->toString())
                             ->getQuery()
                             ->getOneOrNullResult();

        if (!$fieldEntity) {
            throw new EntityNotFoundException("Field with ID {$field->getId()->toString()} not found");
        }

        $entityManager = $this->getEntityManager();
        $entityManager->remove($fieldEntity);
        $entityManager->flush();
    }

}
