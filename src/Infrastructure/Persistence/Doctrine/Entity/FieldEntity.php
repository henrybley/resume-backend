<?php

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\Enum\FieldType;
use App\Domain\Model\Field;
use App\Domain\Model\Value;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'fields')]
class FieldEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id;

    #[ORM\Column(type: 'string', enumType: FieldType::class)]
    private FieldType $type;

    #[ORM\ManyToOne(targetEntity: SectionEntity::class, inversedBy: 'fields', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'section_id', referencedColumnName: 'id')]
    private SectionEntity $section;

    #[ORM\OneToMany(mappedBy: 'field', targetEntity: ValueEntity::class, cascade: ['persist', 'remove'])]
    private Collection $values;
    /**
     * @param Collection<array-key,mixed> $values
     */
    public function __construct(FieldType $type, Collection $values)
    {
        $this->type = $type;
        $this->values = $values;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setType(FieldType $type): void
    {
        $this->type = $type;
    }

    public function setSection(SectionEntity $section): void
    {
        $this->section = $section;
    }

    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(ValueEntity $value): void
    {
        $this->values->add($value);
    }

    public function toField(): Field
    {
        $values = [];
        foreach($this->values as $value) {
            $values[$value->getType()->value] = $value->toValue();
        }
        return new Field($this->type, $values, Uuid::fromString($this->id));
    }

    public static function fromField(SectionEntity $section, Field $field): FieldEntity
    {
        $entity = new FieldEntity($field->getType(), new ArrayCollection($field->getValues()));
        if ($field->getId()) {
            $entity->setId($field->getId());
        }
        $entity->setSection($section);
        return $entity;
    }
}
