<?php

namespace App\Consumer;

use App\Entity\User;
use App\Logger\Log;
use App\Security\UserPasswordLost;
use App\Serializer\Format;
use App\Serializer\Group;
use JMS\Serializer\SerializerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class PasswordLostConsumer extends AbstractConsumer
{
    /** @var UserPasswordLost */
    private $userPasswordLost;

    public function __construct(SerializerInterface $serializer, UserPasswordLost $userPasswordLost, LoggerInterface $logger)
    {
        parent::__construct($serializer, $logger);
        $this->userPasswordLost = $userPasswordLost;
    }

    public function execute(AMQPMessage $message): bool
    {
        if ($this->isPing($message)) {
            return true;
        }

        $this->logMessage($message, Log::SUBJECT_PASSWORD_LOST);

        /** @var User $user */
        $user = $this->serializer->deserialize(
            $message->getBody(),
            User::class,
            Format::JSON,
            $this->getDeserializerContext([Group::EVENT_PASSWORD_LOST])
        );

        $this->userPasswordLost->execute($user);

        return true;
    }
}
