<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Producer\RegistrationProducer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AskRegistration
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var RegistrationProducer */
    private $producer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(EntityManagerInterface $manager, RegistrationProducer $producer, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->producer = $producer;
        $this->logger = $logger;
    }

    public function execute(User $user): void
    {
        $this->logger->info(sprintf('[%s] Ask %s', Log::SUBJECT_REGISTRATION, $user->getEmail()));

        // Set default values
        $user->setPassword('');
        $user->setRegistrationCode('');

        $this->manager->persist($user);
        $this->manager->flush();

        $this->producer->execute($user);
    }
}
