<?php

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\Enum\ValueType;
use App\Domain\Model\Value;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'values')]
class ValueEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(type: 'string', enumType: ValueType::class)]
    private ValueType $type;

    #[ORM\Column(type: 'string')]
    private string $content;

    #[ORM\ManyToOne(targetEntity: FieldEntity::class, inversedBy: 'values')]
    #[ORM\JoinColumn(name: 'field_id', referencedColumnName: 'id')]
    private FieldEntity $field;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getType(): ValueType
    {
        return $this->type;
    }

    public function setType(ValueType $type): void
    {
        $this->type = $type;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setField(FieldEntity $field): void
    {
        $this->field = $field;
    }

    public function toValue(): Value
    {
        return new Value($this->content, Uuid::fromString($this->id));
    }

    public static function fromValue(FieldEntity $fieldEntity, ValueType $type, Value $value): ValueEntity
    {
        $valueEntity = new ValueEntity();
        if($value->getId()) {
            $valueEntity->setId($value->getId()->toString());
        }
        $valueEntity->setType($type);
        $valueEntity->setField($fieldEntity);
        $valueEntity->setContent($value->getContent());

        return $valueEntity;
    }
}
