<?php

namespace App\Model\SNCF;

use JMS\Serializer\Annotation as Serializer;

class Link
{
    const TYPE_NEXT = 'next';

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $type;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $href;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): void
    {
        $this->href = $href;
    }

    public function isNext(): bool
    {
        return self::TYPE_NEXT === $this->type;
    }
}
