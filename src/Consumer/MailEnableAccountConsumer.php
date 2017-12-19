<?php

namespace App\Consumer;

use App\Entity\User;
use App\Logger\Log;
use App\Mailer\EnableAccountMailer;
use App\Serializer\Format;
use App\Serializer\Group;
use JMS\Serializer\SerializerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class MailEnableAccountConsumer extends AbstractConsumer
{
    /** @var EnableAccountMailer */
    private $mailer;

    public function __construct(SerializerInterface $serializer, EnableAccountMailer $mailer, LoggerInterface $logger)
    {
        parent::__construct($serializer, $logger);
        $this->mailer = $mailer;
    }

    public function execute(AMQPMessage $message): bool
    {
        if ($this->isPing($message)) {
            return true;
        }

        $this->logMessage($message, Log::SUBJECT_ENABLE_ACCOUNT);

        /** @var User $user */
        $user = $this->serializer->deserialize(
            $message->getBody(),
            User::class,
            Format::JSON,
            $this->getDeserializerContext([Group::EVENT_ENABLE_ACCOUNT])
        );

        $this->mailer->execute($user);

        return true;
    }
}
