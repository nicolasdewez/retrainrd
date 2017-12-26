<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Producer\MailEnableAccountProducer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class EnableAccount
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var MailEnableAccountProducer */
    private $producer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        EntityManagerInterface $manager,
        MailEnableAccountProducer $producer,
        LoggerInterface $logger
    ) {
        $this->manager = $manager;
        $this->producer = $producer;
        $this->logger = $logger;
    }

    public function execute(User $user): void
    {
        $this->logger->info(sprintf('[%s] User: %s', Log::SUBJECT_ENABLE_ACCOUNT, $user->getUsername()));

        $user->setEnabled(true);

        $this->manager->flush();

        $this->producer->execute($user);
    }
}
