<?php

namespace App\Entity;

use App\Doctrine\DBAL\Type\Point;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="stops")
 * @ORM\Entity(repositoryClass="App\Repository\StopRepository")
 */
class Stop
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="smallint", options={"unsigned": true})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=30)
     *
     * @Assert\NotBlank
     * @Assert\Length(max=30)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $label;

    /**
     * @var Point
     *
     * @ORM\Column(type="point")
     *
     * @Assert\NotBlank
     */
    private $coordinates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getCoordinates(): Point
    {
        return $this->coordinates;
    }

    public function setCoordinates(Point $coordinates): void
    {
        $this->coordinates = $coordinates;
    }

    public function getLatitude(): ?float
    {
        if (null === $this->coordinates) {
            return null;
        }

        return $this->coordinates->getLatitude();
    }

    public function setLatitude(float $latitude): void
    {
        if (null === $this->coordinates) {
            $this->coordinates = new Point($latitude, 0);

            return;
        }

        $this->coordinates->setLatitude($latitude);
    }

    public function setLongitude(float $longitude): void
    {
        if (null === $this->coordinates) {
            $this->coordinates = new Point(0, $longitude);

            return;
        }

        $this->coordinates->setLongitude($longitude);
    }

    public function getLongitude(): ?float
    {
        if (null === $this->coordinates) {
            return null;
        }

        return $this->coordinates->getLongitude();
    }
}
