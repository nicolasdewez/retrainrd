<?php

namespace App\Consumer;

use App\Entity\User;
use App\Logger\Log;
use App\Security\UserRegistration;
use App\Serializer\Format;
use App\Serializer\Group;
use JMS\Serializer\SerializerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class RegistrationConsumer extends AbstractConsumer
{
    /** @var UserRegistration */
    private $userRegistration;

    public function __construct(SerializerInterface $serializer, UserRegistration $userRegistration, LoggerInterface $logger)
    {
        parent::__construct($serializer, $logger);
        $this->userRegistration = $userRegistration;
    }

    public function execute(AMQPMessage $message): bool
    {
        if ($this->isPing($message)) {
            return true;
        }

        $this->logMessage($message, Log::SUBJECT_REGISTRATION);

        /** @var User $user */
        $user = $this->serializer->deserialize(
            $message->getBody(),
            User::class,
            Format::JSON,
            $this->getDeserializerContext([Group::EVENT_REGISTRATION])
        );

        $this->userRegistration->execute($user);

        return true;
    }
}
