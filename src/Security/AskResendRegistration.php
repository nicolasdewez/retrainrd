<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Producer\RegistrationProducer;
use Psr\Log\LoggerInterface;

class AskResendRegistration
{
    /** @var RegistrationProducer */
    private $producer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(RegistrationProducer $producer, LoggerInterface $logger)
    {
        $this->producer = $producer;
        $this->logger = $logger;
    }

    public function execute(User $user)
    {
        $this->logger->info(sprintf('[%s] Ask %s', Log::SUBJECT_RESEND_REGISTRATION, $user->getUsername()));

        $this->producer->execute($user);
    }
}
