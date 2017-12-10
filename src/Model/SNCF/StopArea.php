<?php

namespace App\Model\SNCF;

use JMS\Serializer\Annotation as Serializer;

class StopArea
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $label;

    /**
     * @var string
     *
     * @Serializer\Type("array<string,float>")
     */
    private $coord;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getCoord(): array
    {
        return $this->coord;
    }

    public function setCoord(array $coord): void
    {
        $this->coord = $coord;
    }
}
