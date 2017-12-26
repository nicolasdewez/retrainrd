<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Producer\PasswordLostProducer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AskPasswordLost
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var PasswordLostProducer */
    private $producer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(EntityManagerInterface $manager, PasswordLostProducer $producer, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->producer = $producer;
        $this->logger = $logger;
    }

    public function execute(User $user): void
    {
        /** @var User $userInDatabase */
        $userInDatabase = $this->manager->getRepository(User::class)->findOneBy([
            'email' => $user->getEmail(),
        ]);

        if (null === $userInDatabase) {
            $this->logger->info(sprintf(
                '[%s] No users found for email %s',
                Log::SUBJECT_PASSWORD_LOST,
                $user->getEmail()
            ));

            return;
        }

        $this->logger->info(sprintf(
            '[%s] Ask for %s',
            Log::SUBJECT_REGISTRATION,
            $user->getEmail()
        ));

        $userInDatabase->setEnabled(false);

        $this->manager->flush();

        $this->producer->execute($userInDatabase);
    }
}
