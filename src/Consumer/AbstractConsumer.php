<?php

namespace App\Consumer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

abstract class AbstractConsumer implements ConsumerInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    protected function isPing(AMQPMessage $message): bool
    {
        return Ping::BODY === $message->getBody();
    }

    protected function logMessage(AMQPMessage $message, string $subject): void
    {
        $this->logger->info(sprintf('[%s] Message received: %s', $subject, $message->getBody()));
    }

    protected function getDeserializerContext(array $groups): DeserializationContext
    {
        return DeserializationContext::create()->setGroups($groups);
    }
}
