<?php

namespace App\Manager;

use App\Entity\Stop;
use App\Repository\StopRepository;
use Doctrine\ORM\EntityManagerInterface;

class StopManager
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var StopRepository */
    private $stopRepository;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->stopRepository = $manager->getRepository(Stop::class);
    }

    public function getStopByCode(string $code): ?Stop
    {
        return $this->stopRepository->findOneBy(['code' => $code]);
    }

    /**
     * @param Stop[] $stops
     */
    public function saveStops(array $stops): void
    {
        $nb = 0;
        foreach ($stops as $stop) {
            ++$nb;

            $this->persist($stop);

            if (0 !== ($nb % 500)) {
                continue;
            }

            $this->manager->flush();
        }
    }

    public function persist(Stop $stop): void
    {
        if (null !== $stop->getId()) {
            return;
        }

        $this->manager->persist($stop);
    }
}
