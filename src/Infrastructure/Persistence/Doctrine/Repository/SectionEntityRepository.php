<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Section;
use App\Domain\Repository\SectionRepository;
use App\Infrastructure\Persistence\Doctrine\Entity\SectionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<SectionEntity>
 */
class SectionEntityRepository extends ServiceEntityRepository implements SectionRepository
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger, ManagerRegistry $registry)
    {
        $this->logger = $logger;
        parent::__construct($registry, SectionEntity::class);
    }


    public function findAll(): array
    {
        $sectionEntities = $this->createQueryBuilder('se')
                ->getQuery()
                ->getResult();

        return array_map(function (SectionEntity $sectionEntity) {
            return $sectionEntity->toSection();
        }, $sectionEntities);

    }

    public function findById(UuidInterface $id): Section
    {
        $sectionEntity = $this->createQueryBuilder('se')
            ->where('se.id = :id')
            ->setParameter('id', $id->toString())
            ->getQuery()
            ->getOneOrNullResult();

        if (!$sectionEntity) {
            throw new EntityNotFoundException("Section with ID {$id} not found");
        }

        return $sectionEntity->toSection();

    }

    public function save(Section $section): Section
    {
        if ($section->getId()) {
            $this->logger->debug("Updating Section: {$section->getId()->toString()}");

            $sectionEntity = $this->createQueryBuilder('se')
            ->where('se.id = :id')
            ->setParameter('id', $section->getId()->toString())
            ->getQuery()
            ->getOneOrNullResult();

            if (!$sectionEntity) {
                throw new EntityNotFoundException("Section with ID {$section->getId()->toString()} not found");
            }
            $sectionEntity->setName($section->name);
        } else {
            $sectionEntity = SectionEntity::fromSection($section);
        }


        $entityManager = $this->getEntityManager();
        $entityManager->persist($sectionEntity);
        $entityManager->flush();

        return $sectionEntity->toSection();
    }

    public function delete(Section $section): void
    {
    }
}
