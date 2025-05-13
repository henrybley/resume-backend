<?php

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\Model\Section;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'sections')]
class SectionEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: FieldEntity::class, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private Collection $fields;

    /**
     * @param Collection<array-key,mixed> $fields
     */
    public function __construct(string $name, Collection $fields = new ArrayCollection())
    {
        $this->name = $name;
        $this->fields = $fields;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(FieldEntity $field): void
    {
        $this->fields->add($field);
    }

    public function toSection(): Section
    {
        $fields = [];
        foreach ($this->fields as $fieldEntity) {
            $fields[] = $fieldEntity->toField();
        }
        return new Section($this->name, $fields, Uuid::fromString($this->id));
    }

    public static function fromSection(Section $section): SectionEntity
    {
        $sectionEntity = new SectionEntity($section->getName());
        if ($section->getId()) {
            $sectionEntity->id = $section->getId()->toString();
        }
        foreach ($section->getFields() as $field) {
            $sectionEntity->fields->add(FieldEntity::fromField($sectionEntity, $field));
        }
        return $sectionEntity;
    }
}
