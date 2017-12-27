<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Workflow\RegistrationWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ActiveUser
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var RegistrationWorkflow */
    private $workflow;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        RegistrationWorkflow $workflow,
        LoggerInterface $logger
    ) {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->workflow = $workflow;
        $this->logger = $logger;
    }

    public function execute(User $user): void
    {
        $this->logger->info(sprintf('[%s] User: %s', Log::SUBJECT_ACTIVE, $user->getEmail()));

        $user->setPassword($this->encoder->encodePassword($user, $user->getNewPassword()));
        $user->setEnabled(true);

        $this->workflow->applyActive($user);

        $this->manager->flush();
    }
}
