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

    public function persist(Stop $stop): void
    {
        if (null !== $stop->getId()) {
            return;
        }

        $this->manager->persist($stop);
    }

    public function flush(): void
    {
        $this->manager->flush();
    }
}
